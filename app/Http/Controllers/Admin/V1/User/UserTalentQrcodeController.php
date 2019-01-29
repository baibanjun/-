<?php

namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserTalentQrcodeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = config('console.create_talent_url');
        $qrImg = QrCode::format('png')->margin(0)->size(130)->generate($url, public_path('img/qrcode.png'));
        
        $type = $request->get('type', '');
        
        if ($type == 'download')
        {
            header("Content-Disposition:  attachment;  filename=qrcode.png"); // 告诉浏览器通过附件形式来处理文件
            header('Content-Length: ' . filesize(public_path('img/qrcode.png'))); // 下载文件大小
            readfile(public_path('img/qrcode.png')); // 读取文件内容
        }else {
            return config('console.web_index').'img/qrcode.png';
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
