<?php

return [
    'input' => [
    	'mobile' => env('BOT_REGISTRATION_MOBILE', 'Please input your mobile number.'),
    	'code' => env('BOT_REGISTRATION_CODE', 'Please input your code.'),
    	'repeat' => env('BOT_REGISTRATION_REPEAT', 'Please repeat your input.'), 
    	'throttle' => env('BOT_REGISTRATION_THROTTLE', 'You have exceeded...'),    	
    	'error' => env('BOT_REGISTRATION_ERROR', 'Input error!'),
    	'pin' => env('BOT_REGISTRATION_PIN', 'Please input your OTP.'),
    ],
    'break' => env('BOT_REGISTRATION_BREAK', 'Break, break...'),  
    'success' => 'Yehey!',
    'authenticated' => 'You are authenticated!',
    'failed' => 'Boo!',
];
