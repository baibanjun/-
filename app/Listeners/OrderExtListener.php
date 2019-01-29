<?php

namespace App\Listeners;

use App\Events\OrderExt;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\OrderService;

class OrderExtListener
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
     * @param  OrderExt  $event
     * @return void
     */
    public function handle(OrderExt $event)
    {
        return OrderService::createExt($event->product, $event->order->id, $event->primaryDistributionUid, $event->secondaryDistributionUid);
    }
}
