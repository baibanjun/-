<?php
namespace App\Services\Admin;

use App\Models\BusinessApply;
use App\Models\AdminSet;

class BusinessApplyService extends BaseService
{
    
    /**
     * 用户申请状态
     *
     * @param integer $uid
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getApplyStatus($uid)
    {
        $result = BusinessApply::where('uid', $uid)->first(['uid','status']);
        $result->attention = AdminSet::where('type_name', AdminSet::TYPE_NAME_BUSINESS_ENTER_ATTENTION)->first(['value'])->value;
        
        return self::returnCode('sys.success', $result);
    }
    
    /**
     * 创建一条申请
     *
     * @param   array $saveData
     * @return  array
     */
    static public function createApply($saveData)
    {
        //此用记有没有申请过,有不允许再申请
        $exists = BusinessApply::where('uid', $saveData['uid'])->exists();
        if ($exists){
            return self::returnCode('sys.businessApplyExist');
        }
        
        $result = BusinessApply::create($saveData);
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 通过或驳回申请
     *
     * @param integer $id
     *            申请id
     * @param integer $status
     *            状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateStatus($id, $status)
    {
        if (! in_array($status, [
            BusinessApply::STATUS_1,
            BusinessApply::STATUS_2
        ])) {
            return self::returnCode('sys.dataFali');
        }
        
        $businessApply = BusinessApply::find($id);
        
        if (! $businessApply) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        if ($businessApply->status != BusinessApply::STATUS_0) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $businessApply->status = $status;
        $result = $businessApply->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取商家申请列表
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            获取字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getBusinessApplys($search, $limit = 10)
    {
        $datas = BusinessApply::where(function ($query) use ($search) {
            if (isset($search['name']) && $search['name']) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
            if (isset($search['tel']) && $search['tel']) {
                $query->where('tel', 'like', '%' . $search['tel'] . '%');
            }
            if (isset($search['apply_start_time']) && $search['apply_start_time']) {
                $query->where('created_at', '>=', $search['apply_start_time']);
            }
            if (isset($search['apply_end_time']) && $search['apply_end_time']) {
                $query->where('created_at', '<=', $search['apply_end_time']);
            }
            if (isset($search['update_start_time']) && $search['update_start_time']) {
                $query->where('updated_at', '>=', $search['update_start_time']);
            }
            if (isset($search['update_end_time']) && $search['update_end_time']) {
                $query->where('updated_at', '<=', $search['update_end_time']);
            }
            if (isset($search['status']) && $search['status'] != 'all') {
                $query->where('status', $search['status']);
            }
            if (isset($search['search_status']) && $search['search_status'] == 'apply') {
                $query->where('status', BusinessApply::STATUS_0);
            } else {
                $query->whereIn('status', [
                    BusinessApply::STATUS_1,
                    BusinessApply::STATUS_2
                ]);
            }
        })->orderBy('id', 'DESC')->paginate($limit);
        
        return self::returnCode('sys.success', $datas);
    }
}