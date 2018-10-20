<?php

namespace Acme\Domains\Users\Listeners\Capture;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acme\Domains\Users\Jobs\RegisterAuthyService;

class UserMobileData
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        RegisterAuthyService::dispatch($event->user);
    }
}
