<?php

namespace App\Http\Controllers\Api\V1\My;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Services\UserService;
use App\Http\Requests\Api\My\TalentStore;
use Illuminate\Support\Facades\Auth;

/**
 * 申请成为达人 达人信息
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class TalentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fields = ['uid','name','mobile','team_pic'];
        $userInfoFields = ['id','nickname','headimgurl'];
        
        return UserService::getUserTalentInfo(Auth::id(), $fields, $userInfoFields);
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
    public function store(TalentStore $request)
    {
        $startTime = microtime();
        $result = UserService::createTalent(Auth::id(), $request->name, $request->mobile);
        UserService::setLog('申请成为达人,返回结果', $startTime, $result);
        return $result;
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
