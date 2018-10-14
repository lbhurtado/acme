<?php

namespace Acme\Domains\Users\Models;

use MyCLabs\Enum\Enum;

/**
 * UserType enum
 */
class UserType extends Enum
{
    const ADMIN    		= 'admin';
    const OPERATOR   	= 'operator';
    const STAFF      	= 'staff';
    const SUBSCRIBER 	= 'subscriber';
    const WORKER   	 	= 'worker';
}