<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Acme\Domains\Users\Notifications\UserVerified;

use Illuminate\Support\Facades\Notification;
use NotificationChannels\Twilio\TwilioChannel;

class UserNotificationTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function user_can_be_notified()
    {
    	$user = factory(User::class)->create(['mobile' => '+639173011987']);
   
    	$user->notify(new UserVerified());

     //    $this->assertEquals($error_code, 21212);

		// Notification::route('mail', 'taylor@example.com')
  //           ->route('nexmo', '+639173011987')
  //           ->route(TwilioChannel::class, '+639173011987')
  //           ->notify(new UserVerified());

    }
}
