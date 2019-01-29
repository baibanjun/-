<?php

namespace App\Http\Controllers\Api\V1\My;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\OrderService;
use App\Models\Order;
use App\Http\Requests\Api\My\OrdersIndex;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * 全部订单,已支付,已预约,已完成,订单详情
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class OrdersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrdersIndex $request)
    {
        $type       = request('type', Order::STATUS_PAID);
        $page       = request('page', 1);
        $limit      = request('limit', 20);
        $fields     = ['id','sn','code','name','tel','product_id','money','status','type','remark','created_at'];
        
        return OrderService::getMyOrders(Auth::id(), $type, $fields, $page, $limit);
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
        $url = config('console.business_verify_order_url') . '?code=' . $request->code;
        $qrImg = QrCode::format('png')->margin(0)->size(130)->generate($url);
        
        return 'data:image/png' . ';base64,' . chunk_split(base64_encode($qrImg));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fields     = ['id','code','name','tel','product_id','money','status','sn','remark','type','area_code','address','express_company','express_number'];
        
        return OrderService::getMyOrderInfo(Auth::id(), $id, $fields);
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
        return OrderService::deliverGoods(Auth::id(), $id);
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
