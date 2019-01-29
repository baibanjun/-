<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\User;

class UserLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $user;
    
    public $log;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $log)
    {
        $this->user         = $user;
        $this->log          = $log;
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
