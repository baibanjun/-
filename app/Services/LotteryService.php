<?php
namespace App\Services;

use App\Services\Interfaces\Lottery\LotteryInterface;
use App\Models\LotteryDraw;
use App\Models\AdminSet;
use App\Models\LotteryUser;
use App\Models\LotteryDrawList;
use App\Models\LotteryNumber;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use App\Jobs\setWxMenuJob;

/**
 * 抽奖
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class LotteryService extends BaseService implements LotteryInterface
{

    /**
     * 未使用
     * 
     * @var integer
     */
    const USER_COUPON_TYPE_1 = 1;

    /**
     * 已使用
     * 
     * @var integer
     */
    const USER_COUPON_TYPE_2 = 2;

    /**
     * 已过期
     * 
     * @var integer
     */
    const USER_COUPON_TYPE_3 = 3;

    /**
     * 已转赠
     * 
     * @var integer
     */
    const USER_COUPON_TYPE_4 = 4;

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::shareCallback()
     */
    public function shareCallback($callbackType, $id)
    {
        $startTime = microtime();
        switch ($callbackType) {
            case self::CALLBACK_TYPE_1:
                $result = $this->_addCoupons($id);
                self::setLog('分享后确认领取优惠卷', $startTime, [
                    'callbackType' => $callbackType,
                    'uid' => $this->uid,
                    'coupons_id' => $id,
                    $result
                ]);
                break;
            case self::CALLBACK_TYPE_2:
                $result = $this->_addTimes($id);
                self::setLog('分享后增加抽奖次数', $startTime, [
                    'callbackType' => $callbackType,
                    'uid' => $this->uid,
                    'lottery_id' => $id,
                    $result
                ]);
                break;
        }
        return $result;
    }

    /**
     * 分享活动后增加次数
     *
     * @param int $lotteryId
     */
    private function _addTimes($lotteryId)
    {
        $lotteryNumber = LotteryNumber::where('uid', $this->uid)->where('start_date', date('Y-m-d'))
            ->where('lottery_draw_id', $lotteryId)
            ->first();
        if (! $lotteryNumber) {
            return self::returnCode('sys.lotteryDoesExist');
        }
        
        if ($lotteryNumber->is_share == LotteryNumber::IS_SHARE_0) {
            // 增加相应次数
            $adminSet = AdminSet::where('type_name', AdminSet::TYPE_NAME_LOTTERY_DRAW)->first([
                'value'
            ]);
            $number = $adminSet->value['share_get_num'];
            $this->incrementTodayPrizeNumber($lotteryId, $number);
            
            // 设置今日已分享
            $lotteryNumber->is_share = LotteryNumber::IS_SHARE_1;
            $lotteryNumber->save();
            
            return self::returnCode('sys.success');
        }
        
        return self::returnCode('sys.lotteryDoesExist');
    }

    /**
     * 分享后获取优惠卷所有权
     */
    private function _addCoupons($couponsId)
    {
        $lotteryUser = LotteryUser::where('id', $couponsId)->where('uid', $this->uid)
            ->where('is_share', LotteryUser::IS_SHARE_0)
            ->first();
        if (! $lotteryUser)
            return self::returnCode('sys.couponsDoesExist');
        
        //检测此奖品还有没有,如果没有,提醒用户,来晚了
        $prize = LotteryDrawList::where('id', $lotteryUser->prize_id)->first(['id','inventory']);
        
        if (!$prize){
            return ;
        }
        
        if ($prize->inventory <= 0){
            return self::returnCode(sys.prizeIsGone);
        }
            
        // 奖品id
        $prizeId = $lotteryUser->prize_id;
        // 抽奖活动id
        $lotteryDrawsId = $lotteryUser->lottery_draws_id;
        
        $lotteryUser->is_share = LotteryUser::IS_SHARE_1;
        $lotteryUser->code = uniqid();
        $lotteryUser->save();
        
        // 管理库存
        LotteryDrawList::where('id', $prizeId)->increment('has_send_num');
        LotteryDrawList::where('id', $prizeId)->decrement('inventory');
        
        $nowInventory = LotteryDrawList::where('id', $prizeId)->first(['inventory','is_auto_hidden']);
        //如果存库为0 并且 is_auto_hidden 自动下线,重新生成菜单
        if ($nowInventory->inventory == 0 && $nowInventory->is_auto_hidden == 1){
            LotteryDraw::where('id', $lotteryDrawsId)->update(['status'=>LotteryDraw::STATUS_1]);
            dispatch((new setWxMenuJob())->onQueue('setwxmenu'));
        }
        
        // 奖品剩余次数
        LotteryDraw::where('id', $lotteryDrawsId)->decrement('surplus_number');
        
        return self::returnCode('sys.success');
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getCouponDetails()
     */
    public function getCouponDetails($couponId, $fields = ['*'])
    {
        $data = LotteryUser::with([
            'prize' => function ($query) {
                $query->select([
                    'id',
                    'lottery_draw_id',
                    'name',
                    'use_condition',
                    'description'
                ]);
            },
            'lotteryDraw' => function ($query) {
                $query->select([
                    'id',
                    'title',
                    'lottery_type',
                    'business_id'
                ]);
            },
            'lotteryDraw.business' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            }
        ])->where('uid', $this->uid)
            ->where('is_share', LotteryUser::IS_SHARE_1)
            ->where('status', LotteryUser::STATUS_0)
            ->where('end_date', '>=', date('Y-m-d'))
            ->find($couponId, $fields);
        
        if (! $data) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $url = config('console.business_verify_coupon_url') . '?code=' . $data->code;
        $qrImg = QrCode::format('png')->margin(0)
            ->size(130)
            ->generate($url);
        
        $qrImgUrl = 'data:image/png' . ';base64,' . chunk_split(base64_encode($qrImg));
        
        $data->qrCode = $qrImgUrl;
        
        return self::returnCode('sys.success', $data);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::giveCoupon()
     */
    public function giveCoupon($couponId)
    {
        $coupon = LotteryUser::where('id', $couponId)->where('uid', $this->uid)
            ->where('end_date', '>=', date('Y-m-d'))
            ->where('status', LotteryUser::STATUS_0)
            ->where('is_share', LotteryUser::IS_SHARE_1)
            ->where('is_give', LotteryUser::IS_GIVE_0)
            ->first();
        
        if (! $coupon) {
            return self::returnCode('sys.dataDoesNotExist');
        }
 
        $coupon->uid = 0;
        $coupon->from_uid = $this->uid;
        $coupon->send_date = Carbon::now();
        $coupon->is_give = LotteryUser::IS_GIVE_1;
        $coupon->code = uniqid();
        
        return $coupon->save() ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getGiveCoupon()
     */
    public function getGiveCoupon($couponId, $fromUid)
    {
        $coupon = LotteryUser::where('id', $couponId)->where('uid', 0)
        ->where('from_uid', $fromUid)
        ->where('end_date', '>=', date('Y-m-d'))
        ->where('status', LotteryUser::STATUS_0)
        ->where('is_share', LotteryUser::IS_SHARE_1)
        ->where('is_give', LotteryUser::IS_GIVE_1)
        ->first();
        
        if (! $coupon) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $coupon->uid = $this->uid;
        
        return $coupon->save() ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getUserCoupons()
     */
    public function getUserCoupons($type)
    {
        $query = LotteryUser::where('uid', $this->uid)->where('is_share', LotteryUser::IS_SHARE_1);
        switch ($type) {
            case self::USER_COUPON_TYPE_1:
                $query = $query->where('status', LotteryUser::STATUS_0)->where('end_date', '>=', date('Y-m-d'));
                break;
            case self::USER_COUPON_TYPE_2:
                $query = $query->where('status', LotteryUser::STATUS_1);
                break;
            case self::USER_COUPON_TYPE_3:
                $query = $query->where('status', LotteryUser::STATUS_0)->where('end_date', '<', date('Y-m-d'));
                break;
            case self::USER_COUPON_TYPE_4:
                $query = LotteryUser::where('from_uid', $this->uid)->where('is_share', LotteryUser::IS_SHARE_1);
                break;
        }
        
        $result = $query->with([
            'prize' => function ($query) {
                $query->select([
                    'id',
                    'name',
                    'start_date',
                    'end_date',
                    'use_condition',
                    'pic'
                ]);
            },
            'lotteryDraw' => function ($query) {
            $query->select([
                    'id',
                    'title',
                    'business_id'
                ]);
            },
            'lotteryDraw.business' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            }
        ])
            ->where('draw_type', LotteryUser::DRAW_TYPE_1)
            ->orderBy('id', 'desc')
            ->get();
        return self::returnCode('sys.success', $result);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::startLottery()
     */
    public function startLottery($id)
    {
        // 获取抽奖活动详情
        $data = LotteryDraw::with([
            'business' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            }
        ])->where('status', LotteryDraw::STATUS_2)
            ->find($id);
        if (! $data)
            return self::returnCode('sys.lotteryDoesExist');
        
        if ($data->surplus_number <= 0) {
            return self::returnCode('sys.prizeDoesExist');
        }
        // 用户有没有抽奖机会
        $userTodayPrizeNumber = $this->getUserTodayPrizeNumber($id, [
            'uid',
            'lottery_draw_id',
            'start_date',
            'number',
            'is_share'
        ]);
        if ($userTodayPrizeNumber->number <= 0)
            return self::returnCode('sys.notEnoughLotteries');
        
        // 对应的奖品列表
        $prizes = $this->getPrizeList($id);
        if ($prizes->isEmpty())
            return self::returnCode('sys.prizeDoesExist');
        
        // 增加参与次数
        LotteryDraw::where('id', $id)->increment('join_number');
        
        // 组合奖品数组
        $prizeArr = $this->_makePrizeArr($prizes);
        
        // 随机抽出一个奖品id
        $prizewinningNumber = array_random($prizeArr);
        
        // 奖品详情
        $prizeDetails = $this->getPrizeDetailsById($prizewinningNumber);
        if ($prizeDetails['code'] != self::SUCCESS_CODE)
            return $prizeDetails;
        
        // 抽奖次数减1
        $userTodayPrizeNumber->decrement('number');
        
        // 用户加入中奖数据
        $createLottery = $this->createLottery($prizeDetails['data']->lottery_draw_id, $prizeDetails['data']->id, $prizeDetails['data']->draw_type, $prizeDetails['data']->start_date, $prizeDetails['data']->end_date);
        if ($createLottery['code'] != self::SUCCESS_CODE)
            return $createLottery;
        
        $prizeDetails['data']->user_today_prize_number = $userTodayPrizeNumber;
        $prizeDetails['data']->business = $data->business;
        $prizeDetails['data']->coupons_id = $createLottery['data']->id;
        return $prizeDetails;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::createLottery()
     */
    public function createLottery($lottery_draws_id, $prize_id, $draw_type, $start_date, $end_date)
    {
        $this->incrementTodayPrizeNumber($lottery_draws_id, - 1);
        
        $saveData = [
            'uid' => $this->uid,
            'lottery_draws_id' => $lottery_draws_id,
            'prize_id' => $prize_id,
            'draw_type' => $draw_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_share' => LotteryUser::IS_SHARE_0
        ];
        
        $result = LotteryUser::create($saveData);
        
        if ($draw_type == LotteryDrawList::DRAW_TYPE_2) {
            // 管理库存
            LotteryDrawList::where('id', $prize_id)->increment('has_send_num');
            LotteryDrawList::where('id', $prize_id)->decrement('inventory');
            
            // 奖品剩余次数
            LotteryDraw::where('id', $lottery_draws_id)->decrement('surplus_number');
            
            $nowInventory = LotteryDrawList::where('id', $prize_id)->first(['inventory','is_auto_hidden']);
            //如果存库为0 并且 is_auto_hidden 自动下线,重新生成菜单
            if ($nowInventory->inventory == 0 && $nowInventory->is_auto_hidden == 1){
                LotteryDraw::where('id', $lotteryDrawsId)->update(['status'=>LotteryDraw::STATUS_1]);
                dispatch((new setWxMenuJob())->onQueue('setwxmenu'));
            }
        }
        
        return $result ? self::returnCode('sys.success', $result) : self::returnCode('sys.fail');
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::incrementTodayPrizeNumber()
     */
    public function incrementTodayPrizeNumber($lotteryId, $number)
    {
        $today = date('Y-m-d');
        return LotteryNumber::where('uid', $this->uid)->where('start_date', $today)
            ->where('lottery_draw_id', $lotteryId)
            ->increment('number', $number);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getUserTodayPrizeNumber()
     */
    public function getUserTodayPrizeNumber($lotteryId, $fields = ['*'])
    {
        $today = date('Y-m-d');
        
        $lottery = LotteryNumber::where('uid', $this->uid)->where('start_date', $today)
            ->where('lottery_draw_id', $lotteryId)
            ->first($fields);
        
        // 如果不存在今天的数据,就创建一条
        if (! $lottery) {
            // 获取系统配制的默认次数
            $lotteryDraw = AdminSet::where('type_name', AdminSet::TYPE_NAME_LOTTERY_DRAW)->first([
                'value'
            ]);
            
            $saveData = [
                'uid' => $this->uid,
                'lottery_draw_id' => $lotteryId,
                'start_date' => $today,
                'number' => $lotteryDraw->value['day_has_num'],
                'key' => 'uid:' . $this->uid . '_date:' . $today . '_lotteryId:' . $lotteryId,
                'is_share' => LotteryUser::IS_SHARE_0
            ];
            
            $lottery = LotteryNumber::create($saveData);
        }
        return $lottery;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getPrizeDetailsById()
     */
    public function getPrizeDetailsById($id, $fields = ['*'])
    {
        $prize = LotteryDrawList::find($id, $fields);
        return $prize ? self::returnCode('sys.success', $prize) : self::returnCode('sys.prizeDoesExist');
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getPrizeList()
     */
    public function getPrizeList($id)
    {
        $fields = [
            'id',
            'probability'
        ];
        $list = LotteryDrawList::where('lottery_draw_id', $id)->where('inventory', '>', 0)->get($fields);
        return $list;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getLotteryDetails()
     */
    public function getLotteryDetails($id)
    {
        $fields = [
            'id',
            'title',
            'lottery_type',
            'business_id',
            'poster',
            'description',
            'business_introduce',
            'status',
            'join_number',
            'surplus_number'
        ];
        
        $data = LotteryDraw::with([
            'lotteryDrawList' => function ($query) {
                $query->select([
                    'id',
                    'lottery_draw_id',
                    'name',
                    'draw_type',
                    'inventory',
                    'pic',
                    'description'
                ]);
            },
            'business' => function ($query) {
                $query->select([
                    'id',
                    'name',
                    'tel',
                    'address',
                    'lng',
                    'lat'
                ]);
            }
        ])->where('status', LotteryDraw::STATUS_2)
            ->where('id', $id)
            ->first($fields);
        
        if (! $data)
            return self::returnCode('sys.lotteryDoesExist');
        
        if ($data->surplus_number <= 0) {
            return self::returnCode('sys.prizeDoesExist');
        }
            
        // 福利群
        $data->weichat_group = AdminSet::where('type_name', AdminSet::TYPE_NAME_WEICHAT_GROUP)->first([
            'value'
        ]);
        
        // 用户抽奖次数设置
        $data->share_get_num = AdminSet::where('type_name', AdminSet::TYPE_NAME_LOTTERY_DRAW)->first([
            'value'
        ]);
        
        // 用户还剩多少次
        $data->number = $this->getUserTodayPrizeNumber($id, [
            'number'
        ]);
        
        // 用户今日有没有通过分享获取抽奖次数
        $data->user_today_prize_number = LotteryNumber::where('lottery_draw_id', $id)->where('uid', $this->uid)->first(['is_share']);
        
        return self::returnCode('sys.success', $data);
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Services\Interfaces\Lottery\LotteryInterface::getLotteryUser()
     */
    public function getLotteryUser($id)
    {
        $list = LotteryUser::with([
            'user' => function ($query) {
                $query->select([
                    'id',
                    'nickname',
                    'headimgurl'
                ]);
            },
            'prize' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            }
        ])->where('uid','>',0)->where('lottery_draws_id', $id)
            ->where('draw_type', LotteryUser::DRAW_TYPE_1)
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get([
            'id',
            'uid',
            'prize_id',
            'created_at'
        ]);
            
        $list->each(function ($item) {
            $item->user->nickname = cut_str($item->user->nickname, 1, 0).'*'.cut_str($item->user->nickname, 1, -1);
        });
        
        return self::returnCode('sys.success', $list);
    }

    /**
     * 将奖品id组合成对应概率的个数,并打乱顺序
     *
     * @param array $prizes
     *            所有的奖品
     * @return array
     */
    private function _makePrizeArr($prizes)
    {
        $array = [];
        foreach ($prizes as $prize) {
            $number = $prize['probability'] * 100;
            $prizeArr = $this->_makePrize($prize['id'], $number);
            $array = array_merge($array, $prizeArr);
        }
        shuffle($array);
        
        return $array;
    }

    /**
     * 这里是说明
     *
     * @param int $prizeId
     *            奖品id
     * @param int $number
     *            奖品id出现的次数
     * @return array
     */
    private function _makePrize($prizeId, $number)
    {
        $array = [];
        for ($i = 1; $i <= $number; $i ++) {
            $array[] = $prizeId;
        }
        return $array;
    }
}

