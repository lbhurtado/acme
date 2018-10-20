<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models\User;
use Acme\Domains\Users\Jobs as Jobs;
use Acme\Domains\Users\Events as Events;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Acme\Domains\Users\Listeners\Notify as Notify;
use Acme\Domains\Users\Listeners\Capture as Capture;

class UserEventTest extends TestCase
{
	use RefreshDatabase, WithFaker;

    /** @test */
    function user_model_has_user_recorded_event()
    {
        $this->expectsEvents(Events\UserWasRecorded::class);

        factory(User::class)->create();
    }


    /** @test */
    function user_model_recorded_event_has_capture_user_mobile_data_listener()
    {
        $listener = \Mockery::spy(Capture\UserMobileData::class);
        app()->instance(Capture\UserMobileData::class, $listener);

        $user = factory(User::class)->create();

        $listener->shouldHaveReceived('handle')->with(\Mockery::on(function($event) use ($user) {
            $this->assertInstanceOf(Events\UserWasRecorded::class, $event);

            return empty($event->user->authy_id);
        }))->once();

        $this->assertTrue(empty($user->authy_id));
    }

    /** @test */
    function user_model_capture_user_mobile_data_listener_pushes_registry_authy_service()
    {
        \Queue::fake();

        factory(User::class)->create();

        \Queue::assertPushed(Jobs\RegisterAuthyService::class);
    }

    /** @test */
    function user_model_has_user_registered_event()
    {
        $user = factory(User::class)->create();
        $user->authy_id = $this->faker->randomNumber(7);

        $this->expectsEvents(Events\UserWasRegistered::class);

        $user->save();
    }

    /** @test */
    function user_model_registered_event_has_notify_user_about_verification_listener()
    {
        $listener = \Mockery::spy(Notify\UserAboutVerification::class);
        app()->instance(Notify\UserAboutVerification::class, $listener);

        tap(factory(User::class)->create(), function($user) {
            $user->authy_id = $this->faker->randomNumber(7);
        })->save();

        $listener->shouldHaveReceived('handle')->with(\Mockery::on(function($event) {
            $this->assertInstanceOf(Events\UserWasRegistered::class, $event);

            return !empty($event->user->authy_id);
        }))->once();    
    }

    /** @test */
    function user_model_notify_user_about_verification_listener_pushes_request_otp()
    {
        \Queue::fake();

        tap(factory(User::class)->create(), function($user) {
            $user->authy_id = $this->faker->randomNumber(7);
        })->save();
        
        \Queue::assertPushed(Jobs\RequestOTP::class);
    }

    /** @test */
    function user_model_has_user_verified_event()
    {
        $user = factory(User::class)->create();
        $user->authy_id = $this->faker->randomNumber(7);

        $this->expectsEvents(Events\UserWasVerified::class);

        $user->verifiedBy($this->faker->randomNumber(6), false);
    }
}
