<?php
namespace App\Services\Admin;

use App\Models\Business;
use App\Models\Order;

class BusinessService extends BaseService
{

    /**
     * 修改商家信息
     *
     * @param integer $id
     *            商家id
     * @param array $data
     *            商家用户数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateBusiness($id, $data)
    {
        // 修改商家状态
        if (isset($data['status']) && $data['status']) {
            if ($data['status'] == Business::STATUS_FREEZE) {
                // 商家有已支付未完成的订单时不能冻结商家
                $order = Order::where('business_id', $id)->whereIn('status', [
                    Order::STATUS_PAID,
                    Order::STATUS_RESERVED,
                    Order::STATUS_SHIPPED
                ])->first();
                
                if ($order) {
                    return self::returnCode('admin.businessFreezeOrderFail');
                }
            }
        }else {
            // 判断账号是否存在
            $business = Business::where(function ($query) use ($data) {
                $query->where('username', $data['username']);
                $query->orWhere('mobile', $data['mobile']);
            })->where('id', '<>', $id)->first();
            
            if ($business) {
                return self::returnCode('admin.businessUsernameOrMobileExist');
            }
        }
        
        $result = Business::where('id', $id)->update($data);
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 新增商家
     *
     * @param array $data
     *            商家用户数据
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function createBusiness($data)
    {
        // 判断商家账号是否存在
        $business = Business::where('username', $data['username'])->orWhere('mobile', $data['mobile'])->first();
        
        if ($business) {
            return self::returnCode('admin.businessUsernameOrMobileExist');
        }
        
        $result = Business::create($data);
        
        return $result ? self::returnCode('sys.success', $result) : self::returnCode('sys.fail');
    }

    /**
     * 获取商家列表
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            显示字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getBusinessList($search, $field = ['*'], $limit = 10)
    {
        $businesses = Business::where(function ($query) use ($search) {
            // 注册开始时间搜索
            if (isset($search['start_time']) && $search['start_time']) {
                $query->where('created_at', '>=', $search['start_time']);
            }
            // 注册结束时间搜索
            if (isset($search['end_time']) && $search['end_time']) {
                $query->where('created_at', '<=', $search['end_time']);
            }
            // 商家名称搜索
            if (isset($search['name']) && $search['name']) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
        })->orderBy('id', 'DESC')->paginate($limit, $field);
        
        return self::returnCode('sys.success', $businesses);
    }

    /**
     * 获取商家信息
     *
     * @param array $field
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getBusinessSelect($field = ['*'])
    {
        $datas = Business::where('status', Business::STATUS_NORMAL)->get($field);
        
        return self::returnCode('sys.success', $datas);
    }
}