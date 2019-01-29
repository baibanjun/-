<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

use App\Models\Business;

class BusinessLogin
{
    use SerializesModels;

    public $business;
    
    public $log;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Business $business, array $log = [])
    {
        $this->business = $business;
        $this->log      = $log;
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
