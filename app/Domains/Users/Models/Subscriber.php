<?php

namespace Acme\Domains\Users\Models;

use Acme\Domains\Users\Traits\HasParentModel;

class Subscriber extends User
{
	use HasParentModel;

	public static $role = UserType::SUBSCRIBER;
}
