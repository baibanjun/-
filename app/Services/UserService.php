<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserTalent;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Log;
use App\Models\UserTeam;
use App\Models\UserAccount;
use App\Models\UserTeamMember;

/**
 * 用户
 *
 * @author lilin 
 * wx(tel):13408099056 
 * qq:182436607
 *
 */
class UserService extends BaseService
{
    /**
     * 加入团队
     *
     * @param integer $captain 队长UID
     * @param integer $member  队员UID
     * @return array
     */
    static public function joinTeam($captain, $member)
    {
        //队长和队员不能是一人
        if ($captain == $member){
            return self::returnCode('sys.captainsAndPlayerNotAlike');
        }
        
        //队长达人状态,用户状态是否正常
        $captainStatus = self::determineIfTheUserTalentStatusIsNormal(null, $captain);
        if ($captainStatus['code'] != self::SUCCESS_CODE){
            return $captainStatus;
        }
        
        //队员达人状态,用户状态是否正常
        $memberStatus = self::determineIfTheUserTalentStatusIsNormal(null, $member);
        if ($memberStatus['code'] != self::SUCCESS_CODE){
            return $memberStatus;
        }
        
        //此队员有没有在其它团队
        $exist = UserTeamMember::where('team_member_uid', $member)->where('status', UserTeamMember::STATUS_NORMAL)->exists();
        if ($exist){
            return self::returnCode('sys.teamAlreadyExist');
        }
        
        //加入团队
        $join = UserTeamMember::create(['captain_uid'=>$captain,'team_member_uid'=>$member]);
        if ($join){
            UserTeam::where('uid',$captain)->increment('number_of_team_users');
        }
        
        return self::returnCode('sys.success');
    }
    
    /**
     * 创建达人
     *
     * @param   integer $uid
     * @param   string $name
     * @param   string $mobile
     * @return  array
     */
    static public function createTalent($uid, $name, $mobile)
    {
        // 判断用户是否存在
        $user = self::getUserInfo($uid, ['id','openid','status','role']);
        if ($user['code'] != self::SUCCESS_CODE){
            return $user;
        }
        
        // 判断用户状态
        $userStatus = self::determineIfTheUserStatusIsNormal($user['data']->status);
        if ($userStatus['code'] != self::SUCCESS_CODE){
            return $userStatus;
        }
        
        // 判断用户是否有达人角色
        $talent = self::getUserTalentInfo($uid, ['status']);
        if ($talent['code'] == self::SUCCESS_CODE){
            return self::returnCode('sys.rolesAlreadyExist');
        }
        
        // 判断用户是否关注公众号
        $Wx = new WeixinService();
        $wxUserInfo = $Wx->getUserInfo($user['data']->openid);

        if (! $wxUserInfo['subscribe']) {
            return self::returnCode('sys.dontAttention');
        }
        
        $saveData = [
            'uid' => $uid,
            'name' => $name,
            'mobile' => $mobile
        ];
        
        $create = UserTalent::create($saveData);
        //更新用户角色
        $updateRole = User::where('id',$uid)->update(['role'=>User::ROLE_TALENT]);
        
        //如果用户没有帐户,则开通一个
        $exist = UserAccount::where('uid',$uid)->exists();
        if (!$exist){
            UserAccount::create(['uid'=>$uid]);
        }
        
        //默认开通队长身份
        UserTeam::create(['uid'=>$uid]);
        
        
        return $create && $updateRole ? self::returnCode('sys.success') : self::returnCode('sys.fail');
    }
    
