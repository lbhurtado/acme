<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Acme\Domains\Users\Models as Models;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\PhoneNumber;
use Acme\Domains\Secretariat\Models\Placement;
use Acme\Domains\Secretariat\Events\UserWasFlagged;
use Acme\Domains\Users\Jobs\RequestOTP;
use Acme\Domains\Users\Notifications\PhoneVerification;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::get('/test', function () {

    $user = Models\Operator::find(25);
    // dd($user);
        event(new UserWasFlagged($user));
        // RequestOTP::dispatch($user);
    // $user->notify(new PhoneVerification('sms', true));

    // if (validate($mobile)) {

    //     dd(trans('registration.input.mobile'));

    //     $attributes = [
    //         'mobile' => '09178251991',
    //         // 'name' => 'Test User',
    //     ];

    //     optional(Placement::activate($code, $attributes), function($model) {

    //         dd($model);
    //     });

    // }

    dd('should not be here!');
});