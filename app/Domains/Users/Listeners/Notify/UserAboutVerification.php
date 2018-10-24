<?php

namespace Acme\Domains\Users\Listeners\Notify;

use Acme\Domains\Users\Jobs\RequestOTP;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAboutVerification
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        RequestOTP::dispatch($event->user)->delay(now()->addSeconds(10));
    }
}
