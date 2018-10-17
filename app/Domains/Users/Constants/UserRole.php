<?php

namespace Acme\Domains\Users\Constants;

use MyCLabs\Enum\Enum;

class UserRole extends Enum
{
    const ADMIN    		= 'admin';
    const OPERATOR   	= 'operator';
    const STAFF      	= 'staff';
    const SUBSCRIBER 	= 'subscriber';
    const WORKER   	 	= 'worker';
}