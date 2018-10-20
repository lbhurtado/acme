<?php

namespace Acme\Domains\Users\Observers;

use Acme\Domains\Users\Models\User;
use Acme\Domains\Users\Events\{UserWasRecorded, UserWasRegistered, UserWasVerified};

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \Acme\Acme\Domains\Users\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        event(new UserWasRecorded($user));
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \Acme\Acme\Domains\Users\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->wasRecentlyCreated == true) {
            if ($user->isDirty('authy_id') && ! empty($user->authy_id)) {

                event(new UserWasRegistered($user));
            }
        }
        if ($user->isDirty('verified_at') && ! $user->isVerificationStale()) {

            event(new UserWasVerified($user));
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \Acme\Acme\Domains\Users\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \Acme\Acme\Domains\Users\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \Acme\Acme\Domains\Users\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
