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
    public function user_has_required_mobile_attribute()
    {
    	$this->expectException(\Illuminate\Validation\ValidationException::class);

    	$mobile = null;
    	factory(User::class)->create(compact('mobile'));
    }

    /** @test */
    public function user_has_unique_mobile_attribute()
    {
    	$this->expectException(\Exception::class);

    	$mobile = config('acme.test.user.mobile');
    	factory(User::class)->create(compact('mobile'));
    	factory(User::class)->create(compact('mobile'));
    }

    /** @test */
    public function user_has_persistent_mobile_attribute()
    {
    	$mobile = config('acme.test.user.mobile');
    	factory(User::class)->create(compact('mobile'));

        $this->assertDatabaseHas('users', compact('mobile'));
    }

    /** @test */
    public function user_mobile_field_has_ph_phone_validation()
    {
    	$this->expectException(\Illuminate\Validation\ValidationException::class);    	
    	$mobile = '1234567';

    	factory(User::class)->create(compact('mobile'));
    }
}
