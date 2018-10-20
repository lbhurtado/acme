<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models\User;
use Acme\Domain\Users\Events as Events;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserEventTest extends TestCase
{
	use RefreshDatabase, WithFaker;

    // function setUp()
    // {
    //     parent::setUp();

    //     $this->faker = $this->makeFaker('en_PH');
    // }

    /** @test */
    function user_model_has_user_recorded_event()
    {
        $this->expectsEvents(Events\UserWasRecorded::class);

        $user = factory(User::class)->create();

        // dd($user);
    }
}
