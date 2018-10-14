# Acme

## Introduction

The quick brown fox...    

## Code Samples

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Clarion\Domain\Models\User;
use Clarion\Domain\Models\Mobile;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Clarion\Domain\Contracts\UserRepository;
use Clarion\Domain\Criteria\HasTheFollowing;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
	use RefreshDatabase;

    function setUp()
    {
        parent::setUp();

        $this->withoutEvents();
    }
    
  	/** @test */
    function user_model_has_required_mobile_field()
    {
    	$this->expectException(\libphonenumber\NumberParseException::class);

        $user = User::create(['mobile' => null]);
    }

  	/** @test */
    function user_model_has_mobile_and_handle_fields()
    {
        $user = factory(User::class)->create(['mobile' => '09189362340', 'handle' => 'Lester']);

        $this->assertEquals(Mobile::number('09189362340'), $user->mobile);
        $this->assertEquals('Lester', $user->handle);
    }

    /** @test */
    function user_model_mobile_field_is_default_handle_value()
    {
    	$number = '09189362340';

        $user = $this->app->make(UserRepository::class)->create([
            'mobile' => $number,
        ]);

        $this->assertEquals(Mobile::number($number), $user->mobile);
        $this->assertEquals(Mobile::number($number), $user->handle);
    }

    /** @test */
    function user_model_has_unique_mobile_field()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        factory(User::class)->create(['mobile' => '09189362340']);
        factory(User::class)->create(['mobile' => '09189362340']);
    }

    /** @test */
    function user_model_only_accepts_ph_mobile_number()
    {
        $this->expectException(\Propaganistas\LaravelPhone\Exceptions\NumberParseException::class);

    	factory(User::class)->create(['mobile' => '+1 (301) 1234-567']);
    }

    /** @test */
    function user_model_has_roles()
    {
        $user = factory(User::class)->create(['mobile' => '09189362340']);
 
        $role1 = Role::create(['guard_name' => 'api', 'name' => 'actor']);

        $role2 = Role::create(['guard_name' => 'api', 'name' => 'writer']);

        $user->assignRole($role1, $role2);


        $this->assertEquals($user->roles->count(), 2);
        $this->assertTrue($user->hasRole('actor'));
        $this->assertTrue($user->hasRole('writer'));
    }    

    /** @test */
    function user_model_has_inherits_permissions_from_roles()
    {
        $user = factory(User::class)->create(['mobile' => '09189362340']);

        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
 
        Role::create(['name' => 'writer'])
            ->givePermissionTo('edit articles')
            ->givePermissionTo('delete articles');

        $user->assignRole('writer');

        $this->assertTrue($user->hasPermissionTo('edit articles'));
        $this->assertTrue($user->hasPermissionTo('delete articles'));
    }   

    /** @test */
    function user_model_has_identifier_attribute()
    {
        $user = factory(User::class)->create(['mobile' => '09189362340']);

        $this->assertTrue($user->identifier !== null);
    }

    /** @test */
    function user_model_has_the_following_moble_criterion()
    {
        factory(User::class)->create(['mobile' => '09189362340']);
        factory(User::class)->create(['mobile' => '09173011987']);

        $users = $this->app->make(UserRepository::class);

        $this->assertEquals($users->all()->count(), 2);

        $users->pushCriteria(HasTheFollowing::mobile('09189362340'));   

        $this->assertEquals($users->all()->count(), 1);

        $users->popCriteria(HasTheFollowing::mobile('09189362340'));

        $users->pushCriteria(HasTheFollowing::mobile('09189362340', '09173011987'));   

        $this->assertEquals($users->all()->count(), 2);
    }    

    /** @test */
    function user_model_has_the_following_handle_criterion()
    {
        factory(User::class)->create(['mobile' => '09171111111', 'handle' => 'lester']);
        factory(User::class)->create(['mobile' => '09182222222', 'handle' => 'apple']);
        factory(User::class)->create(['mobile' => '09193333333', 'handle' => 'cheesecake']);

        // \DB::listen(function ($query) {
        //     var_dump($query->sql);
        // });
        
        $users = $this->app->make(UserRepository::class);

        $this->assertEquals($users->all()->count(), 3);

        $users->pushCriteria(HasTheFollowing::handle('apple', 'cheesecake'));

        $this->assertEquals($users->all()->count(), 2);
    }   

    /** @test */
    function user_model_has_nodes()
    {
        $user1 = User::create(config('clarion.test.user1'));
        $user2 = User::create(config('clarion.test.user2'));

        $user1->appendNode($user2);

        $this->assertEquals($user1->descendants[0]->id, $user2->id);
        $this->assertEquals($user2->ancestors[0]->id, $user1->id);
    }
}


## Installation

git clone https://github.com/lbhurtado/acme