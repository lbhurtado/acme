<?php

namespace Acme\Domains\Messenger\Listeners\Notify;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acme\Domains\Messenger\Events\UserWasTagged;
use Acme\Domains\Secretariat\Jobs\WakePlacement;

class PlacementAboutActivation
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
     * @param  UserWasTagged  $event
     * @return void
     */
    public function handle(UserWasTagged $event)
    {
        WakePlacement::dispatch($event->code, $event->attributes);
    }
}
