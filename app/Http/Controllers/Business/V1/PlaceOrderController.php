<?php

namespace App\Http\Controllers\Business\V1;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\BusinessService;
use App\Http\Requests\Business\PlaceOrderUpdate;
use Illuminate\Support\Facades\Auth;

class PlaceOrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fields = ['id','uid','business_id','area_code','sn','address','quantity','money','name','tel','pay_time','product_id','standard_id','remark','express_company','express_number','status'];
        return OrderService::getBusinessOrderInfo(Auth::guard('business')->id(), $id, 3, $fields);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlaceOrderUpdate $request, $id)
    {
        return BusinessService::placeDeliverGoods(Auth::guard('business')->id(), $id, $request->express_company, $request->express_number);
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
