<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Services\OrderService;
use App\Http\Requests\Api\OrderStore;
use Illuminate\Support\Facades\Auth;

/**
 * 本地,周边,地方的订单处理
 *
 * @author lilin
 *
 */
class OrderController extends BaseController
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
    public function store(OrderStore $request)
    {
        $data = [
            'product_id'    => $request->product_id,
            'uid'           => Auth::id(),
            'standard_id'   => $request->standard_id,
            'quantity'      => $request->quantity,
            'name'          => $request->name,
            'tel'           => $request->tel,
            'area_code'     => $request->area_code,
            'address'       => $request->address,
            'remark'        => $request->remark,
            'f'             => request('f',0),
            's'             => request('s',0),
        ];
        
        return OrderService::createOrder($data);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
