<?php

namespace Acme\Domains\Users\Models;

use Kalnoy\Nestedset\NodeTrait;
use FiveSay\Laravel\Model\ExtTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	use ExtTrait, NodeTrait, HasRoles;

    protected $guard_name = 'web';

    protected $rules = [
        'mobile' => [
            'required' => 'mobile field is required',
            'phone:PH' => 'mobile field must be a valid PH phone number',
        ],
    ];
}
