<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\BusinessService;

class CheckBusinessToken
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
        $loginLog       = BusinessService::getLoginLog($token,['uid']);
    
        if ($loginLog['code'] != '0000'){
            BusinessService::setLog('businessToken认证请求失败',$startTime,['token'=>$token,$request->all()]);
            return response()->json($loginLog);
        }

        Auth::guard('business')->loginUsingId($loginLog['data']->uid);
        
//         $params = [
//             'uid'       => $loginLog['data']->user->id,
//             'openid'    => $loginLog['data']->user->openid
//         ];
//         $request->attributes->add($params);
        
//         UserService::setLog('token认证中间件结束', $startTime);
        return $next($request);
    }
}
