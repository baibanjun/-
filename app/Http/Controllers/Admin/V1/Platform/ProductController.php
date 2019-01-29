<?php
namespace App\Http\Controllers\Admin\V1\Platform;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\V1\BaseController;
use App\Services\Admin\ProductService;
use App\Http\Requests\Api\Admin\ProductCreateOrUpdate;

class ProductController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('name', 'bus_name', 'city_name', 'type', 'search_status');
        $field = [
            'id',
            'name',
            'type',
            'business_id',
            'city_code',
            'status',
            'send_sms_or_not',
            'booking_information',
            'primary_distribution_id',
            'secondary_distribution_id',
            'team_distribution_id',
            'poster',
            'pics',
            'content',
            'subtitle',
            'is_countdown',
            'time_limit',
            'updated_at'
        ];
        $limit = $request->get('limit', 10);
        
        return ProductService::getProductList($search, $field, $limit);
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
    public function store(ProductCreateOrUpdate $request)
    {
        $productData = $request->only('name', 'type', 'business_id', 'city_code', 'send_sms_or_not', 'booking_information', 'poster', 'pics', 'content', 'subtitle', 'is_countdown', 'time_limit');
        
        $distributionData = $request->get('distribution');
        
        $standardData = $request->get('standard');
        
        return ProductService::createProduct($productData, $distributionData, $standardData);
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
    public function update(ProductCreateOrUpdate $request, $id)
    {
        $productData = $request->only('name', 'type', 'business_id', 'city_code', 'send_sms_or_not', 'booking_information', 'poster', 'pics', 'content', 'subtitle', 'is_countdown', 'time_limit');
        
        $distributionData = $request->get('distribution');
        
        $standardData = $request->get('standard');
        
        return ProductService::updateProduct($id, $productData, $distributionData, $standardData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ProductService::deleteProduct($id);
    }
}
