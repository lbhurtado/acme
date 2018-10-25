<?php

namespace Acme\Domains\Messenger\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserWasTagged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;

    public $attributes;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($code, $attributes)
    {
        $this->code = $code;
        $this->attributes = $attributes;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user-tagged');
    }
}
