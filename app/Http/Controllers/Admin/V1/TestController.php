<?php
namespace App\Http\Controllers\Admin\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\OrderService;
use App\Services\Admin\LotteryDrawService;
use App\Http\Requests\Admin\LotteryDrawStore;
use App\Http\Requests\Admin\LotteryDrawUpdate;
use App\Http\Requests\Admin\LotteryDrawStatusUpdate;
use App\Services\Admin\BusinessApplyService;
use App\Services\Admin\LotteryUserService;

class TestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->only('nickname');
        $field = [
            'id',
            'nickname'
        ];
        $limit = $request->get('limit', 10);

        return LotteryUserService::getLotteryUser($search, $field, $limit);

        $search = $request->only('name', 'tel', 'apply_start_time', 'apply_end_time', 'update_start_time', 'update_end_time', 'status', 'search_status');
        $limit = $request->get('limit', 10);

        return BusinessApplyService::getBusinessApplys($search, $limit);

        $search = $request->only('title', 'business_name', 'lottery_type', 'status');

        $field = [
            'id',
            'title',
            'lottery_type',
            'business_id',
            'poster',
            'description',
            'business_introduce',
            'status',
            'surplus_number'
        ];

        $limit = $request->get('limit', 10);

        return LotteryDrawService::getLotteryDraws($search, $field, $limit);
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
    public function store(LotteryDrawStore $request)
    {
        $lotteryData = $request->only('title', 'lottery_type', 'business_id', 'poster', 'description', 'business_introduce');
        $drawData = $request->get('draw_data');

        return LotteryDrawService::createLotteryDraw($lotteryData, $drawData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $limit = $request->get('limit', 10);

        return LotteryUserService::lotteryUserDetail($id, $request->type, $limit);

        $field = [
            'id',
            'sn',
            'business_id',
            'money',
            'name',
            'tel'
        ];
        
        return OrderService::getOrderInfoByOrderId($id, $field);
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
        return BusinessApplyService::updateStatus($id, $request->status);
        //return LotteryDrawService::updateLotteryDrawStatus($id, $request->status);


        $lotteryData = $request->only('title', 'lottery_type', 'business_id', 'poster', 'description', 'business_introduce');
        $drawData = $request->get('draw_data');

        return LotteryDrawService::updateLotteryDraw($id, $lotteryData, $drawData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return LotteryDrawService::deleteLotteryDraw($id);
    }
}
