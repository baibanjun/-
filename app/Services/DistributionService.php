<?php
namespace App\Services;

use App\Services\Interfaces\Order\DistributionInterface;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderExtend;
use App\Models\Distribution;
use App\Models\UserTeamMember;
use App\Models\UserTalent;
use App\Models\User;
use App\Models\UserTeam;
use App\Models\AdminSet;
use App\Models\UserAccountRecord;
use App\Services\Wx\WxSendService;

/**
 * 分销,订单分成,团队分成
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class DistributionService extends BaseService implements DistributionInterface
{   
    /**
     * 订单模型
     * @var array
     */
    public $order;
    
    /**
     * 一级分销用户id
     * @var integer
     */
    public $pDisUid = 0;
    
    /**
     * 二级分销用户id
     * @var integer
     */
    public $sDisUid = 0;
    
    /**
     * 产品对应的分销配制,一级,二级,团队
     * @var array
     */
    public $proDisConfig;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    
    public function distribution($config)
    {
        $startTime = microtime();
        $wxSendService = new WxSendService();
        
        // 一级分销加钱,加钱成功后,还有更新对应队长的信息
        $fUid = $config['primary']['uid'];
        $fMoney = $config['primary']['money'];
        if ($fUid && $fMoney){
            AccountService::addMoney($fUid, $fMoney, UserAccountRecord::OBJECT_TYPE_1, 0, $this->order->id);
            $wxSendService->sendTemplateMsg($fUid, $this->order->id, $fMoney);
        }
        setLog('订单分销:一级分销', $startTime, ['uid'=>$fUid, 'money'=>$fMoney]);
        
        // 二级分销加钱
        $sUid = $config['secondary']['uid'];
        $sMoney = $config['secondary']['money'];
        if ($sUid && $sMoney){
            AccountService::addMoney($sUid, $sMoney, UserAccountRecord::OBJECT_TYPE_6, 0, $this->order->id);
            $wxSendService->sendTemplateMsg($sUid, $this->order->id, $sMoney);
        }
        setLog('订单分销:二级分销', $startTime, ['uid'=>$sUid, 'money'=>$sMoney]);
        
        // 团队分销加钱
        $teamUid = $config['team']['uid'];
        $teamMoney = $config['team']['money'];
        if ($teamUid && $teamMoney){
            AccountService::addMoney($teamUid, $teamMoney, UserAccountRecord::OBJECT_TYPE_2, 0, $this->order->id);
            $wxSendService->sendTemplateMsg($teamUid, $this->order->id, $teamMoney);
        }
        setLog('订单分销:团队分销', $startTime, ['uid'=>$teamUid, 'money'=>$teamMoney]);
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Order\DistributionInterface::howMuchMoney()
     */
    public function howMuchMoney()
    {
        $this->proDisConfig = $this->getOrderDisConfig();
        $this->getOrderDisUser();

        return [
            'primary'   => $this->_money($this->proDisConfig->primaryDistribution, $this->pDisUid),
            'secondary' => $this->_money($this->proDisConfig->secondaryDistribution, $this->sDisUid),
            'team'      => $this->_money($this->proDisConfig->teamDistribution, $this->getCaptain($this->pDisUid)),
        ];
        
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Order\DistributionInterface::getOrderDisConfig()
     */
    public function getOrderDisConfig()
    {
        $data = Product::with([
            'primarydDistribution'=>function($query){
                    $query->select(['id','class_type','type','value']);
                },'secondaryDistribution'=>function($query){
                    $query->select(['id','class_type','type','value']);
                },'teamDistribution'=>function($query){
                    $query->select(['id','class_type','type','value']);}
                ])->where('id', $this->order->product_id)->first(['id','primary_distribution_id','secondary_distribution_id','team_distribution_id']);

        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Order\DistributionInterface::getOrderDisUser()
     */
    public function getOrderDisUser()
    {
        $data = OrderExtend::with(['f_talent'=>function($query){
            $query->where('status',UserTalent::STATUS_1)->select(['uid','status']);
        },'s_talent'=>function($query){
            $query->where('status',UserTalent::STATUS_1)->select(['uid','status']);
        },'f_talent.user'=>function($query){
            $query->where('status', User::STATUS_NORMAL)->select(['id','role','status']);
        },'s_talent.user'=>function($query){
            $query->where('status', User::STATUS_NORMAL)->select(['id','role','status']);
        }])->find($this->order->id, ['order_id','primary_distribution_uid','secondary_distribution_uid']);
        
        //是否有一级
        if ($data->f_talent && $data->f_talent->user){
            $this->pDisUid = $data->primary_distribution_uid;
        }
        
        //是否有二级
        if ($data->s_talent && $data->s_talent->user){
            $this->sDisUid = $data->secondary_distribution_uid;
        }
        
        //一二级同一个时,二级为0
        if ($this->pDisUid == $this->sDisUid){
            $this->sDisUid = 0;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Services\Interfaces\Order\DistributionInterface::getCaptain()
     */
    public function getCaptain($teamMemberUid, $isMeet = true)
    {
        $captainUid = 0;
        
        //队长基本信息
        $data = UserTeamMember::with(['talent'=>function($query){
            $query->where('status', UserTalent::STATUS_1)->select(['uid','status']);
        },'talent.user'=>function($query){
            $query->where('status', User::STATUS_NORMAL)->select(['id','role','status']);
        }])->where('team_member_uid',$teamMemberUid)->where('status', UserTeamMember::STATUS_NORMAL)->first(['captain_uid']);
        
        if ($data && $data->talent && $data->talent->user){
            $captainUid = $data->captain_uid;
        }
        
        if (!$isMeet){
            return $captainUid;
        }
        
        //队长是否满足配制的分成条件,如果不,返回0
        return $this->_teamMeetTheConditions($captainUid) == false ? 0 : $captainUid;
    }
    
    /**
     * 团队是否满足分成条件
     *
     * @param   integer $captainUid 队长ID
     * @return  boolean
     */
    private function _teamMeetTheConditions($captainUid)
    {
        $result = false;
        
        //团队
        $team = UserTeam::find($captainUid,['uid','number_of_team_users','number_of_satisfied_popler']);
        if (!$team){
            return false;
        }
        
        //设置的条件
        $set = AdminSet::where('type_name',AdminSet::TYPE_NAME_TEAM_SETTING)->first(['value']);
        
        if ($team->number_of_team_users >= $set->value['team_number'] && $team->number_of_satisfied_popler >= $set->value['sale_team_number']){
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * 通过分配配制获取用户得到的钱
     *
     * @param array     $config         配制
     * @param integer   $uid            角色用户id
     * @param float     $orderMoney     订单总金额
     */
    private function _money($config, $uid)
    {
        $money = 0;
        
        switch ($config->type) {
            case Distribution::ALLOCATION_TYPE_PERCENT:
                $money = $this->order->money * $config->value;
                break;
            case Distribution::ALLOCATION_TYPE_MONEY:
                $money = $config->value;
                break;
        }
        
        return [
            'uid'   => (int) $uid,
            'money' => (float) roundDown($money)
        
        ];
    }
}

