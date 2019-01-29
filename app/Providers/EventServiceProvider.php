<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Services\Admin\RecordService;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserLogin' => [
            'App\Listeners\Login'
        ],
        'App\Events\BusinessLogin' => [
            'App\Listeners\BusinessLoginListener'
        ],
        'App\Events\AdminDatabase' => [
            'App\Listeners\AdminDatabaseListener',
        ],
        'App\Events\OrderExt' => [
            'App\Listeners\OrderExtListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
