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

Route::group(['namespace' => 'Api'], function(){
    Route::group(['namespace' => 'V1'], function(){
        Route::resource('test', 'TestController');
        Route::resource('tip', 'TipController');

        Route::resource('wx', 'WxController');
        
        Route::resource('index', 'IndexController',['only'=>['index','store']]);
        Route::resource('set_menu', 'SetMenuController');
        
        //微信支付成功,回调
        Route::post('wx_pay_notify', 'WxPayController@notify');
        
        //须要API请求验证
        Route::group(['middleware'=>['apiSign','token']], function(){
            //获取城市列表
            Route::resource('city', 'CityController',['only'=>['index']]);
            //JsapiTicket
            Route::resource('jsapi_ticket', 'JsapiTicketController',['only'=>['index']]);
            //微信预支付,生成前端支付签名
            Route::resource('wx_pay', 'WxPayController',['only'=>['store']]);
            
            //上传文件
            Route::post('file/upload','UploadController@upload');
            //获取上传返回信息
            Route::get('file/upload','UploadController@getReturn');
            //用户自定义海报
            Route::post('poster','UploadController@poster');
            //产品对应的海报
            Route::get('poster', 'ProductController@getPoster');
            
            //本地,周边,地方,产品详情
            Route::resource('product', 'ProductController',['only'=>['index','show']]);
            //创建订单
            Route::resource('order', 'OrderController',['only'=>['store']]);
            //攻略,2台,探店,新闻详情
            Route::resource('news', 'NewsController');
            //商家申请
            Route::resource('business_apply', 'BusinessApplyController',['only'=>['store','index']]);
            
            //我的
            Route::group(['namespace' => 'My','prefix' => 'my'], function(){
                //全部订单,已支付,已预约,已完成,订单详情
                Route::resource('orders', 'OrdersController');
                //我的二维码
                Route::resource('index', 'IndexController',['only'=>['index']]);
                //申请成为达人 达人信息
                Route::resource('talent', 'TalentController',['only'=>['index','store']]);
                //加入团队
                Route::resource('team', 'TeamController');
                //我的金库
                Route::group(['middleware'=>['talent']], function(){
                    //达人首页
                    Route::resource('coffer', 'CofferController',['only'=>['index']]);
                    //提现
                    Route::resource('withdraw', 'WithdrawController',['only'=>['store']]);
                    //加钱记录
                    Route::resource('records', 'AddMoneyRecordsController',['only'=>['index']]);
                });
            });

            //抽奖
            Route::group(['namespace' => 'Lottery','prefix' => 'lottery'], function(){
                //抽奖活动详情,抽奖中奖人员列表,抽奖
                Route::resource('index', 'IndexController',['only'=>['index','store']]);
                //我的优惠卷,优惠卷详情,优惠卷转赠(优惠卷转赠被领取)
                Route::resource('my', 'MyCouponsController',['only'=>['index','show','store']]);
                //分享回调
                Route::resource('share_callback', 'ShareCallbackController');
            });
        });
    });
});
