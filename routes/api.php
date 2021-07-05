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

/***********************
 *  Public API Routes  *
 ***********************/

// List vehicle makes
Route::get('vehicle-make/list', 'App\Http\Controllers\Api\VehicleMakeListController')
    ->name('vehicle-make.list');

// List top hosts
Route::get('top-hosts/list', 'App\Http\Controllers\Api\TopHostsListController')
    ->name('top-hosts.list');

// Get max and min price of vehicles
Route::get('vehicles/price-range', 'App\Http\Controllers\Api\VehiclePriceRangeController');

// Vehicle index
Route::get('vehicles-index', [App\Http\Controllers\Api\VehiclesController::class, 'index'])
    ->name('vehicles.index');

// Vehicle show
Route::get('vehicle-show/{id}', [App\Http\Controllers\Api\VehiclesController::class, 'show'])
    ->name('vehicle.show');

// Get reviews for a vehicle
Route::get('reviews-vehicle/{id}', [App\Http\Controllers\Api\ReviewController::class, 'vehicleReviews'])
    ->name('reviews.vehicle');
