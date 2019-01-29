<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\UserCashService;

class UserCashController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('start_created_at', 'end_created_at', 'nickname', 'mobile');
        
        $field = [
            'id',
            'uid',
            'money',
            'balance',
            'created_at',
            'updated_at',
            'status'
        ];
        $limit = $request->get('limit', 10);
        
        $type = $request->get('type', '');
        
        return UserCashService::getUserCashList($search, $type, $field, $limit);
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
    public function show(Request $request, $uid)
    {
        $field = [
            'id',
            'uid',
            'money',
            'object_type',
            'object_id',
            'created_at'
        ];
        $limit = $request->get('limit', 1);
        
        return UserCashService::getUserAccountRecords($uid, $field, $limit);
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
        return UserCashService::updateUserCash($id, $request->status);
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
