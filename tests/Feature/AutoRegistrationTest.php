<?php

namespace Tests\Feature;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Acme\Domains\Users\Constants as Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Acme\Domains\Secretariat\Models\{Placement, Tag};

class AutoRegistrationTest extends TestCase
{
	use RefreshDatabase, WithFaker;

	protected $classes = [
		Models\Admin::class,
		Models\Operator::class,
		Models\Worker::class,
		// Models\Subscriber::class,
	];

    function setUp()
    {
        parent::setUp();

        // $this->withoutEvents();

        $this->faker = $this->makeFaker('en_PH');

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
    public function admin_has_permission_to_create_placement()
    {
    	$admin = Models\Admin::first();

    	$this->assertTrue($admin->hasPermissionTo(Constants\UserPermission::CREATE_PLACEMENT));
    }

    // /** @test */
    public function admin_can_create_a_placement_and_user_can_auto_register()
    {
   		$upline = Models\Admin::first();
		$code =  $this->faker->word;
		$type = Models\Operator::class;

        Placement::record(compact('code', 'type'), $upline);

        $this->assertDatabaseHas('placements', [
        	'user_id' => $upline->id, 
        	'code' => $code,
        	'type' => $type,
        ]);

		$mobile = $this->faker->mobileNumber;
		$name = $this->faker->name;
        $email = $this->faker->email;
		$downline = Placement::activate($code, compact('mobile', 'name', 'email'));

		$this->assertInstanceOf($type, $downline);
    	$this->assertEquals($upline->descendants[0]->id, $downline->id);
    	$this->assertEquals($downline->ancestors[0]->id, $upline->id);

   		$upline = Models\Operator::first();
		$code =  $this->faker->word;
		$type = Models\Worker::class;

        $placement = Placement::record(compact('code', 'type'), $upline);

        $this->assertDatabaseHas('placements', [
        	'user_id' => $upline->id, 
        	'code' => $code,
        	'type' => $type,
        ]);

		$mobile = $this->faker->mobileNumber;
		$name = $this->faker->name;
		$email = $this->faker->email;
        $downline = Placement::activate($code, compact('mobile', 'name', 'email'));

		$this->assertInstanceOf($type, $downline);
    	$this->assertEquals($downline->ancestors[0]->id, $upline->id);

        $this->assertDatabaseHas('activations', [
            'placement_id' => $placement->id,
            'user_id' => $downline->id, 
        ]);

		$nodes = Models\User::get()->toTree();

		$traverse = function ($categories, $prefix = '-') use (&$traverse) {
		    foreach ($categories as $category) {
		        echo PHP_EOL.$prefix.' '.$category->name.' ('.$category->email.')';

		        $traverse($category->children, $prefix.'-');
		    }
		};

		$traverse($nodes);
        echo PHP_EOL.' ';
        echo PHP_EOL.' ';

    }

    /** @test */
    public function placements_are_seeded()
    {
        $user = Models\Admin::first();
        foreach(Tag::$classes as $key => $values) {
            $code = $key;
            $type = $values;

            $this->assertDatabaseHas('placements', [
                'user_id' => $user->id, 
                'code' => $code,
                'type' => $type,
            ]);   
        }

    }
}
