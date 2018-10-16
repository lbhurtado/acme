<?php

namespace Tests\Feature;

use Tests\TestCase;
use Acme\Domains\Users\Models as Models;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Acme\Domains\Bookings\Models\Booking;

class BookableTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function worker_has_a_rate()
    {
    	$worker = factory(Models\Worker::class)->create();
    	$price = 537;
    	$attributes = compact('price');

    	$worker->rate()->create($attributes);

        $this->assertDatabaseHas('rates', $attributes);
        $this->assertEquals($worker->rate->bookable_type, Models\Worker::class);
        $this->assertEquals($worker->rate->bookable_id, $worker->id);
        $this->assertEquals($worker->rate->price, $price);
    }

    /** @test */
    public function worker_has_an_availability()
    {
        $worker = factory(Models\Worker::class)->create();
        $open = true;
        $attributes = compact('open');

        $worker->availability()->create($attributes);

        $this->assertDatabaseHas('availabilities', $attributes);
        $this->assertEquals($worker->availability->bookable_type, Models\Worker::class);
        $this->assertEquals($worker->availability->bookable_id, $worker->id);
        $this->assertEquals($worker->availability->open, $open);
    }

    /** @test */
    public function worker_can_be_booked_by_subscriber()
    {
        $worker = factory(Models\Worker::class)->create();
        $subscriber = factory(Models\Subscriber::class)->create();
        $requested_at = now();
        $confirmed_at = now();
        $cancelled_at = now();
        $accepted_at = now();
        $fulfilled_at = now();
        $notes = 'The quick brown fox...';
        $attributes = compact(
            'requested_at', 
            'confirmed_at',
            'cancelled_at', 
            'accepted_at', 
            'fulfilled_at', 
            'notes'
        );

        $booking = new Booking();
        $booking->customer()->associate($subscriber);
        $booking->bookable()->associate($worker);
        $booking->requested_at = $requested_at;
        $booking->confirmed_at = $confirmed_at;
        $booking->cancelled_at = $cancelled_at;
        $booking->accepted_at = $accepted_at;
        $booking->fulfilled_at = $fulfilled_at;
        $booking->notes = $notes;
        $booking->save();

        $this->assertDatabaseHas('bookings', $attributes);
        $this->assertEquals($booking->customer->type, Models\Subscriber::class);
        $this->assertEquals($booking->bookable->type, Models\Worker::class);
        $this->assertEquals($booking->customer->id, $subscriber->id);
        $this->assertEquals($booking->bookable->id, $worker->id);
    }
}
