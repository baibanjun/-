<?php

namespace App\Http\Controllers\Business\V1;

use App\Services\BusinessService;
use App\Http\Requests\Business\VerifyTheOrderStore;
use App\Http\Requests\Business\VerifyTheOrderUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * 优惠卷
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class VerifyTheCouponsController extends BaseController
{
    /**
     * 优惠卷管理列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search     = $request->all();
        $page       = request('page', 1);
        $limit      = request('limit', 20);
        
        return BusinessService::getLottery(Auth::guard('business')->id(), $search, $page, $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * 核销订单时,输入电子码
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VerifyTheOrderStore $request)
    {
        return BusinessService::verifyTheCoupon(Auth::guard('business')->id(), $request->code);
    }

    /**
     * 优惠卷详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page       = request('page', 1);
        $limit      = request('limit', 20);
        $search     = ['name'=>request('name')];
        
        return BusinessService::lotteryDetails(Auth::guard('business')->id(), $id, $search, $page, $limit);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 核销优惠卷时,输入电子码后,确认使用
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VerifyTheOrderUpdate $request, $id)
    {
        return BusinessService::verifyTheCoupon(Auth::guard('business')->id(), $request->code, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
