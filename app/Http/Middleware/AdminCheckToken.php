<?php
namespace App\Http\Middleware;

use Closure;
use App\Services\Admin\AdminUserService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Events\AdminDatabase;

class AdminCheckToken
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $startTime = microtime();
        
        // 验证token用户
        $token = $request->header('token')?$request->header('token'):$request->get('token');
        
        $loginResult = AdminUserService::getLoginUid($token);
        
        if ($loginResult['code'] != '0000') {
            UserService::setLog('token认证请求失败', $startTime, [
                'token' => $token,
                $request->all()
            ]);
            return response()->json($loginResult);
        }
        
        Auth::guard('admin')->loginUsingId($loginResult['data']->uid);
        
        event(new AdminDatabase());
        
        return $next($request);
    }
}
