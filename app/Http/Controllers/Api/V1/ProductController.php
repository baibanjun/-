<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\ProductService;
use App\Http\Requests\Api\ProductIndex;
use App\Http\Requests\Api\PosterIndex;

/**
 * 本地,周边,地方产品
 *
 * @author lilin
 *
 */
class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductIndex $request)
    {
        $type       = request('type', 1);
        $cityCode   = request('city', '510100000');
        $page       = request('page', 1);
        $limit      = request('limit', 20);
        $fields     = ['id','subtitle','is_countdown','time_limit','primary_distribution_id','pics','status','created_at','updated_at'];
        
        return ProductService::getList($type, $cityCode, $fields, $page, $limit);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ProductService::getInfo($id);
    }
    
    /**
     * 获取海报
     *
     * @param ProductIndex $request
     */
    public function getPoster(PosterIndex $request)
    {
        return ProductService::getPoster($request->id, $request->f, $request->s);
    }
}
