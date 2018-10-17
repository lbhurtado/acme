<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Acme\Domains\Users\Constants as Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutoRegistrationTest extends TestCase
{
	use RefreshDatabase;

    function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }
    /** @test */
    public function admin_is_seeded()
    {
    	$user = Models\Admin::first();

        $this->assertFalse(is_null($user));
        $this->assertEquals($user->type, Models\Admin::class);
    }

    /** @test */
    public function admin_can_create_placement()
    {
    	$admin = Models\Admin::first();

    	$this->assertTrue($admin->hasPermissionTo(Constants\UserPermission::CREATE_PLACEMENT));
    }
}
