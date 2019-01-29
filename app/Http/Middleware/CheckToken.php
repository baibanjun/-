<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $startTime = microtime();
//         UserService::setLog('token认证中间件开始');
        
        $token         = $request->header('token');
        $loginLog       = UserService::getUserLoginLog($token,['uid']);
        if ($loginLog['code'] != '0000'){
            UserService::setLog('token认证请求失败',$startTime,['token'=>$token,'ip'=>$request->ip(),$request->all()]);
            return response()->json($loginLog);
        }

        Auth::loginUsingId($loginLog['data']->user->id);
        
        $params = [
            'uid'       => $loginLog['data']->user->id,
            'openid'    => $loginLog['data']->user->openid
        ];
        $request->attributes->add($params);
        
//         UserService::setLog('token认证中间件结束', $startTime);
        return $next($request);
    }
}
