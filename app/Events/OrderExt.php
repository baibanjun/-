<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Product;
use App\Models\Order;

class OrderExt
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product, $order, $primaryDistributionUid, $secondaryDistributionUid;
    
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Product $product, Order $order, $primaryDistributionUid, $secondaryDistributionUid)
    {
        $this->product                  = $product;
        $this->order                    = $order;
        $this->primaryDistributionUid   = $primaryDistributionUid;
        $this->secondaryDistributionUid = $secondaryDistributionUid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
