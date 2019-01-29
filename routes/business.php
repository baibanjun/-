<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 */

Route::group(['namespace' => 'Business','middleware'=>['apiSign']], function(){
    Route::group(['namespace' => 'V1'], function(){
        //登陆
        Route::resource('login', 'LoginController');
        //忘记密码:发送短信,验证短信,修改密码
        Route::resource('forget_pwd', 'ForgetPasswordController');
        //JsapiTicket
        Route::resource('jsapi_ticket', 'JsapiTicketController',['only'=>['index']]);
        
        Route::group(['middleware'=>['businessToken']], function(){
            //本地订单详情,更新物流
            Route::resource('place_order', 'PlaceOrderController',['only'=>['show', 'update']]);
            //核销记录,未核销的订单,地方订单
            Route::resource('verify_the_order', 'VerifyTheOrderController',['only'=>['index','store', 'update']]);
            
            //核销记录,未核销的订单,地方订单
            Route::resource('verify_the_coupon', 'VerifyTheCouponsController');
        });
    });
});
    