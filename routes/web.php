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
use Acme\Domains\Secretariat\Models\Placement;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::get('/test', function () {
    $admin = Models\Admin::first();

    if ($admin->hasPermissionTo('create placement')) {
    	$code = '123456';
    	$type = Models\Operator::class;
    	$placement = Placement::record(compact('code', 'type'), $admin);
    	dd($placement);
    }
    dd('here');

});