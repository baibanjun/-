<?php
namespace App\Http\Controllers\Api\V1\Lottery;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ChwlFactoty;

class MyCouponsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = request('type', 1);
        return ChwlFactoty::Lottery()->getUserCoupons($type);
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
        $type = request('type', 1);
        $couponId = $request->coupon_id;
        $fromUid = $request->from_uid;
        
        if ($type == 1) { // 转赠
            $result = ChwlFactoty::Lottery()->giveCoupon($couponId);
        } elseif ($type == 2) { // 接收转赠
            $result = ChwlFactoty::Lottery()->getGiveCoupon($couponId, $fromUid);
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fields = [
            'id',
            'uid',
            'from_uid',
            'lottery_draws_id',
            'prize_id',
            'draw_type',
            'code'
        ];
        return ChwlFactoty::Lottery()->getCouponDetails($id, $fields);
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
