<?php

namespace Acme\Domains\Users\Models;

use FiveSay\Laravel\Model\ExtTrait;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberType;
use Illuminate\Validation\Rule;

class User extends Model
{
	use ExtTrait;

    public $rules = [
        'mobile' => [
            'required' 	   => 'mobile field is required',
            'phone:PH'	   => 'mobile field must be a valid PH phone number',
        ],
    ];
}
