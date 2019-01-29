<?php
namespace App\Services\Wx;

use App\Services\WeixinService;
use App\Models\Order;
use App\Models\User;
use function GuzzleHttp\json_decode;

/**
 * 微信主动发送事件
 *
 * @author lilin
 *         wx(tel):13408099056
 *         qq:182436607
 *        
 */
class WxSendService extends WxToolService
{
    /**
     * 发送文字信息
     *
     * @param int $uid
     */
    public function sendTextMsg($openid)
    {
        $start = microtime();
        
        $url = __('url.send_text', [
            'access_token' => WeixinService::getAccessToken()
        ]);
        
        $content = [
            'touser'    => $openid,
            'msgtype'   => 'text',
            'text'      => [
                            'content' => "点击下方链接，成功生成个人专属国民暖男大黄蜂海报:\n".config('console.poster_url')
                            ]
        ];
        
        $tmpInfo = self::sendWxHttp($url, $content);
    }
    
    /**
     * 发送结算模板信息
     *
     * @param int $uid
     * @param int $orderId
     * @param float $commission
     * @return array|string|mixed
     */
    public function sendTemplateMsg($uid, $orderId, $commission)
    {
        $start = microtime();
        
        //通知的用户
        $user = User::find($uid,['id','openid']);
        
        //相关订单信息
        $order = Order::with(['product'=>function($query){
            $query->select(['id','name']);
        }])->where('id',$orderId)->first(['id','sn','uid','business_id','product_id','standard_id','type','money','name','tel','status']);
        
        $userName       = starReplace($order->name);
        $mobile         = hideTel($order->tel);
        $productNmae    = $order->product->name;
        
        $typeTip = '';
        if ($order->type == Order::TYPE_LOCAL){
            $typeTip        = Order::TYPE_LOCAL_TIP;
        }elseif ($order->type == Order::TYPE_PLACE){
            $typeTip        = Order::TYPE_PLACE_TIP;
        }else{
            $typeTip        = Order::TYPE_CIRCUM_TIP;
        }
        
        
        $content = [
            "touser"        => $user->openid,
            'template_id'   => config('console.msg_template_id'),
            "data" => [
                "first" => [
                    "value" => config('console.msg_template_first'),
                ],
                "keyword1" => [
                    "value" => $order->sn,
                ],
                "keyword2" => [
                    "value" => $typeTip.'订单',
                ],
                "keyword3" => [
                    "value" => "已完成",
                ],
                "keyword4" => [
                    "value" => $order->money.'元',
                ],
                "keyword5" => [
                    "value" => $commission.'元',
                ],
                "remark" => [
                    "value" => "商品名称：$productNmae\n买家姓名：$userName\n买家手机号：$mobile",
                ],
            ]
        ];

        $url = __('url.send_template', [
            'access_token' => WeixinService::getAccessToken()
        ]);
        
        $tmpInfo = self::sendWxHttp($url, $content);
        
        if ($tmpInfo['errcode'] !== 0 || $tmpInfo['errmsg'] !== 'ok'){
            self::setLog('分销佣金结算通知:出错', $start, [$tmpInfo,'uid'=>$uid,'order_id'=>$orderId,'commission'=>$commission]);
        }
        
        self::setLog('分销佣金结算通知:成功', $start, [$tmpInfo,'uid'=>$uid,'order_id'=>$orderId,'commission'=>$commission]);

        return $tmpInfo;
    }
}

