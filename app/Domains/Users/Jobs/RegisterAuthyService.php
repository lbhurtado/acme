<?php

namespace Acme\Domains\Users\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Acme\Domains\Secretariat\Models\Mobile;

class RegisterAuthyService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {        
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $authy_id = $this->getAuthyId();

        tap($this->user, function($user) use ($authy_id) {
            $user->forceFill(compact('authy_id'));
        })->save();
    }

    protected function getAuthyId()
    {
        extract(Mobile::authy($this->user->mobile));

        $email = "$code$number@serbis.io";

        return app('rinvex.authy.user')
            ->register($email, $number, $code)
            ->get('user')['id'];
    }
}
