<?php

namespace Acme\Domains\Users\Models;

use Acme\Domains\Users\Traits\HasParentModel;
use Acme\Domains\Bookings\Models\{Rate, Availability};

class Worker extends User
{
	use HasParentModel;

	public static $role = UserType::WORKER;

    public function rate()
    {
        return $this->morphOne(Rate::class, 'bookable');
    }

    public function availability()
    {
        return $this->morphOne(Availability::class, 'bookable');
    }
}
