<?php
namespace App\Services;

use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

/**
 * 微信支付
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class WxPayService extends BaseService
{
    /**
     * 回调,订单设为已支付
     *
     * @param string $request
     * @return array
     */
    static public function notify($request)
    {
        $info = self::_xmlToArray($request);
        
        if (isset($info['result_code']) && $info['result_code'] === 'SUCCESS'){
            $data = OrderService::payNotifySuccess($info['out_trade_no']);
            if ($data['code'] != self::SUCCESS_CODE){
                return $data;
            }
            
            $result = [
                'return_code'   => 'SUCCESS',
                'return_msg'    => 'OK'
            ];
            
            Log::info('微信支付回调,支付成功,sn:'.$info['out_trade_no']);
            return self::_createXml($result);
        }
        
        Log::info('微信回调失败 result_code不为SUCCESS:'.json_encode($info));
    }
    
    /**
     * 预支付一个订单并生成支付签名
     *
     * @param Order $order
     * @param string $openId
     * @param string $ip
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function createOrder(Order $order, $openId, $ip)
    {
        //检测产品信息
        $check = OrderService::checkProduct($order->product_id, $order->standard_id, $order->quantity);
        if ($check['code'] != self::SUCCESS_CODE){
            return $check;
        }
        
        //过期时间
        $expirationTime = config('console.wx_pay_expiration_time');
        
        //检测订单是否过期
        if (isset($order->created_at)){
            $orderExpirationTime = strtotime($order->created_at) + $expirationTime;
            if ($orderExpirationTime < time()){
                return self::returnCode('sys.orderHasExpired');
            }
        }
        
        //查询redis有没有对应的值
        $key     = config('console.redis_key.wx_pay_sign').$order->id;
        $paySign = Redis::GET($key);
        
        if (!$paySign){
            $data = [
                'appid'             => config('console.appId'),         //公众账号ID
                'mch_id'            => config('console.wx_mchId'),      //商户号
                'nonce_str'         => self::_randStr(),                //随机字符串
                'body'              => rand(0,9999999),           //商品描述
                'out_trade_no'      => (string)$order->sn,              //商户订单号
                'total_fee'         => $order->money * 100,             //标价金额(分)
                'spbill_create_ip'  => $ip,                             //终端IP
                'time_expire'       => (string)Carbon::now()->addSeconds($expirationTime)->format('YmdHis'), //交易结束时间,也就是过期时间
                'notify_url'        => config('console.wx_notify_url'), //通知地址
                'trade_type'        => 'JSAPI',                         //交易类型
                'product_id'        => $order->id,                      //订单id
                'openid'            => $openId,                         //用户openid
            ];
            
            //签名
            $data['sign'] = self::_sign($data);
            $xml = self::_createXml($data);
            
            $url = config('console.wx_pay_url');
            
            $parameters = [
                'headers' => ['Content-Type' => 'text/xml; charset=UTF8'],
                'body' => $xml
            ];
            
            $client = new Client();
            $res = $client->request('post', $url, $parameters);
            
            $info = self::_xmlToArray($res->getBody());
            
            if ($info['return_code'] ==='FAIL' || $info['result_code'] === 'FAIL'){ //微信返回结果失败
                Log::info('微信预支付出错:'.json_encode($info));
                return self::returnCode('sys.wxPayFail');
            }
            
            $paySign = self::_paySign($info['prepay_id']);
            
            Redis::SET($key, json_encode($paySign));
            Redis::EXPIRE($key, $expirationTime);
        }else{
            $paySign = json_decode($paySign, true);
        }
        
        //设置规格的库存预售数量
        $onhandKey = config('console.redis_key.wx_pay_onhand').$order->standard_id.'_orderid_'.$order->id;
        $onhand    = Redis::SETEX($onhandKey, $expirationTime, $order->quantity);
        
        return self::returnCode('sys.success', $paySign);
        
    }
    
    /**
     * 生成付款信息
     *
     * @param string $prepayId 预支付号
     * @return array
     */
    static private function _paySign($prepayId)
    {
        $config = [
            'appId'     => config('console.appId'),
            'timeStamp' => (string)time(),
            'nonceStr'  => self::_randStr(),
            'package'   => 'prepay_id='.$prepayId,
            'signType'  => 'MD5'
        ];
        
        ksort($config);
        
        $config['sign'] = self::_sign($config);
        
        return $config;
    }
    
    /**
     * xml转成数据
     *
     * @param string $xml
     * 
     * @return array
     */
    static public function _xmlToArray($xml)
    {
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
    
    /**
     * 生成一个xml
     *
     * @param array $config
     * @return string
     */
    static private function _createXml($config)
    {
        $xml = "<xml>";
        foreach ($config as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml; 
    }
    
    /**
     * 生成签名
     *
     * @param   array   $config
     * @return  string
     */
    static private function _sign($config)
    {
        ksort($config);
        $str = '';
        
        foreach ($config as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str .= 'key='.config('console.wx_mch_secret');
        
        return md5($str);
    }

    /**
     * 生成一个随机字段串
     *
     * @return string
     */
    static private function _randStr()
    {
        return uniqid();
    }
}

