<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->group(function() {

    Route::get('user', function(Request $request) {
        return response()->json(['user' => $request->user()], 200);
    });
    Route::put('user', 'UserController@update');
    Route::put('user/password', 'UserController@updatePassword');

    Route::get('order-operation', 'OrderOperationController@index');
    Route::get('order-type', 'OrderTypeController@index');
    Route::get('order-status', 'OrderStatusController@index');
    
    Route::get('order/{view}', 'OrderController@index');
    Route::post('order/filter', 'OrderController@filter');
    Route::resource('order', 'OrderController');
    Route::resource('client', 'ClientController');
    Route::resource('client-address', 'ClientAddressController');
    Route::resource('driver', 'DriverController');
    Route::resource('location', 'LocationController');
    Route::resource('package', 'PackageController');

    Route::get('current-acount/{client_id}/{months_ago}', 'CurrentAcountController@index');
    Route::post('current-acount/pago', 'CurrentAcountController@pago');
    Route::post('current-acount/nota-credito', 'CurrentAcountController@notaCredito');
    Route::post('current-acount/saldo-inicial', 'CurrentAcountController@saldoInicial');
    Route::delete('current-acount/{id}', 'CurrentAcountController@delete');

    Route::get('current-acount-payment-method', 'CurrentAcountPaymentMethodController@index');
});