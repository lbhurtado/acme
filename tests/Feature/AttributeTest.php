<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    function user_has_schemaless_attributes()
    {
        $user = factory(Models\User::class)->create();
        $string = 'string';
        $array = ['array' => 'array'];

        $user->extra_attributes->string = $string;
        $user->extra_attributes->array = $array;
        $user->save();

        $this->assertEquals($user->extra_attributes->string, $string);
        $this->assertEquals($user->extra_attributes->array, $array);
        $this->assertDatabaseHas('users', [
            'extra_attributes' => json_encode(compact('string','array'))
        ]);   
    }
}
