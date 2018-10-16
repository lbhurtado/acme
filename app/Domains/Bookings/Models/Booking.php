<?php

namespace Acme\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
	use SoftDeletes;

    protected $fillable = [
    	'requested_at',
    	'confirmed_at',
    	'accepted_at',
    	'cancelled_at',
    	'fulfilled_at',
    	'notes',
    ];

    public function bookable()
    {
    	return $this->morphTo();
    }

    public function customer()
    {
    	return $this->morphTo();
    }
}
