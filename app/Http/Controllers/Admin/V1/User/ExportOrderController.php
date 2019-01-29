<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\OrderService;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportOrderController extends BaseController
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
        
        $isExport = 1;
        
        $orders = OrderService::getOrdersList($search, $field, $limit, $isExport);
        
        if (count($orders)>0)
        {
            if (count($orders) > 30000)
            {
                echo "<script>alert('导出数量太多了')</script>";
            }else{
                $file = (new FastExcel($orders))->export('order.xlsx');
                
                header("Content-Disposition:  attachment;  filename=order.xlsx"); // 告诉浏览器通过附件形式来处理文件
                header('Content-Length: ' . filesize($file)); // 下载文件大小
                readfile($file); // 读取文件内容
                unlink($file); // 删除文件
            }
        }else {
            echo "<script>alert('没有数据')</script>";
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
        //
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
