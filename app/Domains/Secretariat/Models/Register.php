<?php

namespace Acme\Domains\Secretariat\Models;

use Validator;

class Register
{
    protected $regex = "/^(?<mobile>\S*)\s(?<code>\S*)$/i";

    protected $rules = [
        'mobile' => 'required|phone:PH',
        'code' => 'required|exists:placements,code',
    ];

    protected $arguments;

    protected $matches;

    protected $attributes;

	public static function attributes($arguments)
	{
		return (new static($arguments))
			->extractMatchedParameters()
			->removeNumericIndices()
            ->validateParameters()	
			->getAttributes()
			;
	}

    public function __construct($arguments)
    {
        $this->arguments = $arguments;
    }

    protected function extractMatchedParameters()
    {
		preg_match($this->regex, $this->arguments, $this->matches);

		return $this;
    }

    protected function removeNumericIndices()
    {
    	foreach ($this->matches as $k => $v) { 
    		if (is_int($k)) { 
    			unset($this->matches[$k]); 
    		} 
    	}

    	return $this;
    }

    protected function validateParameters()
    {
        $validator = Validator::make($this->matches, $this->rules);

        if ($validator->passes()) {
            $this->attributes = $this->matches;
        }
	
		return $this;
    }

    protected function getAttributes()
    {
    	return $this->attributes ?? false;
    }
}
