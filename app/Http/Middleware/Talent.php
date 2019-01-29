<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTalent;
use App\Models\User;

class Talent
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
        UserService::setLog('达人认证中间件开始');
        
        $talent = UserService::getUserTalentInfo(Auth::id(),['uid','status'],['id','status']);

        if ($talent['code'] != '0000' || $talent['data']->status != UserTalent::STATUS_1 || $talent['data']->user->status != User::STATUS_NORMAL) {
            UserService::setLog('达人认证中间件失败', $startTime,['ip'=>$request->ip(),$request->all()]);
            return response()->json(UserService::returnCode('sys.dataDoesNotExist'));
        }

        UserService::setLog('达人认证中间件结束', $startTime);
        return $next($request);
    }
}
