<?php
namespace App\Services\Admin;

use App\Models\UserAccountRecord;

class UserAccountService extends BaseService
{

    /**
     * 获取邀请关注列表信息
     * 
     * @param array $search
     *            搜索条件
     * @param array $field
     *            获取字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getInvterRecord($search, $field = ['*'], $limit = 10)
    {
        $records = UserAccountRecord::with([
            'user' => function ($query) {
                $query->select('id', 'nickname');
            },
            'objectUser' => function ($query) {
                $query->select('id', 'nickname');
            }
        ])->where(function ($query) use ($search) {
            // 开始时间搜索
            if (isset($search['start_time']) && $search['start_time']) {
                $query->where('created_at', '>=', $search['start_time']);
            }
            // 结束时间搜索
            if (isset($search['end_time']) && $search['end_time']) {
                $query->where('created_at', '<=', $search['end_time']);
            }
        })
            ->whereHas('user', function ($query) use ($search) {
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', 'like', '%'.$search['nickname'].'%');
            }
        })
            ->where('object_type', UserAccountRecord::OBJECT_TYPE_3)
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $records);
    }
}