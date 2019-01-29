<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\UserService;
use Rap2hpoutre\FastExcel\FastExcel;

class UserTalentController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('nickname', 'name', 'mobile', 'status');
        $field = [
            'uid',
            'mobile',
            'name',
            'status'
        ];
        $limit = $request->get('limit', 10);
        
        $isExport = $request->get('is_export', 0);
        
        if ($isExport) {
            $datas = UserService::getUserTalentsInfo($search, $field, $limit, $isExport);
            
            $file = (new FastExcel($datas))->export('user_talent.xlsx');
            
            header("Content-Disposition:  attachment;  filename=user_talent.xlsx"); // 告诉浏览器通过附件形式来处理文件
            header('Content-Length: ' . filesize($file)); // 下载文件大小
            readfile($file); // 读取文件内容
            unlink($file); // 删除文件
        } else {
            return UserService::getUserTalentsInfo($search, $field, $limit, $isExport);
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
    public function update(Request $request, $uid)
    {
        return UserService::frozenOrUnfrozenUserTalent($uid, $request->status);
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
