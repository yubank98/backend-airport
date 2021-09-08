<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\EmployeeController;

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


Route::prefix('/api')->group(function(){
    /*Route::get('/employee',EmployeeController::class,['except'=>['create','edit']]);*/
    Route::resource('/airport',AirportController::class,['except'=>['create','edit']]);
});