<?php

namespace Acme\Domains\Secretariat\Models;

use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;

class Mobile
{
	protected $mobile;

    protected $authy_number;

    protected $authy_dialling_code;
    /**
     * Mobile constructor.
     * @param $number
     */
    public function __construct($number, $country = 'PH')
    {
        $this->mobile = PhoneNumber::make($number, $country);
    }

    public static function number($number, $country = 'PH')
    {
    	return (new static($number, $country))->mobile->formatE164();
    }

    public static function national($number, $country = 'PH')
    {
        return (new static($number, $country))->mobile->formatForMobileDialingInCountry('PH');
    }

    public static function prefix($number, $country = 'PH')
    {
		return preg_split("/[\s,]+/", (new static($number, $country))->mobile->formatInternational('PH'))[1];
    }

    public static function authy($number, $country = 'PH')
    {
        $split = preg_split(
            "/[\s,]+/", 
            (new static($number, $country))->mobile->formatInternational()
        );

        $code = (string) (integer) array_shift($split);
        $number = implode('',$split);

        return compact('code', 'number');
    }

    public static function validate($mobile)
    {
        $validator = Validator::make(compact('mobile'), ['mobile' => 'required|phone:PH']);

        if ($validator->passes())
            return static::number($mobile);

        return false;
    }
}
