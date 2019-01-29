<?php

namespace App\Listeners;

use App\Events\BusinessLogin;

use App\Services\BusinessService;

class BusinessLoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(BusinessLogin $event)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BusinessLogin  $event
     * @return void
     */
    public function handle(BusinessLogin $event)
    {
        // 设置为登陆
        $log            = $event->log;
        $log['uid']     = $event->business->id;
        $log['token']   = BusinessService::passwordEncrypt($event->business, time()).uniqid();
        return BusinessService::setLoginLog($log);
    }
}
