<?php

namespace Acme\Providers;

use Illuminate\Support\ServiceProvider;
use Acme\Domains\Users\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // User::observe(UserObserver::class);
        // Operator::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
