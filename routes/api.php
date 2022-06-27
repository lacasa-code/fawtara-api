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


//auth routes
Route::middleware(['api'])->group(function () {
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
});


//user route 
Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', [App\Http\Controllers\Api\StatisticController::class, 'GetStatistic']);

    Route::get('/listcustomers', [App\Http\Controllers\Api\CustomersController::class, 'GetListCustomers']);
    Route::post('/showcustomer', [App\Http\Controllers\Api\CustomersController::class, 'ShowCustomer']);

});