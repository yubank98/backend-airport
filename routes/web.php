<?php

use App\Http\Controllers\AirlineController;
use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\AirportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FlightCatalogController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\PilotController;
use App\Http\Controllers\UserController;
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
    #Rutas especificas
    Route::post('/user/login',[UserController::class,'login']);
    Route::post('/user/getIdentity',[UserController::class,'getidentity']);
    Route::get('/airport/employee',[AirportController::class,'findEmployee']);
    #Rutas automaticas restfull
    Route::resource('/user',UserController::class,['except'=>['create','edit']]);
    Route::resource('/employee',EmployeeController::class,['except'=>['create','edit']]);
    Route::resource('/airport',AirportController::class,['except'=>['create','edit']]);
    Route::resource('/airline',AirlineController::class,['except'=>['create','edit']]);
    Route::resource('/airplane',AirplaneController::class,['except'=>['create','edit']]);
    Route::resource('/flightCatalog',FlightCatalogController::class,['except'=>['create','edit']]);
    Route::resource('/flight',FlightController::class,['except'=>['create','edit']]);
    Route::resource('/pilot',PilotController::class,['except'=>['create','edit','update']]);
});