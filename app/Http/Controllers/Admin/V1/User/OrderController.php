<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\OrderService;
use Rap2hpoutre\FastExcel\FastExcel;

class OrderController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $field = [
            'id',
            'sn',
            'product_id',
            'business_id',
            'uid',
            'quantity',
            'money',
            'type',
            'status',
            'created_at',
            'complete_time',
            'remark',
            'name',
            'tel',
            'express_company',
            'express_number',
            'area_code',
            'address'
        ];
        $search = $request->only('start_created_at', 'end_created_at', 'sn', 'nickname', 'type', 'status');
        
        $isExport = $request->get('is_export', 0);
        
        if ($isExport) {
            $orders = OrderService::getOrdersList($search, $field, $limit, $isExport);
            
            $file = (new FastExcel($orders))->export('order.xlsx');
            
            header("Content-Disposition:  attachment;  filename=order.xlsx"); // 告诉浏览器通过附件形式来处理文件
            header('Content-Length: ' . filesize($file)); // 下载文件大小
            readfile($file); // 读取文件内容
            unlink($file); // 删除文件
        } else {
            return OrderService::getOrdersList($search, $field, $limit);
        }
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $field = [
            'id',
            'sn',
            'business_id',
            'money',
            'name',
            'tel',
        ];
        
        return OrderService::getOrderInfoByOrderId($id, $field);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
