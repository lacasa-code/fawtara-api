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
    Route::get('/profile', [App\Http\Controllers\Api\AuthController::class, 'userProfile']);
    Route::post('/update/profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);

});


//Customer route 
Route::middleware(['auth'])->group(function () {
    Route::get('/statistics', [App\Http\Controllers\Api\StatisticController::class, 'GetStatistic']);

    Route::post('/add/customer', [App\Http\Controllers\Api\CustomersController::class, 'AddCustomer']);
    Route::post('/update/customer/{id}', [App\Http\Controllers\Api\CustomersController::class, 'updateCustomer']);
    Route::post('/delete/customer/{id}', [App\Http\Controllers\Api\CustomersController::class, 'delete']);
    Route::get('/searc/customers', [App\Http\Controllers\Api\CustomersController::class, 'search']);

    Route::get('/listcustomers', [App\Http\Controllers\Api\CustomersController::class, 'GetListCustomers']);
    Route::post('/showcustomer', [App\Http\Controllers\Api\CustomersController::class, 'ShowCustomer']);
    
    Route::post('/add/car', [App\Http\Controllers\Api\CarController::class, 'AddCar']);
    Route::post('/update/car/{id}', [App\Http\Controllers\Api\CarController::class, 'updateCar']);
    Route::post('/delete/car/{id}', [App\Http\Controllers\Api\CarController::class, 'delete']);

});

//car routes
