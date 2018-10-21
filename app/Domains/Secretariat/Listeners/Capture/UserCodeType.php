<?php

namespace Acme\Domains\Secretariat\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acme\Domains\Secretariat\Jobs\LogPlacement;
// use Acme\Domains\Secretariat\Events\PlacementWasRecorded;

class UserCodeType
{
    /**
     * Handle the event.
     *
     * @param  PlacementWasRecorded  $event
     * @return void
     */
    public function handle($event)
    {
        LogPlacement::dispatch($event->user, $event->placement);
    }
}
