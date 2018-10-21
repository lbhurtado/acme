<?php

namespace Acme\Domains\Secretariat\Observers;

use Acme\Domains\Secretariat\Models\Placement;
use Acme\Domains\Secretariat\Events\PlacementWasRecorded;

class PlacementObserver
{
    /**
     * Handle the placement "created" event.
     *
     * @param  \Acme\Acme\Domains\Secretariat\Models\Placement  $placement
     * @return void
     */
    public function created(Placement $placement)
    {
        // event(new PlacementWasRecorded($placement));
    }

    /**
     * Handle the placement "updated" event.
     *
     * @param  \Acme\Acme\Domains\Secretariat\Models\Placement  $placement
     * @return void
     */
    public function updated(Placement $placement)
    {
        //
    }

    /**
     * Handle the placement "deleted" event.
     *
     * @param  \Acme\Acme\Domains\Secretariat\Models\Placement  $placement
     * @return void
     */
    public function deleted(Placement $placement)
    {
        //
    }

    /**
     * Handle the placement "restored" event.
     *
     * @param  \Acme\Acme\Domains\Secretariat\Models\Placement  $placement
     * @return void
     */
    public function restored(Placement $placement)
    {
        //
    }

    /**
     * Handle the placement "force deleted" event.
     *
     * @param  \Acme\Acme\Domains\Secretariat\Models\Placement  $placement
     * @return void
     */
    public function forceDeleted(Placement $placement)
    {
        //
    }
}
