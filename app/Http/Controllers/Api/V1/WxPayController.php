<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Services\WxPayService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class WxPayController extends BaseController
{
    /**
     * 生成支付签名
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $order = OrderService::getMyOrderInfo($user->id, $request->order_id);
        if ($order['code'] != self::SUCCESS_CODE){
            return $order;
        }
        
        return WxPayService::createOrder($order['data'], $user->openid, $request->ip());
    }
    
    /**
     * 支付成功,回调
     *
     * @param Request $request
     */
    public function notify(Request $request)
    {
        return WxPayService::notify(file_get_contents("php://input"));
    }
}
