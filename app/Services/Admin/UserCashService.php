<?php
namespace App\Services\Admin;

use App\Models\UserCash;
use App\Models\UserAccountRecord;
use App\Services\AccountService;
use App\Models\UserAccount;

class UserCashService extends BaseService
{

    /**
     * 提现成功和驳回操作
     *
     * @param integer $id
     *            提现id
     * @param integer $status
     *            操作状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function updateUserCash($id, $status)
    {
        if (! in_array($status, [
            UserCash::STATUS_2,
            UserCash::STATUS_3
        ])) {
            return self::returnCode('admin.paramFail');
        }
        
        $userCash = UserCash::find($id);
        
        if (! $userCash) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 判断状态
        if ($userCash->status != UserCash::STATUS_1) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $userAccount = UserAccount::find($userCash->uid);
        
        if (!$userAccount)
        {
            return self::returnCode('sys.userAccountNotExist');
        }
        
        // 已驳回
        if ($status == UserCash::STATUS_3) {
            $result = AccountService::addMoney($userCash->uid, -$userCash->money, UserAccountRecord::OBJECT_TYPE_5, $userAccount->balance,$userCash->id);
            
            if ($result['code'] != self::SUCCESS_CODE) {
                return $result;
            }
        }
        
        //已打款
        if ($status == UserCash::STATUS_2)
        {
            $result = UserAccount::where('uid', $userCash->uid)->increment('withdraw_money', -$userCash->money);
            
            if (!$result)
            {
                return self::returnCode('sys.fail');
            }
        }
        
        $userCash->status = $status;
        $result = $userCash->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取用户账户
     *
     * @param integer $uid
     *            用户uid
     * @param array $field
     *            获取字段
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserAccountRecords($uid, $field = ['*'], $limit = 10)
    {
        $records = UserAccountRecord::where('uid', $uid)->orderBy('id', 'DESC')->paginate($limit, $field);
        return self::returnCode('sys.success', $records);
    }

    /**
     * 获取用户提现订单列表
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserCashList($search, $type = 'success', $field = ['*'], $limit = 10)
    {
        $userCashes = UserCash::where(function ($query) use ($search, $type) {
            // 按申请时间搜索
            if (isset($search['start_created_at']) && $search['start_created_at']) {
                $query->where('created_at', '>=', $search['start_created_at']);
            }
            // 按申请时间搜索
            if (isset($search['end_created_at']) && $search['end_created_at']) {
                $query->where('created_at', '<=', $search['end_created_at']);
            }
            
            if ($type == 'success') {
                $query->whereIN('status', [
                    UserCash::STATUS_2,
                    UserCash::STATUS_3
                ]);
            } else {
                $query->where('status', UserCash::STATUS_1);
            }
        })->with([
            'user' => function ($query) {
                $query->select('id', 'nickname');
            },
            'userTalent' => function ($query) {
                $query->select('uid', 'mobile', 'name');
            }
        ])
            ->whereHas('user', function ($query) use ($search) {
            // 按昵称搜索
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', $search['nickname']);
            }
        })
            ->whereHas('userTalent', function ($query) use ($search) {
            // 按手机号搜索
            if (isset($search['mobile']) && $search['mobile']) {
                $query->where('mobile', $search['mobile']);
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $userCashes);
    }
}