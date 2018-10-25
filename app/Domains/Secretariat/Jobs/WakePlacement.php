<?php

namespace Acme\Domains\Secretariat\Jobs;

use Illuminate\Bus\Queueable;
use Acme\Domains\Users\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Acme\Domains\Secretariat\Models\Placement;

class WakePlacement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $code;

    public $attributes;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code, $attributes)
    {
        $this->code = $code;

        $this->attributes = $attributes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $placement = Placement::first();
        // $placement = Placement::bearing($this->code)->first();
        // $placement->wake($this->attributes);

        optional(Placement::bearing($this->code)->first(), function($placement) {
            $placement->wake($this->attributes);
        });
    }
}
