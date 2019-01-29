<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportDistributionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('start_pay_time','end_pay_time','sn');
        $field = [
            'id',
            'sn',
            'uid',
            'money',
            'pay_time'
        ];
        $limit = $request->get('limit', 10);
        
        $isexport = 1;
        
        $orders = OrderService::getOrderDistributions($search, $field, $limit, $isexport);
        
        if (count($orders)>0)
        {
            if (count($orders) > 30000)
            {
                echo "<script>alert('导出数量太多了')</script>";
            }else{
                $file = (new FastExcel($orders))->export('order_distribution.xlsx');
                
                header("Content-Disposition:  attachment;  filename=order_distribution.xlsx"); // 告诉浏览器通过附件形式来处理文件
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
