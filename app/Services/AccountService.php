<?php
namespace App\Services;

use App\Models\UserAccount;
use App\Models\User;
use App\Models\UserAccountRecord;
use App\Models\UserCash;
use App\Models\AdminSet;
use App\Models\Order;
use App\Models\UserTeamMember;
use App\Models\UserTeam;
use Illuminate\Support\Facades\Log;
use App\Models\OrderExtend;
use App\Models\Distribution;

/**
 * 帐户管理
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class AccountService extends BaseService
{
    /**
     * 我的金库订单列表
     *
     * @param int       $uid
     * @param number    $page
     * @param number    $limit
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getCoffersOrders($uid, $page = 1, $limit = 20)
    {
        //订单ids
        $orders = OrderExtend::with(['order'=>function($query){
            $query->select(['id','sn','name','tel','status','money']);
        }])->where(function($query) use($uid){
            $query->where('primary_distribution_uid', $uid)->whereHas('order',function($query){
                $query->where('status','<>',Order::STATUS_UNPAID);
            });
        })->orWhere(function($query) use($uid){
            $query->where('secondary_distribution_uid', $uid)->whereHas('order',function($query){
                $query->where('status','<>',Order::STATUS_UNPAID);
            });
        })->orWhere(function($query) use($uid){
            $query->where('captain_uid', $uid)->whereHas('order',function($query){
                $query->where('status','<>',Order::STATUS_UNPAID);
            });
        })->paginate($limit,['order_id','copy','primary_distribution_uid','secondary_distribution_uid','captain_uid']);
        
        $orders->each(function($item) use($uid){
            $item->addHidden('copy');
            $item->product = [
                'id'                => $item->copy['id'],
                'subtitle'          => $item->copy['subtitle'],
            ];
            $item->money = [
                'primary'     => self::_money($item->primary_distribution_uid, $item->copy['primaryd_distribution'], $item->order->money, $uid),
                'secondary' => self::_money($item->secondary_distribution_uid, $item->copy['secondary_distribution'], $item->order->money, $uid),
                'team' => self::_money($item->captain_uid, $item->copy['team_distribution'], $item->order->money, $uid),
            ];
        });
        
        return self::returnCode('sys.success', $orders);
    }
    
    /**
     * 计算每个用户角色通过订单能获得多少钱
     *
     * @param int   $distributionUid            分销用户id
     * @param array $distribution               分销分配规则
     * @param float $money                      订单金额
     * $param int   $authUid                    当前登陆用户id
     */
    static private function _money($distributionUid, $distribution, $money, $authUid)
    {
        $result = 0;
        
        if ($distributionUid && $distributionUid ==$authUid ) {
            if ($distribution['type'] == Distribution::ALLOCATION_TYPE_PERCENT) {
                $result = $money * $distribution['value'];
            } elseif ($distribution['type'] == Distribution::ALLOCATION_TYPE_MONEY) {
                $result = $distribution['value'];
            }
        }
        
        return roundDown($result);
    }
    
    /**
     * 获取用户收益记录
     *
     * @param integer $uid
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getAddMoneyRecord($uid, $page = 1, $limit = 20)
    {
        //收益的类型
        $objectTypeArr = [UserAccountRecord::OBJECT_TYPE_1,UserAccountRecord::OBJECT_TYPE_2,UserAccountRecord::OBJECT_TYPE_6];
        
        $list = UserAccountRecord::with(['order'=>function($query){
            $query->select(['id','sn','name','tel','status']);
        },'order.extend'=>function($query){
            $query->select(['order_id','copy']);
        }])->where('uid',$uid)->whereIn('object_type',$objectTypeArr)->paginate($limit, ['money','object_id']);
        
        $list->each(function ($item) {
            $item->addHidden('order');
            
            $item->product = [
                'id'        => $item->order->extend->copy['id'],
                'name'      => $item->order->extend->copy['name'],
                'subtitle'  => $item->order->extend->copy['subtitle']
            ];
            
            $item->order_info = [
                'id'        => $item->order->id,
                'sn'        => $item->order->sn,
                'name'      => $item->order->name,
                'tel'       => $item->order->tel,
                'status'    => $item->order->status,
            ];
        });
        
        return self::returnCode('sys.success', $list);
    }
    
    /**
     * 提现
     *
     * @param integer $uid
     * @param float $money
     * @return array
     */
    static public function withdraw($uid, $money)
    {
        //用户是否是达人
        $fields = ['role', 'status'];
        $conditions = [['id' => $uid],['role' => User::ROLE_TALENT]];
        
        $user = UserService::getUserInfoByConditions($conditions, $fields);
        if ($user['code'] != self::SUCCESS_CODE){
            return $user;
        }

        //此用户普通用户角色是否正常
        $userStatus = UserService::determineIfTheUserStatusIsNormal($user['data']->status);
        if ($userStatus['code'] != self::SUCCESS_CODE){
            return $userStatus;
        }
        
        //用记达人状态是否正常
        $talentStatus = UserService::determineIfTheUserTalentStatusIsNormal(null, $uid);
        if ($talentStatus['code'] != self::SUCCESS_CODE){
            return $talentStatus;
        }

        //获取用户帐户
        $account = self::getMyAccount($uid, ['balance']);
        if ($account['code'] != self::SUCCESS_CODE){
            return $account;
        }
        
        //对比帐户余额是否满足
        if ($account['data']->balance < $money){
            return self::returnCode('sys.insufficientFunds');
        }
        
        //对比提现金额是否满足条件
        if (config('console.withdraw_min_money') > $money){
            return self::returnCode('sys.amountIsTooSmall');
        }
        
        //是否有未处理的提现
        $hasCash = UserCash::where('uid', $uid)->where('status', UserCash::STATUS_1)->exists();
        if ($hasCash){
            return self::returnCode('sys.unprocessedWithdrawal');
        }
        
        //对金额进行操作
        return self::addMoney($uid, - $money, UserAccountRecord::OBJECT_TYPE_4, $account['data']->balance);
    }

    /**
     * 金额进行操作
     *
     * @param integer   $uid                        加钱的用户id
     * @param float     $changeMoney                多少钱
     * @param integer   $objectType                 类别
     * @param float     $balance                    帐户余额    0:须要重新查询
     * @param integer   $objectId                   对象id
     * @param array     $primaryDistribution        一级分销分成配制 NULL:须要重新查询
     * @param array     $secondaryDistribution      二级分销分成配制 NULL:须要重新查询
     * @param array     $recommendedUser            推荐分成配制 NULL:须要重新查询
     * @param array     $team                       团队分成配制 NULL:须要重新查询
     * @return array
     */
    static public function addMoney($uid, $changeMoney, $objectType, $balance = 0, $objectId = 0, $primaryDistribution = null, $secondaryDistribution = null, $recommendedUser = null, $team = null)
    {
        //用户查询
        $query = UserAccount::where('uid', $uid);
      
        //用户帐户余额
        if (!$balance){
            $userAccountBalance = clone $query;
            $balance = $userAccountBalance->first(['balance'])->balance;
        }
        
        $key = '';
        
        //帐户记录里变化后的金额
        $nowMoney = $balance + $changeMoney;
        
        switch ($objectType) {
            case UserAccountRecord::OBJECT_TYPE_1:
                //一级分销分成配制
                $primaryDistribution ? : $primaryDistribution = Order::withTrashed()->with(['product.primarydDistribution'])->find($objectId)->product->primarydDistribution;
                $key = 'userId:'.$uid.'_fDisId:'.$objectType.'_orderId:'.$objectId;
                break;
            case UserAccountRecord::OBJECT_TYPE_6:
                //团队分销分成配制
                $primaryDistribution ? : $secondaryDistribution = Order::withTrashed()->with(['product.secondaryDistribution'])->find($objectId)->product->primarydDistribution;
                $key = 'captainId:'.$uid.'_teamId:'.$objectType.'_orderId:'.$objectId;
                break;
            case UserAccountRecord::OBJECT_TYPE_3:
                //推荐分成配制
                $recommendedUser ? : $recommendedUser = AdminSet::where('type_name',AdminSet::TYPE_NAME_ATTENTION)->first(['value'])->value;
                $key = 'userId:'.$uid.'_recId:'.$objectType.'_recUid:'.$objectId;
                break;
            case UserAccountRecord::OBJECT_TYPE_4:
            case UserAccountRecord::OBJECT_TYPE_5:
                break;
            case UserAccountRecord::OBJECT_TYPE_2:
                //二级分销分成配制
                $primaryDistribution ? : $team = Order::withTrashed()->with(['product.teamDistribution'])->find($objectId)->product->teamDistribution;
                $key = 'userId:'.$uid.'_sDisId:'.$objectType.'_orderId:'.$objectId;
                break;
        }
        
        $addCash = true;
        // 加减(减钱为负数)
        $addMoney = $query->increment('balance', $changeMoney);
        
        // 如果是提现要生成一条提现
        if ($objectType == UserAccountRecord::OBJECT_TYPE_4) {
            $addCash = self::_addCash($uid, $changeMoney, $nowMoney);
            $objectId = $addCash;
        }
            
        // 生成记录
        try {
            $addRecord = self::_addRecord($uid, $changeMoney, $nowMoney, $objectType, $objectId, $key, $primaryDistribution, $secondaryDistribution, json_encode($recommendedUser), $team);
        } catch (\Exception $e) {
            Log::info($e);
            return self::returnCode('sys.fail');
        }
        
        if (! $addRecord) {
            return self::returnCode('sys.fail');
        }

        return self::returnCode('sys.success');
    }
    
    /**
     * 生成一条提现
     *
     * @param   int     $uid
     * @param   float   $money      提现多少钱
     * @param   float   $balance    提现后还有多少钱
     * @return  integer|boolean
     */
    static private function _addCash($uid, $money, $balance)
    {
        $saveData = [
            'uid'   => $uid,
            'money' => $money,
            'balance' => $balance
        ];
        
        $result = UserCash::create($saveData);
        
        return  $result ? $result->id : false;
    }
    
    /**
     * 添加一条记录
     *
     * @param int       $uid
     * @param float     $money
     * @param int       $objectType
     * @param int       $objectId
     * @param string    $key
     * @param string    $primaryDistribution
     * @param string    $secondaryDistribution
     * @param string    $recommendedUser
     * @param string    $team
     * @return boolean
     */
    static private function _addRecord($uid, $money, $nowMoney, $objectType, $objectId, $key, $primaryDistribution, $secondaryDistribution, $recommendedUser, $team)
    {
        $key ?: $key = $uid . '_' . $objectType . '_' . $objectId;
        
        $saveData = [
            'uid'                       => $uid,
            'money'                     => $money,
            'now_money'                 => $nowMoney,
            'object_type'               => $objectType,
            'object_id'                 => $objectId,
            'primary_distribution'      => $primaryDistribution,
            'secondary_distribution'    => $secondaryDistribution,
            'recommended_user'          => $recommendedUser,
            'team'                      => $team,
            'key'                       => $key
        ];
        
        $save = UserAccountRecord::create($saveData);
        
        if (! $save) {
            return false;
        }
        
        switch ($objectType) {
            case UserAccountRecord::OBJECT_TYPE_1:
                UserAccount::where('uid', $uid)->increment('primary_distribution_money', $money);
                
                //更新团队数据-队员卖出的产品金额
                $teamMember = UserTeamMember::where('team_member_uid', $uid)->where('status', UserTeamMember::STATUS_NORMAL)->first();
                
                if ($teamMember){
                    $captainUid = $teamMember->captain_uid;
                    
                    $teamMember->increment('amount_of_product_sold', $money);
                    //统计同一队长满足条件的队员个数
                    $count = UserTeamMember::where('captain_uid', $captainUid)->where('status', UserTeamMember::STATUS_NORMAL)->where('amount_of_product_sold','>',0)->count();
                    //更新满足的人数
                    UserTeam::where('uid', $captainUid)->where('status', UserTeam::STATUS_1)->update(['number_of_satisfied_popler'=>$count]);
                }
                
                break;
            case UserAccountRecord::OBJECT_TYPE_2:
                UserAccount::where('uid', $uid)->increment('team_distribution_money', $money);
                break;
            case UserAccountRecord::OBJECT_TYPE_3:
                UserAccount::where('uid', $uid)->increment('recommended_user_money', $money);
                break;
            case UserAccountRecord::OBJECT_TYPE_6:
                UserAccount::where('uid', $uid)->increment('secondary_distribution_money', $money);
                break;
        }
        return true;
    }
    
    /**
     * 获取用户帐户信息
     *
     * @param int $uid
     * @param array $fields
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getMyAccount($uid, $fields = ['*'])
    {
        $data = UserAccount::where('uid',$uid)->first($fields);
        
        if (!$data){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $talentStatus = UserService::determineIfTheUserTalentStatusIsNormal(null, $uid);
        
        if ($talentStatus['code'] != self::SUCCESS_CODE) {
            return $talentStatus;
        }
        
        return self::returnCode('sys.success', $data);
    }
}

