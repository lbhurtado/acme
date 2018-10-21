<?php

namespace Acme\Domains\Secretariat\Jobs;

use Illuminate\Bus\Queueable;
use Acme\Domains\Users\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Acme\Domains\Secretariat\Models\{Placement, Activation};

class LogPlacement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    public $placement;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Placement $placement)
    {
        $this->user = $user;

        $this->placement = $placement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        tap($this->placement->activations()->make(), function($activation) {
            $activation->user()->associate($this->user);   
        })->save();
    }
}
