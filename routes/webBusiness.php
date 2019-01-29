<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['prefix'=>'bus'],function(){

Route::get('/', function () {
	return view('business.index');
});

//登录
Route::get('/login', function () {
	return view('business.login');
});

//找回密码
Route::get('/find_pwd', function () {
	return view('business.findPwd');
});

//重置密码
Route::get('/reset_pwd', function () {
	return view('business.resetPwd');
});

//核销记录
Route::get('/record', function () {
	return view('business.record');
});

//未核销记录
Route::get('/verification', function () {
	return view('business.verification');
});

//联盟商城订单
Route::get('/order', function () {
	return view('business.order');
});

//订单详情
Route::get('/orderDetails', function () {
	return view('business.orderDetails');
});

//优惠券核销
Route::get('/coupons', function () {
	return view('business.coupons');
});

//优惠券管理
Route::get('/couponsManage', function () {
	return view('business.couponsManage');
});

//优惠券管理查看详情
Route::get('/couponsDetails', function () {
	return view('business.couponsDetails');
});


//二维码中间页面
//订单详情
Route::get('/qr', function () {
	return view('business.qr');
});

Route::get('/coupon_qr', function () {
	return view('business.coupon_qr');
});
	
// });


