<?php

namespace Acme\Domains\Secretariat\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VerifyOTP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    protected $otp;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;

        $this->otp = $otp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $verified = app('rinvex.authy.token')
                        ->verify($this->otp, $this->user->authy_id)
                        ->succeed();

        $verified_at = now();

        if ($verified) $this->user->forceFill(compact('verified_at'))->save();          
    }
}
