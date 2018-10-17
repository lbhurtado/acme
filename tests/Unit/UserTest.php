<?php

namespace Tests\Unit;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Acme\Domains\Users\Models as Models;
use Acme\Domains\Users\Models\{User, Admin};
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
	use RefreshDatabase;

	protected $classes = [
		Models\Admin::class,
		Models\Operator::class,
		Models\Staff::class,
		Models\Subscriber::class,
		Models\Worker::class,
	];

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

    	$mobile = config('acme.test.user1.mobile');
    	factory(User::class)->create(compact('mobile'));
    	factory(User::class)->create(compact('mobile'));
    }

    /** @test */
    public function user_has_persistent_mobile_attribute()
    {
    	$mobile = config('acme.test.user1.mobile');
    	factory(User::class)->create(compact('mobile'));

        $this->assertDatabaseHas('users', compact('mobile'));
    }

    // /** @test */
    // public function user_mobile_field_has_phone_validation()
    // {
    // 	$this->expectException(\Illuminate\Validation\ValidationException::class);    	
    // 	$mobile = '1234';

    // 	factory(User::class)->create(compact('mobile'));
    // }

    /** @test */
    public function user_can_have_uplines_and_downlines()
    {
    	$user1 = factory(User::class)->create();
    	$user2 = factory(User::class)->create();

    	$user1->appendNode($user2);

    	$this->assertEquals($user1->descendants[0]->id, $user2->id);
    	$this->assertEquals($user2->ancestors[0]->id, $user1->id);
    }	

    /** @test */
    public function user_has_roles()
    {
    	$user = factory(User::class)->create();
 
        $role1 = Role::create(['name' => 'actor']);
        $role2 = Role::create(['name' => 'writer']);

        $user->assignRole($role1, $role2);

        $this->assertEquals($user->roles->count(), 2);
        $this->assertTrue($user->hasRole('actor'));
        $this->assertTrue($user->hasRole('writer'));
    }

    /** @test */
    function user_model_has_inherits_permissions_from_roles()
    {
    	$user = factory(User::class)->create();

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
    function child_model_is_essentially_a_user_with_a_type()
    {
    	foreach ($this->classes as $class) {
		    $descendant = factory($class)->create();
	  		$user = User::find($descendant->id);

		    $this->assertEquals($user->id, $descendant->id);
		    $this->assertEquals($user->type, $class);
            $this->assertInstanceOf($class, $user);
    	}
    }

    /** @test */
    function child_model_has_a_child_role()
    {	
    	foreach ($this->classes as $class) {
		    $descendant = factory($class)->create();

 			$this->assertDatabaseHas('roles', [
 				'name' 		 => $descendant::$role, 
 				'guard_name' => $descendant->getGuardName()
 			]);
    		$this->assertTrue($descendant->hasRole($descendant::$role)); 
    	}
    }
}
