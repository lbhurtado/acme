<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Acme\Domains\Users\Notifications\PhoneVerification;

use Illuminate\Support\Facades\Notification;
// use NotificationChannels\Twilio\TwilioChannel;

class UserNotificationTest extends TestCase
{
	use RefreshDatabase, WithFaker;

    function setUp()
    {
        parent::setUp();

        $this->withoutEvents();
    }
    
    /** @test */
    public function user_can_be_notified_for_phone_verification()
    {
    	$user = factory(User::class)->create([
    		'mobile' => '+639173011987',
    		'authy_id' => '7952368',
    	]);
   
   		Notification::fake();

   		$actionName = $this->faker->word;
   		$actionMessage = $this->faker->sentence;

		  $user->notify(new PhoneVerification('sms', true, $actionName, $actionMessage));

        Notification::assertSentTo($user,
        	PhoneVerification::class,
        	function ($notification, $channels) use ($actionName, $actionMessage) {
				$this->assertEquals($notification->action, $actionName);
				$this->assertEquals($notification->actionMessage, $actionMessage);

				return $notification->method === 'sms';
			}
		);
    }
}
