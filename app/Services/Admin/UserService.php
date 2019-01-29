<?php
namespace App\Services\Admin;

use App\Models\User;
use App\Models\UserTalent;
use App\Models\UserTeam;
use App\Models\UserTeamMember;

class UserService extends BaseService
{

    /**
     * 获取团队成员
     *
     * @param integer $uid
     *            队长uid
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserTeamMembersByUid($uid, $field = ['*'], $limit = 10)
    {
        $userTeamMembers = UserTeamMember::with([
            'memberUser' => function ($query) {
                $query->select('id', 'nickname');
            }
        ])->where('captain_uid', $uid)
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $userTeamMembers);
    }

    /**
     * 获取团队信息
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserTeamsInfo($search, $field = ['*'], $limit = 10)
    {
        $userTalents = UserTeam::with([
            'user' => function ($query) {
                $query->select('id', 'nickname');
            },
            'userTalent' => function ($query) {
                $query->select('uid', 'name', 'mobile');
            },
            'account' => function ($query) {
                $query->select('uid', 'team_distribution_money');
            }
        ])->whereHas('user', function ($query) use ($search) {
            // 昵称搜索
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', $search['nickname']);
            }
        })
            ->whereHas('userTalent', function ($query) use ($search) {
            // 昵称搜索
            if (isset($search['mobile']) && $search['mobile']) {
                $query->where('mobile', $search['mobile']);
            }
            // 姓名搜索
            if (isset($search['name']) && $search['name']) {
                $query->where('name', $search['name']);
            }
        })
            ->orderBy('uid', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $userTalents);
    }

    /**
     * 冻结解冻达人用户
     *
     * @param integer $uid
     *            用户id
     * @param integer $status
     *            用户状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function frozenOrUnfrozenUserTalent($uid, $status)
    {
        if (! in_array($status, [
            UserTalent::STATUS_1,
            UserTalent::STATUS_2
        ])) {
            return self::returnCode('sys.dataFali');
        }
        
        $userTalent = UserTalent::find($uid);
        
        if (! $userTalent) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        if ($userTalent->status == $status) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $userTalent->status = $status;
        $result = $userTalent->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取达人用户账号
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserTalentsInfo($search, $field = ['*'], $limit = 10, $isExport = 0)
    {
        $userTalents = UserTalent::with([
            'account' => function ($query) {
                $query->select('uid', 'primary_distribution_money', 'secondary_distribution_money', 'team_distribution_money', 'recommended_user_money', 'withdraw_money', 'balance');
            },
            'user' => function ($query) {
                $query->select('id', 'nickname');
            }
        ])->whereHas('user', function ($query) use ($search) {
            // 昵称搜索
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', $search['nickname']);
            }
        })
            ->where(function ($query) use ($search) {
            // 姓名搜索
            if (isset($search['name'])) {
                $query->where('name', $search['name']);
            }
            // 手机号搜索
            if (isset($search['mobile'])) {
                $query->where('mobile', $search['mobile']);
            }
            // 状态搜索
            if (isset($search['status']) && $search['status'] != 'all') {
                $query->where('status', $search['status']);
            }
        })
            ->orderBy('uid', 'DESC');
        
        if ($isExport) {
            $userTalents = $userTalents->get($field);
            
            if (count($userTalents) > 30000) {
                return self::returnCode('admin.export_count_false');
            }
            
            return self::getExcelArrayData($userTalents);
        } else {
            $userTalents = $userTalents->paginate($limit, $field);
            
            return self::returnCode('sys.success', $userTalents);
        }
    }

    /**
     * 获取EXCEL数据
     *
     * @param array $datas
     * @return \Illuminate\Support\Collection
     */
    static protected function getExcelArrayData($datas)
    {
        if ($datas->isEmpty()) {
            return collect([]);
        }
        
        $orderData = [];
        
        foreach ($datas as $d) {
            $orderData[] = [
                '昵称' => $d->user->nickname,
                '姓名' => $d->name,
                '手机号' => $d->mobile,
                '一级分销金额' => $d->account->primary_distribution_money,
                '二级分销金额' => $d->account->secondary_distribution_money,
                '团队分销金额' => $d->account->team_distribution_money,
                '已提现总金额' => $d->account->withdraw_money,
                '账户余额' => $d->account->balance,
                '达人资格状态' => ($d->status == 1) ? "正常" : "冻结"
            ];
        }
        
        return collect($orderData);
    }

    /**
     * 冻结解冻用户
     *
     * @param integer $id
     *            用户id
     * @param integer $status
     *            用户状态
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function frozenOrUnfrozenUser($id, $status)
    {
        if (! in_array($status, [
            User::STATUS_FREEZE,
            User::STATUS_NORMAL
        ])) {
            return self::returnCode('sys.dataFali');
        }
        
        $user = User::find($id);
        
        if (! $user) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        if ($user->status == $status) {
            return self::returnCode('sys.statusIsNotNormal');
        }
        
        $user->status = $status;
        $result = $user->save();
        
        return $result ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }

    /**
     * 获取用户账号
     *
     * @param array $search
     *            搜索条件
     * @param array $field
     *            查询字段
     * @param number $limit
     *            每页显示条数
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUsersInfo($search, $field = ['*'], $limit = 10)
    {
        $users = User::with('statistic')->where(function ($query) use ($search) {
            // 昵称搜索
            if (isset($search['nickname']) && $search['nickname']) {
                $query->where('nickname', $search['nickname']);
            }
            // 角色搜索
            if (isset($search['role']) && $search['status'] != 'all') {
                $query->where('role', $search['role']);
            }
            // 状态搜索
            if (isset($search['status']) && $search['status'] != 'all') {
                $query->where('status', $search['status']);
            }
        })
            ->orderBy('id', 'DESC')
            ->paginate($limit, $field);
        
        return self::returnCode('sys.success', $users);
    }
}