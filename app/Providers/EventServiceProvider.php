<?php

namespace Acme\Providers;

use Acme\Domains\Users\Events as Events;
use Acme\Domains\Users\Listeners as Listeners;
use Acme\Domains\Secretariat as Secretariat;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Events\UserWasRecorded::class => [
            Listeners\Capture\UserMobileData::class,
        ],
        Events\UserWasRegistered::class => [
            Listeners\Notify\UserAboutVerification::class,
        ],    
        Secretariat\Events\PlacementWasRecorded::class => [
            Secretariat\Listeners\Capture\UserCodeType::class,
        ],
        // Events\UserWasFlagged::class => [
        //     Listeners\Notify\UserAboutVerification::class,
        // ],
        'Acme\Domains\Secretariat\Events\UserWasFlagged' => [
            'Acme\Domains\Users\Listeners\Notify\UserAboutVerification'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
