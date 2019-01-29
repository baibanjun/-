<?php
namespace App\Services;

use App\Models\Business;
use App\Models\BusinessLoginLog;
use App\Events\BusinessLogin;
use App\Models\Order;
use Carbon\Carbon;
use App\Jobs\deliverGoodsJob;
use App\Jobs\distributionJob;
use App\Models\LotteryUser;
use App\Models\LotteryDraw;
use App\Models\LotteryDrawList;

/**
 * 商家管理平台
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class BusinessService extends BaseService
{

    /**
     * 已核销
     *
     * @var integer
     */
    const CLASS_TYPE_1 = 1;

    /**
     * 未核销
     *
     * @var integer
     */
    const CLASS_TYPE_2 = 2;

    /**
     * 地方订单
     *
     * @var integer
     */
    const CLASS_TYPE_3 = 3;

    /**
     * 地方订单商家确认发货
     *
     * @param integer $uid
     * @param integer $orderId
     * @param string $expressCompany
     * @param string $expressNumber
     * @return array
     */
    static public function placeDeliverGoods($uid, $orderId, $expressCompany, $expressNumber)
    {
        $order = Order::where('business_id', $uid)->where('id', $orderId)
            ->where('type', Order::TYPE_PLACE)
            ->first([
            'id',
            'uid',
            'express_company',
            'express_number',
            'status'
        ]);
        
        // 订单是否存在
        if (! $order) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 订单状态是否正确
        if ($order->status != Order::STATUS_PAID) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $ext = [
            'express_company' => $expressCompany,
            'express_number' => $expressNumber
        ];
        
        $result = OrderService::changeOrderStatus($order, Order::STATUS_SHIPPED, $ext);
        
        if ($result) { // 修改成功加入7天自动收货
            $days = config('console.sys_deliver_goods_day');
            $job = (new deliverGoodsJob($order))->delay(Carbon::now()->addDays($days))
                ->onQueue('deliverGoods');
            dispatch($job);
            
            return self::returnCode('sys.success');
        }
        
        return self::returnCode('sys.fail');
    }
    
    /**
     * 优惠券管理
     *
     * @param int $uid
     * @param array $search
     * @param number $page
     * @param number $limit
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getLottery($uid, $search, $page = 1, $limit = 20)
    {
        $fields = ['id','title','created_at','updated_at'];
        
        $list = LotteryDraw::where('business_id', $uid)->where(function ($query) use ($search) {
            if (!is_null($search['c_start_time']) && !is_null($search['c_end_time'])){
                $query->whereBetWeen('created_at',[$search['c_start_time'], $search['c_end_time']]);
            }
            
            if (!is_null($search['u_start_time']) && !is_null($search['u_end_time'])){
                $query->whereBetWeen('updated_at',[$search['u_start_time'], $search['u_end_time']]);
            }
            
            if (!is_null($search['title'])){
                $query->where('title','like', '%'.$search['title'].'%');
            }
        })
            ->orderBy('id', 'desc')
            ->paginate($limit, $fields);

        return self::returnCode('sys.success', $list);
    }
    
    /**
     * 优惠卷详情
     *
     * @param int $uid
     * @param int $LotteryDrawId
     * @param array $search
     * @param number $page
     * @param number $limit
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function lotteryDetails($uid, $LotteryDrawId, $search, $page = 1, $limit = 20)
    {
        $fields = ['id','lottery_draw_id','name','draw_type'];
        
        $list = LotteryDrawList::where('lottery_draw_id', $LotteryDrawId)->where(function($query) use($search){
            if (!is_null($search['name'])){
                $query->where('name', $search['name']);
            }
        })->orderBy('id', 'desc')->paginate($limit, $fields);
        
        $list->each(function ($item) use ($LotteryDrawId) {
            //未使用数量 这里的字段名,不对,别问我为什么
            $item->inventory = LotteryUser::where('prize_id', $item->id)->where('status', LotteryUser::STATUS_0)
            ->where('is_share', LotteryUser::IS_SHARE_1)->where('end_date', '>=', date('Y-m-d'))->count();
            //已使用数量
            $item->has_send_num = LotteryUser::where('prize_id', $item->id)->where('status', LotteryUser::STATUS_1)->count();
            //已过期
            $item->expired = LotteryUser::where('prize_id', $item->id)->where('status', LotteryUser::STATUS_0)
                ->where('is_share', LotteryUser::IS_SHARE_1)
                ->where('end_date', '<', date('Y-m-d'))
                ->count();
        });
        
        
        return self::returnCode('sys.success', $list);
    }

    /**
     * 核销记录,未核销的订单
     *
     * @param integer $uid
     * @param array $search
     * @param integer $type
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    static public function getOrders($uid, $search, $type = 1, $page = 1, $limit = 20)
    {
        $query = Order::with([
            'standard' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            },
            'extend' => function ($query) {}
        ])->where('business_id', $uid);
        
        switch ($type) {
            case self::CLASS_TYPE_1:
                $fields = [
                    'id',
                    'code',
                    'sn',
                    'name',
                    'quantity',
                    'money',
                    'name',
                    'tel',
                    'pay_time',
                    'verification_time',
                    'product_id',
                    'standard_id'
                ];
                $query->where('type', '<>', Order::TYPE_PLACE)->where('status', Order::STATUS_COMPLETED);
                break;
            case self::CLASS_TYPE_2:
                $fields = [
                    'id',
                    'sn',
                    'name',
                    'quantity',
                    'money',
                    'name',
                    'tel',
                    'pay_time',
                    'product_id',
                    'standard_id'
                ];
                $query->where('type', '<>', Order::TYPE_PLACE)->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_RESERVED
                ]);
                break;
            case self::CLASS_TYPE_3:
                $fields = [
                    'id',
                    'sn',
                    'name',
                    'quantity',
                    'money',
                    'name',
                    'tel',
                    'pay_time',
                    'product_id',
                    'standard_id',
                    'status'
                ];
                $query->where('type', Order::TYPE_PLACE)->where('status', '>', Order::STATUS_UNPAID);
                break;
        }
        
        $query->where(function ($query) use ($search, $type) {
            if (! is_null($search['name'])) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
            if (! is_null($search['tel'])) {
                $query->where('tel', 'like', '%' . $search['tel'] . '%');
            }
            if (! is_null($search['sn'])) {
                $query->where('sn', 'like', '%' . $search['sn'] . '%');
            }
            if (! is_null($search['code']) && $type == self::CLASS_TYPE_1) {
                $query->where('code', 'like', '%' . $search['code'] . '%');
            }
        });
        
        $list = $query->orderBy('id', 'desc')->paginate($limit, $fields);
        
        $list->each(function ($item) {
            
            $item->addHidden([
                'extend'
            ]);
            $product = [
                'id' => $item->extend['copy']['id'],
                'name' => $item->extend['copy']['name'],
                'subtitle' => $item->extend['copy']['subtitle']
            ];
            
            $item->product = $product;
        });
        
        return self::returnCode('sys.success', $list);
    }

    /**
     * 核销优惠卷
     *
     * @param int $uid
     * @param string $code
     * @param boolean $showInfo
     */
    static public function verifyTheCoupon($uid, $code, $showInfo = false)
    {
        $startTime = microtime();
        
        $coupon = LotteryUser::with([
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
        ])->where('code', $code)
            ->where('is_share', LotteryUser::IS_SHARE_1)
            ->first();
        
        if (! $coupon) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 此优惠卷是否是此商家的
        if($coupon->lotteryDraw->business_id != $uid || !$coupon->uid){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 展示信息
        if (! $showInfo) {
            return self::returnCode('sys.success', $coupon);
        }
        
        // 开始走核销流程
        
        // 是否已核销过
        if ($coupon->status == LotteryUser::STATUS_1) {
            return self::returnCode('sys.codeIsAuthenticated');
        }
        
        // 是否过期
        if ($coupon->end_date < date('Y-m-d')) {
            return self::returnCode('sys.couponHasExpired');
        }
        
        $coupon->status = LotteryUser::STATUS_1;
        $coupon->verification_time = Carbon::now();
        
        return $coupon->save() ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 核销订单
     *
     * @param integer $uid
     * @param string $code
     * @return array
     */
    static public function verifyTheOrder($uid, $code, $showInfo = false)
    {
        $startTime = microtime();
        // 订单信息
        // $fields = ['id','code','type','sn','uid','business_id','product_id','standard_id','quantity','money','name','tel','status','verification_time','remark'];
        $order = Order::with([
            'extend' => function ($query) {},
            'standard' => function ($query) {
                $query->select([
                    'id',
                    'name'
                ]);
            }
        ])->where('business_id', $uid)
            ->where('type', '<>', Order::TYPE_PLACE)
            ->where('code', $code)
            ->first();
        
        // 订单是否存在
        if (! $order) {
            self::setLog('核销订单出错:订单是否存在', $startTime, $order);
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 未支付的订单也是不存在的
        if ($order->status == Order::STATUS_UNPAID) {
            self::setLog('核销订单出错:未支付的订单也是不存在的', $startTime, $order);
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $order->addHidden([
            'extend'
        ]);
        $product = [
            'id' => $order->extend['copy']['id'],
            'name' => $order->extend['copy']['name'],
            'subtitle' => $order->extend['copy']['subtitle']
        ];
        $order->product = $product;
        
        if (! $showInfo) { // 展示信息
            return self::returnCode('sys.success', $order);
        }
        
        // 开始走核销流程
        
        // 是否已核销过
        if ($order->status == Order::STATUS_COMPLETED) {
            return self::returnCode('sys.codeIsAuthenticated');
        }
        
        $ext = [
            'verification_time' => Carbon::now()
        ];
        
        $result = OrderService::changeOrderStatus($order, Order::STATUS_COMPLETED, $ext);
        
        if ($result) {
            // 收货成功后,触发分销,一级,二级,团队
            dispatch((new distributionJob($order))->onQueue('distribution'));
            
            return self::returnCode('sys.success');
        } else {
            return self::returnCode('sys.fail');
        }
    }

    /**
     * 通过手机验证码修改密码
     *
     * @param string $mobile
     * @param string $code
     * @param string $pwd
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updatePwd($mobile, $code, $pwd, $salt)
    {
        $fields = [
            'id',
            'username',
            'mobile',
            'password',
            'salt',
            'status'
        ];
        $business = self::getBusinessByField('mobile', $mobile, $fields);
        
        // 检测帐号是否存在
        if ($business['code'] != self::SUCCESS_CODE) {
            return $business;
        }
        
        // 状态是否正常
        if ($business['data']->status != Business::STATUS_NORMAL) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        // 测试手机验证码是否正确
        $verifyCode = SmsService::verifyCode($mobile, $code, 'forget_pwd');
        if ($verifyCode['code'] != self::SUCCESS_CODE) {
            return $verifyCode;
        }
        
        // 重置密码
        return $business['data']->where('id', $business['data']->id)->update([
            'password' => $pwd,
            'salt' => $salt
        ]) ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 忘记手机号 发送短信
     *
     * @param string $mobile
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function forgetPwd($mobile)
    {
        // 验证手机号是否存在
        $fields = [
            'id',
            'username',
            'mobile',
            'password',
            'salt',
            'status'
        ];
        $business = self::getBusinessByField('mobile', $mobile, $fields);
        
        // 检测帐号是否存在
        if ($business['code'] != self::SUCCESS_CODE) {
            return $business;
        }
        
        // 状态是否正常
        if ($business['data']->status != Business::STATUS_NORMAL) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        // 发送短信
        $send = SmsService::sendSms($mobile, 'forget_pwd');
        if (! $send['code'] == self::SUCCESS_CODE) {
            return $send;
        }
        
        return self::returnCode('sys.success', [
            'username' => $business['data']->username,
            'mobile' => $business['data']->mobile
        ]);
    }

    /**
     * 商家登陆
     *
     * @param string $userName
     * @param string $pwd
     * @param array $log
     *            ['platform','login_ip']
     * @return array
     */
    static public function login($userName, $pwd, $log)
    {
        // 判断帐号类型
        $type = self::_getAccountType($userName);
        
        // 帐户信息
        $fields = [
            'id',
            'username',
            'password',
            'status'
        ];
        $business = self::getBusinessByField($type, $userName, $fields);
        
        // 检测帐号是否存在
        if ($business['code'] != self::SUCCESS_CODE) {
            return $business;
        }
        
        // 状态是否正常
        if ($business['data']->status != Business::STATUS_NORMAL) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        // 密码是否正确
        if ($pwd != $business['data']->password) {
            return self::returnCode('sys.incorrect_password');
        }
        
        // 设置登陆状态
        $data = event(new BusinessLogin($business['data'], $log));
        
        return $data[0];
    }

    /**
     * 获取商家密码盐值
     *
     * @param string $username
     * @return array
     */
    static public function getSalt($username)
    {
        // 判断帐号类型
        $type = self::_getAccountType($username);
        
        return self::getBusinessByField($type, $username, [
            'salt'
        ]);
    }

    /**
     * 指定一个字段和值查找数据
     *
     * @param string $field
     * @param string $value
     * @param array $fields
     * @return array
     */
    static public function getBusinessByField($field, $value, $fields)
    {
        $data = Business::where($field, $value)->first($fields);
        
        return $data ? self::returnCode('sys.success', $data) : self::returnCode('sys.dataDoesNotExist');
    }

    /**
     * 帐号是否存在
     *
     * @param string $account
     * @param string $type
     * @return array
     */
    static public function accountExistsOrNot($account, $type)
    {
        switch ($type) {
            case 'mobile':
                $exists = Business::where('mobile', $account)->exists();
                break;
            case 'username':
                $exists = Business::where('username', $account)->exists();
                break;
        }
        
        return $exists ? self::returnCode('sys.success') : self::returnCode('sys.dataDoesNotExist');
    }

    /**
     * 判断帐号是哪一种类型
     *
     * @param string $account
     * @return string
     */
    static private function _getAccountType($account)
    {
        $type = 'username';
        
        if (preg_match('/^\d{11}$/', $account)) {
            $type = 'mobile';
        } elseif (preg_match("/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([.a-zA-Z0-9_-])+([.a-zA-Z0-9_-]+)+([.a-zA-Z0-9_-])$/", $account)) {
            $type = 'email';
        } else {
            $type = 'username';
        }
        
        return $type;
    }

    /**
     * 登陆日志
     *
     * @param array $log
     * @param boolean $clearErrNum
     * @return array
     */
    static public function setLoginLog($log)
    {
        if (config('console.login_delete_other_log')) {
            BusinessLoginLog::where('uid', $log['uid'])->delete();
        }
        
        $result = BusinessLoginLog::create($log);
        $business = self::getBusinessByField('id', $log['uid'], [
            'id',
            'name'
        ]);
        $result->business = $business['data'];
        
        return self::returnCode('sys.success', $result);
    }

    /**
     * 登陆信息 合并 加密
     *
     * @param Business $user
     * @return string
     */
    static public function passwordEncrypt(Business $user, $time)
    {
        $salted = $user->mobile . $user->password . $user->salt . config('console.reg_pwd_sn') . $time;
        return hash('sha256', $salted);
    }

    /**
     * 验证用户token是否正确
     *
     * @param string $field
     * @param string $value
     * @return array
     */
    static public function getLoginLog($token, $field = ['*'])
    {
        $loginLog = BusinessLoginLog::where('token', $token)->first($field);
        
        if ($loginLog) {
            return self::returnCode('sys.success', $loginLog);
        } else {
            return self::returnCode('sys.authenticationFailed');
        }
    }
}

