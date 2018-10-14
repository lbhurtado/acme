<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Acme\Domains\Users\Models\User;

class UserTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function user_has_mobile_attribute()
    {
    	$mobile = config('acme.test.user.mobile');
        factory(User::class)->create(compact('mobile'));

        $this->assertDatabaseHas('users', compact('mobile'));
    }
}
