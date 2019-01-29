<?php
namespace App\Services;

use App\Models\Order;
use App\Models\ProductStandard;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\OrderExtend;
use App\Events\OrderExt;
use App\Jobs\distributionJob;
use App\Models\UserOrderStatistic;
use App\Models\Business;

/**
 * 订单管理
 *
 * @author lilin
 *
 */
class OrderService extends BaseService
{
    /**
     * 微信回调支付成功
     *
     * @param string $orderSn
     */
    static public function payNotifySuccess($orderSn)
    {
        $order = Order::withTrashed()->with(['product'=>function($query){
            $query->select(['id','send_sms_or_not','booking_information','name']);
        }])->where('sn',$orderSn)->first();
        
        //判断订单状态是否正常
        if ($order->status != Order::STATUS_UNPAID){
            Log::info('微信支付回调,订单状态不为未支付,sn:'.$orderSn);
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $ext = [
            'pay_time' => Carbon::now(),
            'code'     => uniqid()
        ];
        
        $result = self::changeOrderStatus($order, Order::STATUS_PAID, $ext);
        
        if ($result){
            //检查是否发放预约短信
            if ($order->product->send_sms_or_not == Order::SEND_SMS_1){
                // 发送短信
                $msgStr = mb_substr($order->product->name, 0, 19, 'utf-8');
                $send = SmsService::sendSms($order->tel, 'send_code', $msgStr.' ', $ext['code']);
                if ($send['code'] != self::SUCCESS_CODE){
                    self::setLog('订单支付成功,发送预约短信失败:', 0, $send);
                }
                self::changeOrderStatus($order, Order::STATUS_RESERVED);
            }
            return self::returnCode('sys.success');
        }else{
            Log::info('微信支付回调,更改订单状态错误');
            return self::returnCode('sys.fail');
        }
    }
    
    /**
     * 更改订单状态,状态加入用户订单数统计
     *
     * @param Order $order
     * @param integer $changeStatus
     * @param array $ext
     */
    static public function changeOrderStatus(Order $order, $changeStatus, $ext = [])
    {
            $userOrderStatistic = UserOrderStatistic::firstOrCreate(['uid'=>$order->uid],['uid'=>$order->uid]);
            
            $updateData = $ext;
            
            switch ($changeStatus) {
                case Order::STATUS_UNPAID: //初始状态就是未支付,所以这里不须要改状态
                    UserOrderStatistic::where(['uid'=>$order->uid])->increment('unpaid_order_quantity');
                    break;
                case Order::STATUS_PAID:
                    $userOrderStatistic->increment('paid_order_quantity');
                    $userOrderStatistic->decrement('unpaid_order_quantity');
                    
                    $updateData['status'] = Order::STATUS_PAID;
                    
                    break;
                case Order::STATUS_RESERVED:
                    $userOrderStatistic->increment('subscribe_order_quantity');
                    $userOrderStatistic->decrement('paid_order_quantity');
                    
                    $updateData['status'] = Order::STATUS_RESERVED;
                    break;
                case Order::STATUS_SHIPPED:
                    $userOrderStatistic->increment('shipped_order_quantity');
                    $userOrderStatistic->decrement('paid_order_quantity');
                    
                    $updateData['status'] = Order::STATUS_SHIPPED;
                    break;
                case Order::STATUS_COMPLETED:
                    $userOrderStatistic->increment('completed_order_quantity');
                    if ($order->type != Order::TYPE_PLACE){
                        if ($order->status == Order::STATUS_PAID){
                            $userOrderStatistic->decrement('paid_order_quantity');
                        }else{
                            $userOrderStatistic->decrement('subscribe_order_quantity');
                        }
                    }else{
                        $userOrderStatistic->decrement('shipped_order_quantity');
                    }
                    
                    $updateData['status'] = Order::STATUS_COMPLETED;
                    $updateData['complete_time'] = Carbon::now();
                    break;
            }
            
            $result = Order::where('id',$order->id)->update($updateData);
            
            //减少对应的库存数
            if ($result && $changeStatus == Order::STATUS_PAID){
                //产品已售数量 +1
                ProductStandard::where('pid',$order->product_id)->increment('quantity_sold');
                //减少对应的库存数
                $dec = ProductService::decOnhand($order->standard_id, $order->quantity, $order->id);
            }
            
            return true;
    }
    
    /**
     * 系统自动收货或者用户主动收货
     *
     * @param   integer $uid  订单用户ID
     * @param   integer $id   订单id
     * @param   boolean $sys  是否系统自动收货
     * @return  array
     */
    static public function deliverGoods($uid, $id, $sys = false)
    {
        $order = Order::where('id', $id)->where('uid', $uid)->first();
        
        //订单是否存在
        if (!$order){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        //订单订单是否可以收货
        if ($order->status != Order::STATUS_SHIPPED){
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        // 系统自动收货还是用户主动收货
        $ext = [
            'complete_time' => Carbon::now(),
            'received_role' => $sys ? Order::RECEIVED_ROLE_SYS : Order::RECEIVED_ROLE_USER
        ];
        
        $result = self::changeOrderStatus($order, Order::STATUS_COMPLETED, $ext);
        
        if ($result) {
            // 收货成功后,触发分销,一级,二级,团队
            dispatch((new distributionJob($order))->onQueue('distribution'));
            
            return self::returnCode('sys.success');
        } else {
            return self::returnCode('sys.fail');
        }
    }
    
    /**
     * 获取指定商家的订单详情
     *
     * @param integer   $bussinessId    商家id
     * @param integer   $id             订单id
     * @param integer   $type           显示类别 0:不区分 1:本地,2:周边,3:地方
     * @param array     $fields         显示字段
     */
    static public function getBusinessOrderInfo($bussinessId, $id, $type = 0, $fields = ['*'])
    {
        $query = Order::with([
            'standard' => function ($query) {
                $query->select(['id','name']);
            },'user'=>function($query){
                $query->select(['id','nickname']);
            },'extend'=>function($query){
            },'business'=>function($query){
                $query->select(['id','name']);
            }]);
        
        $query = $query->where(function ($query) use ($type) {
            ! $type ?: $query->where('type', $type);
        });
        
        $order = $query->where('business_id', $bussinessId)->where('id', $id)->first($fields);
        if (!$order){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $order->addHidden(['extend']);
        $product = [
            'id' => $order->extend['copy']['id'],
            'name' => $order->extend['copy']['name'],
            'subtitle' => $order->extend['copy']['subtitle'],
        ];
        
        
        $order->product = $product;
            
        return self::returnCode('sys.success', $order);
    }
    
    /**
     * 获取指定用户订单详情
     *
     * @param int   $uid        用户id
     * @param int   $id         订单id
     * @param array $fields     显示字段
     * @return array
     */
    static public function getMyOrderInfo($uid, $id, $fields = ['*'])
    {
        $order = Order::with(['extend'=>function($query){
            $query->select(['order_id','copy']);
        }])->where('uid', $uid)->where('id', $id)->first($fields);
        
        if (! $order || ! $order->extend) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        $order->addHidden(['extend']);
        
        $order->product = [
            'id' => $order->extend->copy['id'],
            'name' => $order->extend->copy['name'],
            'subtitle' => $order->extend->copy['subtitle'],
        ];
        
        return self::returnCode('sys.success', $order);
    }
    
    /**
     * 获取我的对应状态的所有订单
     *
     * @param int       $uid
     * @param int       $type       1:全部订单 2:已支付+已发货 3:已预约 4:已完成 5:已支付+已完成
     * @param array     $fields
     * @param int       $page
     * @param int       $limit
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getMyOrders($uid, $type, $fields = ['*'], $page = 1, $limit = 20)
    {
        $list = Order::with(['extend'=>function($query){
            $query->select(['order_id','copy']);
        }])->where('uid', $uid)->where(function($query) use($type){
            switch ($type){ // 全部订单显示所有状态
                case 2:
                    $query->whereIn('status', [Order::STATUS_PAID, Order::STATUS_SHIPPED]);
                    break;
                case 3:
                    $query->where('status', Order::STATUS_RESERVED);
                    break;
                case 4:
                    $query->where('status', Order::STATUS_COMPLETED);
                    break;
            }
        })->orderBy('id','desc')->paginate($limit, $fields);
        
        $list->each(function ($item) {
            $item->addHidden(['extend']);
            if ($item->extend){
                $item->product = [
                    'id' => $item->extend->copy['id'],
                    'name' => $item->extend->copy['name'],
                    'subtitle' => $item->extend->copy['subtitle']
                ];
            }else{
                self::setLog('订单没有找到extend',0,['order_id'=>$item->id]);
            }
            
        });
        
        return self::returnCode('sys.success', $list);
    }
    
    /**
     * 生成一条订单
     *
     * @param array $saveData
     * @return array
     */
    static public function createOrder($saveData)
    {
        self::setLog('准备创建订单',0, $saveData);
        
        // 产品信息
        $info = Product::with(['primarydDistribution','secondaryDistribution','teamDistribution','standards'])->find($saveData['product_id']);
        if (!$info){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 通过规格,生成订单总价
        $standard = ProductStandard::where('id', $saveData['standard_id'])->where('pid', $saveData['product_id'])->first(['id','sale_price','onhand']);
        if (!$standard){
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 产品信息检测
        $check = self::checkProduct($saveData['product_id'], $saveData['standard_id'], $saveData['quantity'], $standard);
        if ($check['code'] != self::SUCCESS_CODE){
            return $check;
        }

        $saveData['money']          = $standard->sale_price * $saveData['quantity'];
        $saveData['business_id']    = $info->business_id;
        $saveData['type']           = $info->type;
        $saveData['sn']             = self::_createOrderNum();
        
        $result = Order::create($saveData);
        
        //更新订单扩展属性,确认一级分销,二级分销用户,订单快照
        event(new OrderExt($info, $result, $saveData['f'], $saveData['s']));
        
        //调用更改订单状态,加入数量统计
        self::changeOrderStatus($result, Order::STATUS_UNPAID);
        
        return self::returnCode('sys.success', $result);
    }
    
    /**
     * 产品检测
     *
     * @param integer $productId    产品id
     * @param integer $standardId   规格id
     * @param integer $quantity     购买数据
     * @param ProductStandard $standard 规格信息 为null时通过 $standardId 查询
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function checkProduct($productId, $standardId, $quantity, ProductStandard $standard = null)
    {
        //产品检测信息
        $product = Product::withTrashed()->with(['business'=>function($query){
            $query->select(['id','status']);
        }])->find($productId,['id','business_id','status','is_countdown','updated_at','time_limit']);
        
        //产品状态
        if ($product->status != Product::STATUS_ITEM_UPSHELF){
            return self::returnCode('sys.proStatusIsNotNormal');
        }
        
        //产品是否倒计时结束
        if ($product->is_countdown == Product::IS_COUNTDOWN_1){
            $endTime = strtotime($product->updated_at) + $product->time_limit;
            if ($endTime < time()){
                return self::returnCode('sys.productEndOfCountdown');
            }
        }
        
        //商家状态
        if ($product->business->status != Business::STATUS_NORMAL){
            return self::returnCode('sys.busStatusIsNotNormal');
        }
        
        if (! $standard) {
            $standard = ProductStandard::find($standardId);
        }
        
        //预售库存数
        $bookingNumber = ProductService::getBookingNumber($standardId);
        
        //库存
        $onhand = $standard->onhand - $quantity - $bookingNumber;
        self::setLog('产品下单数据检测',0,['productId'=>$productId, 'standardId'=>$standardId,'quantity'=>$quantity,'onhand'=>[$standard->onhand , $quantity , $bookingNumber]]);
        if ($onhand < 0) {
            return self::returnCode('sys.insufficientInventory');
        }
        
        return self::returnCode('sys.success');
    }
    
    /**
     * 更新订单扩展属性,确认一级分销,二级分销用户,订单快照
     *
     * @param array     $product
     * @param integer   $orderId
     * @param integer   $primaryDistributionUid
     * @param integer   $secondaryDistributionUid
     * @return array
     */
    static public function createExt($product, $orderId, $primaryDistributionUid, $secondaryDistributionUid)
    {
        $captainUid = 0;
        if ($primaryDistributionUid) {
            $order = Order::withTrashed()->find($orderId);
            $dis = new DistributionService($order);
            $captainUid = $dis->getCaptain($primaryDistributionUid, false);
        }
        
        $saveData = [
            'order_id'                      => $orderId,
            'primary_distribution_uid'      => $primaryDistributionUid,
            'secondary_distribution_uid'    => $secondaryDistributionUid,
            'captain_uid'                   => $captainUid,
            'copy'                          => $product
        ];
        $result = OrderExtend::create($saveData);
        
        return self::returnCode('sys.success', $result);
    }
    
    /**
     * 生成订单号
     *
     * @return string
     */
    static private function _createOrderNum()
    {
        return $danhao = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}

