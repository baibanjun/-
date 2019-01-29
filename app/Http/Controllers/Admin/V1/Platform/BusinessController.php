<?php
namespace App\Http\Controllers\Admin\V1\Platform;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\BusinessService;
use App\Http\Requests\Api\Admin\BusinessCreate;
use App\Http\Requests\Api\Admin\BusinessUpdate;

class BusinessController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('start_time', 'end_time', 'name');
        $field = [
            'id',
            'name',
            'tel',
            'address',
            'lng',
            'lat',
            'username',
            'mobile',
            'status'
        ];
        $limit = $request->get('limit', 1);
        
        return BusinessService::getBusinessList($search, $field, $limit);
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
    public function store(BusinessCreate $request)
    {
        $data = $request->only('name', 'tel', 'address', 'lng', 'lat', 'username', 'mobile', 'password', 'salt');
        
        return BusinessService::createBusiness($data);
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
    public function update(BusinessUpdate $request, $id)
    {
        if ($request->update_type == 'status')
        {
            $data = $request->only('status');
        }else{
            $data = $request->only('tel', 'address', 'lng', 'lat', 'username', 'mobile');
        }
        
        return BusinessService::updateBusiness($id, $data);
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
