<?php

namespace App\Listeners;

use App\Events\AdminDatabase;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Event;
use App\Services\Admin\RecordService;

class AdminDatabaseListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminDatabase  $event
     * @return void
     */
    public function handle(AdminDatabase $event)
    {
        Event::listen(['eloquent.created: *'], function($foo, $bar)
        {
            $action['type'] = 'created';
            RecordService::createLog($action, $bar);
        });
        Event::listen(['eloquent.updated: *'], function ($foo, $bar)
        {
            $action['type'] = 'updated';
            RecordService::createLog($action, $bar);
        });
        Event::listen(['eloquent.deleted: *'], function ($foo, $bar)
        {
            $action['type'] = 'deleted';
            RecordService::createLog($action, $bar);
        });
    }
}
