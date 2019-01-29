<?php

namespace App\Http\Controllers\Api\V1\My;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Api\My\TeamIndex;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TeamController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $url = config('console.join_team_url') . '?captain=' . Auth::id();
        $qrImg = QrCode::format('png')->margin(0)->size(130)->generate($url);
        
        return 'data:image/png' . ';base64,' . chunk_split(base64_encode($qrImg));
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
    public function store(TeamIndex $request)
    {
        $captain    = $request->captain;
        
        $result = UserService::joinTeam($captain, Auth::id());
        
        UserService::setLog('用户扫码加入团队', 0, $result);
        
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
