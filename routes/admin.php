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
Route::resource('test', 'Admin\V1\TestController');

Route::resource('user_talent_qrcode', 'Admin\V1\User\UserTalentQrcodeController');

Route::group(['namespace' => 'Admin'], function(){
    Route::group(['namespace' => 'V1'], function(){
        //登录
        Route::resource('login', 'LoginController');
        
        Route::group(['middleware' => ['adminToken']], function(){
            //注册用户
            Route::resource('register', 'RegisterController');
            
            //用户管理
            Route::group(['namespace' => 'User'], function(){
                Route::resource('user', 'UserController');
                Route::resource('user_talent', 'UserTalentController');
                Route::resource('user_team', 'UserTeamController');
                Route::resource('order', 'OrderController');
                Route::resource('distribution', 'DistributionController');
                Route::resource('user_cash', 'UserCashController');
                //Route::resource('user_talent_qrcode', 'UserTalentQrcodeController');
                
                Route::resource('export_order', 'ExportOrderController');
                Route::resource('export_distribution', 'ExportDistributionController');
                Route::resource('export_user_talent', 'ExportUserTalentController');
            });
                
            //联盟平台管理
            Route::group(['namespace' => 'Platform'], function(){
                Route::resource('product', 'ProductController');
                Route::resource('product_status', 'ProductStatusController');
                Route::resource('business_select', 'BusinessSelectController');
                Route::resource('product_city', 'ProductCityController');
                Route::resource('inviter_record', 'InviterRecordController');
                Route::resource('business', 'BusinessController');
                Route::resource('product_poser', 'ProductPoserController');
            });
                    
            //后台设置
            Route::resource('admin_set', 'AdminSetController');
            
            //文件上传
            Route::resource('upload', 'UploadController');
            
        });
    });

    Route::group(['namespace' => 'V2'], function(){
        Route::group(['middleware' => ['adminToken']], function(){
            Route::group(['namespace' => 'Platform'], function(){
                //抽奖管理
                Route::resource('lottery_draw', 'LotteryDrawController');
                Route::resource('lottery_draw_status', 'LotteryDrawStatusController');
            });
            Route::group(['namespace' => 'User'], function(){
                //商家入驻申请
                Route::resource('business_apply', 'BusinessApplyController');
                //用户优惠券管理
                Route::resource('lottery_user', 'LotteryUserController');
            });

        });
    });
});
    