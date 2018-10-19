<?php

namespace Tests\Feature;

use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Acme\Domains\Secretariat\Models\Placement;
use Acme\Domains\Users\Constants as Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /** @test */
    public function admin_can_create_a_placement_and_user_can_auto_register()
    {
   		$upline = Models\Admin::first();
		$code =  $this->faker->word;
		$type = Models\Operator::class;
		$placement = tap(
			$upline->placements()->make(compact('code', 'type')), function ($placement) use ($upline) {
				$placement->user()->associate($upline)->save();		
			});

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
		$placement = tap(
			$upline->placements()->make(compact('code', 'type')), function ($placement) use ($upline) {
				$placement->user()->associate($upline)->save();		
			});

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
}
