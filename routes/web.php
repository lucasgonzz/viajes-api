<?php

use Illuminate\Support\Facades\Route;

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

Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');

Route::get('order/pdf/{id}', 'OrderController@pdf');

Route::get('current-acount/pdf/{client_id}/{months_ago}', 'CurrentAcountController@pdf');

Route::get('asd', function() {
	echo Carbon\Carbon::now()->subDays(1);
});
