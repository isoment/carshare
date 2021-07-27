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

// Check vehicle availability
Route::get('vehicle-availability/{id}', [App\Http\Controllers\Api\AvailabilityController::class, 'check'])
    ->name('vehicle.availability.check');

// Vehicle price
Route::get('vehicle-price/{id}', [App\Http\Controllers\Api\AvailabilityController::class, 'price'])
    ->name('vehicle.price');

// Get reviews for a vehicle
Route::get('reviews-vehicle/{id}', [App\Http\Controllers\Api\ReviewController::class, 'vehicleReviews'])
    ->name('reviews.vehicle');


/**************************
 *  Dashboard API Routes  *
 *************************/

// All authenticated users can access these api endpoints
// Route::middleware('auth:sanctum')->group(function() {

    // Change user avatar
    Route::post(
        'dashboard/change-avatar', 
        [App\Http\Controllers\Api\Dashboard\ProfileController::class, 'updateAvatar']
    );

    // Update user profile
    Route::put(
        'dashboard/update-profile',
        [App\Http\Controllers\Api\Dashboard\ProfileController::class, 'updateProfile']
    );

// });


