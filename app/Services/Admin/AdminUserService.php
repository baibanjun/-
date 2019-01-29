<?php
namespace App\Services\Admin;

use App\Models\AdminLoginLog;
use App\Models\AdminUser;

class AdminUserService extends BaseService
{

    /**
     * 注册后台用户
     *
     * @param integer $data
     *            用户数据
     * @param number $adminUid
     * @return array|array[]|mixed[]|\Illuminate\Foundation\Application[]
     */
    static public function registerUser($data, $adminUid)
    {
        $adminUser = AdminUser::find($adminUid, ['id', 'powers']);
        
        if (!in_array('register', $adminUser->powers))
        {
            return self::returnCode('admin.hasNoPowerRegister');
        }
        
        if (! isset($data['mobile']) || ! isset($data['realname']) || ! isset($data['password']) || ! $data['mobile'] || ! $data['realname'] || ! $data['password']) {
            return self::returnCode('admin.paramFail');
        }
        
        $user = AdminUser::where('mobile', $data['mobile'])->first();
        
        if ($user) {
            return self::returnCode('sys.dataDoesExist');
        }
        
        $userData['mobile'] = $data['mobile'];
        $userData['realname'] = $data['realname'];
        $userData['powers'] = [];
        $userData['salt'] = uniqid();
        $userData['password'] = self::makePwd($data['password'], $userData['salt']);
        
        $result = AdminUser::create($userData);
        
        if (! $result) {
            return self::returnCode('sys.fail');
        }
        
        return self::returnCode('sys.success', $result);
    }

    /**
     * 制作密码
     *
     * @author lilin
     * @param string $pwd
     * @param string $salt
     * @return string
     */
    static public function makePwd($pwd, $salt)
    {
        return strtoupper(md5($pwd . $salt));
    }

    /**
     * 用户登录
     *
     * @param array $login
     * @param array $log
     * @param integer $platform
     */
    static public function loginUser($login, $log, $platform = 1)
    {
        // 对应帐号是否存在
        $user = AdminUser::where('mobile', $login['mobile'])->first();
        
        if (! $user) {
            return self::returnCode('sys.dataDoesNotExist');
        }
        
        // 检查密码错误次数和时间
        $logError = self::_getLoginError($user);
        
        if (! $logError) {
            return self::returnCode('admin.loginErrorNum');
        }
        
        // 检查密码是否正确
        $makePw = self::makePwd($login['password'], $user->salt);
        
        if ($user->password != $makePw) {
            // 记录密码出错信息
            self::_setLoginError($user);
            return self::returnCode('admin.passwordMistake');
        }
        
        $log['uid'] = $user->id;
        $log['platform'] = $platform;
        $log['token'] = self::passwordEncrypt($user, date('Y-m-d H:i:s'));
        
        $result = AdminLoginLog::create($log);
        
        if (! $result) {
            return self::returnCode('admin.loginFail');
        }
        
        $user->err_num = 0;
        $user->err_time = null;
        $user->save();
        
        return self::returnCode('sys.success', $result);
    }

    /**
     * 登陆次数是否达到条件
     *
     * @param AdminUser $user
     * @return boolean
     */
    static private function _getLoginError($user)
    {
        $sysLoginErrorNum = config('admin.login_error_num');
        $sysLoginErrorNextTime = config('admin.login_error_next_time');
        
        $diffTime = time() - strtotime($user->err_time);
        
        if ($user->err_num >= $sysLoginErrorNum && $diffTime < $sysLoginErrorNextTime) {
            return FALSE;
        } elseif ($diffTime > $sysLoginErrorNextTime) {
            $user->err_num = 0;
            $user->err_time = null;
            $user->save();
            return TRUE;
        } else {
            return TRUE;
        }
    }

    /**
     * 用户登陆错误记录
     *
     * @param AdminUser $user
     */
    static private function _setLoginError($user)
    {
        $user->err_num += 1;
        $user->err_time = date('Y-m-d H:i:s');
        $user->save();
    }

    /**
     * 登陆信息 合并 加密
     *
     * @param object $user
     *            用户模型
     * @return string
     */
    static public function passwordEncrypt($user, $time)
    {
        $salted = $user->mobile . $user->password . $user->salt . $time;
        
        return hash('sha256', $salted);
    }

    /**
     * 获取登录用户ID
     *
     * @param string $token
     *            登录用户TOKEN
     * @param integer $platform
     *            平台类型
     */
    static public function getLoginUid($token, $platform = 1)
    {
        $result = AdminLoginLog::where('token', $token)->where('platform', $platform)->first([
            'uid'
        ]);
        
        if (! $result) {
            return self::returnCode('admin.tokenFail');
        }
        
        return self::returnCode('sys.success', $result);
    }

    /**
     * 获取登录用户信息
     *
     * @param string $token
     *            登录用户TOKEN
     * @param integer $platform
     *            平台类型
     */
    static public function getAdminInfo($uid)
    {
        $result = AdminUser::find($uid, [
            'id',
            'mobile',
            'realname',
            'power'
        ]);
        
        return $result ? self::returnCode('sys.success', $result) : self::returnCode('sys.dataDoesNotExist');
    }
}