<?php

namespace Acme\Domains\Users\Models;

use Acme\Domains\Users\Traits\HasParentModel;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
	use HasParentModel;

	public static $role = UserType::ADMIN;
}