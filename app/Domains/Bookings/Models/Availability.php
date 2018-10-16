<?php

namespace Acme\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'open',
    ];

    public function bookable()
    {
    	return $this->morphTo();
    }
}
