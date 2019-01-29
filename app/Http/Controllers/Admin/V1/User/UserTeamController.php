<?php
namespace App\Http\Controllers\Admin\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\UserService;

class UserTeamController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('nickname', 'name', 'mobile');
        $field = [
            'uid',
            'number_of_team_users',
            'number_of_satisfied_popler'
        ];
        $limit = $request->get('limit', 10);
        
        return UserService::getUserTeamsInfo($search, $field, $limit);
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
     * @param int $uid
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $uid)
    {
        $field = [
            'captain_uid',
            'team_member_uid',
            'amount_of_product_sold'
        ];
        $limit = $request->get('limit', 10);
        
        return UserService::getUserTeamMembersByUid($uid, $field, $limit);
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
