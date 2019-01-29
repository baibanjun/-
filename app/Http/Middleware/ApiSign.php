<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\AuthService;

class ApiSign
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
        AuthService::setLog('api认证中间件开始',$startTime,['token'=>$request->header('token')]);
        
        //api请求验证
        if (config('console.checkAuth')) {
            $auth = AuthService::auth($request->header('sign'), $request->header('random'), $request->header('timestamp'));
            if ($auth['code'] != '0000') {
                $auth['ip'] = $request->ip();
                AuthService::setLog('api认证中间件失败', $startTime, $auth);
                return response()->json($auth);
            }
        }
        AuthService::setLog('api认证中间件结束', $startTime,['token'=>$request->header('token')]);
        return $next($request);
    }
}
