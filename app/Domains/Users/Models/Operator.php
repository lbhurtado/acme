<?php

namespace Acme\Domains\Users\Models;

use Acme\Domains\Users\Traits\HasParentModel;
use Acme\Domains\Users\Constants as Constants;

class Operator extends User
{
	use HasParentModel;

	public static $role = Constants\UserRole::OPERATOR;
}
