<?php

namespace Acme\Domains\Bookings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'price',
    ];

    public function bookable()
    {
    	return $this->morphTo();
    }
}
