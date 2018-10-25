<?php

namespace Tests\BotMan;

use Tests\TestCase;
use Acme\Domains\Secretariat\Models\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecretariatTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    function setUp()
    {
        parent::setUp();

        $this->withoutEvents();

        $this->faker = $this->makeFaker('en_PH');

        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    // /** @test */
    // public function bot_can_tag_placements()
    // {
    //     foreach(Tag::$classes as $type => $class) {
    //         $code = $this->faker->unique()->word;
    //         $message = $this->faker->sentence;
    //         $this->bot
    //             ->receives("tag $type $code $message")
    //             ->assertReply(trans('secretariat.tag.success')) 
    //             ;
    //     }

    //     $type = 'asdasd';
    //     $code = $this->faker->unique()->word;
    //     $message = $this->faker->sentence;
    //     $this->bot
    //         ->receives("tag $type $code $message")
    //         ->assertReply(trans('secretariat.tag.failed')) 
    //         ;
    // }

    // /** @test */
    // public function bot_registration_requires_register_keyword()
    // {
    //     $this->bot
    //         ->receives('/register')
    //         ->assertQuestion(trans('registration.input.code'))             
    //         ;
    // }

    /** @test */
    public function bot_can_register_user()
    {
        $this->bot
            ->receives('register 09178251991 operator')  
            ->assertReply(trans('registration.input.pin'))            
            ;
    }

}