    /**
     * 判断达人状态是否正常
     *
     * @param   int $status
     * @param   int $uid
     * @return  array|mixed[]|\Illuminate\Foundation\Application[]|array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function determineIfTheUserTalentStatusIsNormal($status = NULL, $uid = 0)
    {
        if ($uid) {
            $talent = self::getUserTalentInfo($uid, ['uid','status']);
            if ($talent['code'] != self::SUCCESS_CODE) {
                return self::returnCode('sys.talentStatusIsNotNormal');
            }
            $talent = $talent['data'];
            
            //达人状态
            if ($talent->status != UserTalent::STATUS_1) {
                return self::returnCode('sys.talentStatusIsNotNormal');
            }
            
            //用户状态
            if ($talent->user->status != User::STATUS_NORMAL){
                return self::returnCode('sys.userStatusIsNotNormal');
            }
            
            return self::returnCode('sys.success');
            
        }else {
            return $status == UserTalent::STATUS_1 ? self::returnCode('sys.success') : self::returnCode('sys.talentStatusIsNotNormal');
        }

    }
    
    /**
     * 获取指定达人信息
     *
     * @param   int     $uid
     * @param   array   $fields
     * @return  array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserTalentInfo($uid, $fields = ['*'], $userInfoFields = ['id','status'])
    {
        $data = UserTalent::with(['user'=>function($query) use($userInfoFields){
            $query->select($userInfoFields);
        }])->find($uid, $fields);
        return !$data ? self::returnCode('sys.dataDoesNotExist') : self::returnCode('sys.success', $data);
    }
    
    /**
     * 判断用户状态是否正常
     *
     * @param   int $status
     * @param   int $uid
     * @return  array|mixed[]|\Illuminate\Foundation\Application[]|array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function determineIfTheUserStatusIsNormal($status = NULL, $uid = 0)
    {
        if ($uid){
            $user = self::getUserInfo($uid, ['status']);
            if ($user['code'] != self::SUCCESS_CODE){
                return $user;
            }
            $status = $user['data']->status;
        }
        
        return $status != User::STATUS_NORMAL ? self::returnCode('sys.statusIsNotNormal') : self::returnCode('sys.success');
    }
    
    /**
     * 根据多条件获取用户信息
     *
     * @param   array $conditions   [['id'=>1],['status'=>2]]
     * @param   array $fields
     * @return  array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserInfoByConditions($conditions, $fields = ['*'])
    {
        $data = User::where(function ($query) use ($conditions) {
            foreach ($conditions as $condition) {
                $query->where(key($condition), value($condition));
            }
        })->first($fields);
        
        return !$data ? self::returnCode('sys.dataDoesNotExist') : self::returnCode('sys.success', $data);
    }
    
    /**
     * 获取指定用户信息
     *
     * @param   int     $uid
     * @param   array   $fields
     * @return  array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function getUserInfo($uid, $fields = ['*'])
    {
        $data = User::find($uid, $fields);
        return !$data ? self::returnCode('sys.dataDoesNotExist') : self::returnCode('sys.success', $data);
    }
    
    /**
     * 登陆信息 合并 加密
     *
     * @param User $user
     * @return string
     */
    static public function passwordEncrypt(User $user, $time)
    {
        $salted = $user->mobile . $user->password . $user->salt . config('console.reg_pwd_sn') . $time;
        return hash('sha256', $salted);
    }
    
    /**
     * 通过微信创建一个用户
     *
     * @param   string $code        微信的CODE
     * @param   integer $platform   1:wx
     * @return  array
     */
    static public function wxCreateUser($code, $platform, $ip)
    {
        // 通过code换取网页授权access_token
        $getAuthAccessCode = WxWebAuthService::getAuthAccessCode($code);
        if ($getAuthAccessCode['code'] != self::SUCCESS_CODE){
            return $getAuthAccessCode;
        }
        $getAuthAccessCode = $getAuthAccessCode['data'];
        
        $wxUserInfo = WxWebAuthService::getUserInfo($getAuthAccessCode['access_token'], $getAuthAccessCode['openid']);
        
        if ($wxUserInfo['code'] != self::SUCCESS_CODE){
            return $wxUserInfo;
        }
        
        $wxUserInfo['data']['salt'] = uniqid();
        
        $addUser = User::updateOrCreate(['openid' => $getAuthAccessCode['openid']], $wxUserInfo['data']);

        //判断用户状态
        if ($addUser->status != User::STATUS_NORMAL){
            Log::info('微信登陆创建或者更新用户,发现用户状态不正常:'.json_encode($addUser));
            return self::returnCode('sys.userStatusIsNotNormal');
        }
        
        //生成一个token
        $log = [
            'uid'       => $addUser->id,
            'platform'  => $platform,
            'login_ip'  => $ip
        ];
        $loginLog = self::createUserLoginLog($addUser, $log);
        
        $addUser->access_token = $getAuthAccessCode['access_token'];
        $addUser->token        = $loginLog['token'];
        
        Log::info('微信登陆创建或者更新用户:'.json_encode($addUser));
        
        return $addUser ? self::returnCode('sys.success', $addUser) : self::returnCode('sys.fail');
    }
    
    /**
     * 设置一个登陆
     *
     * @param User $user
     * @param array $log['uid','platform','login_ip']
     * @return array
     */
    static public function createUserLoginLog(User $user, $log)
    {
        //删除其它token
        UserLoginLog::where('uid', $user->uid)->delete();
        
        //重新生成
        $log['token'] = self::_token($user);
        
        return UserLoginLog::create($log);
    }
    
    /**
     * 生成一个token
     *
     * @return string
     */
    static private function _token(User $user)
    {
        $salted = $user->openid . $user->salt . config('console.reg_pwd_sn') . time();
        return hash('sha256', $salted);
    }
    
    /**
     * 验证用户token是否正确
     *
     * @param string $field
     * @param string $value
     * @return array
     */
    static public function getUserLoginLog($token, $field = ['*'])
    {
        $loginLog = UserLoginLog::with(['user'=>function($query){
            $query->select(['id','openid','status']);
        }])->where('token', $token)->first($field);
        
        if ($loginLog && $loginLog['user'] && $loginLog->user->status == User::STATUS_NORMAL) {
            return self::returnCode('sys.success', $loginLog);
        } else {
            return self::returnCode('sys.authenticationFailed');
        }
    }
}

