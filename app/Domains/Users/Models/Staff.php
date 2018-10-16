<?php

namespace Acme\Domains\Users\Models;

use Acme\Domains\Users\Traits\HasParentModel;
use Illuminate\Database\Eloquent\Model;

class Staff extends User
{
	use HasParentModel;

	public static $role = UserType::STAFF;
}