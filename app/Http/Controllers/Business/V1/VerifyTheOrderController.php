<?php

namespace App\Http\Controllers\Business\V1;

use App\Services\BusinessService;
use App\Http\Requests\Business\VerifyTheOrderStore;
use App\Http\Requests\Business\VerifyTheOrderUpdate;
use App\Http\Requests\Business\VerifyTheOrderIndex;
use Illuminate\Support\Facades\Auth;

/**
 * 核销记录,未核销的订单,地方订单
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class VerifyTheOrderController extends BaseController
{
    /**
     * 获取核销记录,未核销的订单,地方订单列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VerifyTheOrderIndex $request)
    {
        $search     = $request->all();
        $type       = request('type', 1);
        $page       = request('page', 1);
        $limit      = request('limit', 20);
        
        return BusinessService::getOrders(Auth::guard('business')->id(), $search, $type, $page, $limit);
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
        return BusinessService::verifyTheOrder(Auth::guard('business')->id(), $request->code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * 核销订单时,输入电子码后,确认使用
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VerifyTheOrderUpdate $request, $id)
    {
        return BusinessService::verifyTheOrder(Auth::guard('business')->id(), $request->code, true);
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
