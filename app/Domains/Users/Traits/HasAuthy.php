<?php

namespace Acme\Domains\Users\Traits;

use Acme\Domains\Users\Events\UserWasRecorded;

trait HasAuthy
{

        public static function bootHasAuthy()
        {

                static::created(function ($model) {
                        event(new UserWasRecorded($model));
                });

                // static::updated(function ($model) {
                //         if ($model->wasRecentlyCreated == true) {
                //                 if ($model->isDirty('authy_id') && ! empty($model->authy_id)) {
                //                         event(new UserWasRegistered($model));
                //                 }             
                //         }
                // });
        }
}