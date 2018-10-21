<?php

namespace Acme\Domains\Secretariat\Events;

use Acme\Domains\Users\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Acme\Domains\Secretariat\Models\Placement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PlacementWasRecorded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public $placement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Placement $placement)
    {
        $this->user = $user;
        
        $this->placement = $placement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('placement.recorded');
    }
}
