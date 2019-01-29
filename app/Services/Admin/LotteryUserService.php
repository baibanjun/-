<?php
namespace App\Services\Admin;

use App\Models\User;
use App\Models\LotteryUser;

class LotteryUserService extends BaseService
{

    /**
     * 获取优惠券详情列表
     *
     * @param integer $uid
     *            用户id
     * @param integer $type
     *            优惠券类型
     * @param integer $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function lotteryUserDetail($uid, $type, $limit)
    {
        $datas = LotteryUser::with([
            'user' => function ($query) {
                $query->select('id', 'nickname');
            },
            'userFrom' => function ($query) {
                $query->select('id', 'nickname');
            },
            'prize' => function ($query) {
                $query->select('id', 'name');
            },
            'lotteryDraw' => function ($query) {
                $query->select('id', 'title');
            }
        ])->where(function ($query) use ($uid, $type) {
            switch ($type) {
                case 'not_use': // 未使用优惠券
                    $query->where('uid', $uid)
                        ->where('status', LotteryUser::STATUS_0)
                        ->where('end_date', '>=', date('Y-m-d'));
                    break;
                case 'has_use': // 已使用优惠券
                    $query->where('uid', $uid)
                        ->where('status', LotteryUser::STATUS_1);
                    break;
                case 'overdue': // 已过期优惠
                    $query->where('uid', $uid)
                        ->where('status', LotteryUser::STATUS_0)
                        ->where('end_date', '<', date('Y-m-d'));
                    break;
                case 'has_send': // 已转赠优惠
                    $query->where('from_uid', $uid);
                    break;
                default:
                    $query->where('id', 0);
                    break;
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($limit);
        
        return self::returnCode('sys.success', $datas);
    }

    /**
     * 获取用户优惠券数量
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            搜索字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getLotteryUser($search, $field = ['*'], $limit = 10)
    {
        $datas = User::where(function ($query) use ($search) {
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', 'like', '%' . $search['nickname'] . '%');
            }
        })
        
//         ->whereHas('lotteryUser', function ($query) {
//             $query->where('id', '>', 0);
//         })
//             ->orWhereHas('lotteryUserFrom', function ($query) {
//             $query->where('id', '>', 0);
//         })
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        if (! $datas->isEmpty()) {
            $datas->each(function ($item) {
                // 未使用优惠券数量
                $item->not_use_num = LotteryUser::where('uid', $item->id)->where('status', LotteryUser::STATUS_0)
                    ->where('end_date', '>=', date('Y-m-d'))->where('is_share',LotteryUser::IS_SHARE_1)
                    ->count();
                // 已使用优惠券数量
                $item->has_use_num = LotteryUser::where('uid', $item->id)->where('status', LotteryUser::STATUS_1)
                    ->count();
                // 已过期优惠券数量
                $item->overdue_num = LotteryUser::where('uid', $item->id)->where('status', LotteryUser::STATUS_0)
                ->where('end_date', '<', date('Y-m-d'))->where('is_share',LotteryUser::IS_SHARE_1)
                    ->count();
                // 已转赠优惠券数量
                $item->has_send_num = LotteryUser::where('from_uid', $item->id)->count();
            });
        }
        return self::returnCode('sys.success', $datas);
    }
}