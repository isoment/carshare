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
 **********************/

// Authenticated user details
Route::get('user-details', 'App\Http\Controllers\Api\Auth\UserDetailController')
    ->name('user-details');

// List vehicle makes
Route::get(
    'vehicle-make/list', 
    [App\Http\Controllers\Api\VehicleInformationController::class, 'vehicleMakeIndex']
)->name('vehicle-make.list');

// Get a list of models for a given make
Route::get(
    'vehicle-models/list',
    [App\Http\Controllers\Api\VehicleInformationController::class, 'vehicleModelIndex']
)->name('vehicle-model.list');

// List top hosts
Route::get('top-hosts/list', 'App\Http\Controllers\Api\TopHostsListController')
    ->name('top-hosts.list');

// Get max and min price of vehicles
Route::get('vehicles/price-range', 'App\Http\Controllers\Api\VehiclePriceRangeController');

// Vehicle index
Route::get(
    'vehicles-index', 
    [App\Http\Controllers\Api\VehiclesController::class, 'index']
)->name('vehicles.index');

// Vehicle show
Route::get(
    'vehicle-show/{id}', 
    [App\Http\Controllers\Api\VehiclesController::class, 'show']
)->name('vehicle.show');

// Check vehicle availability
Route::get(
    'vehicle-availability-guest/{id}', 
    [App\Http\Controllers\Api\AvailabilityController::class, 'guestCheck']
)->name('vehicle.availability.guest');

// Vehicle price
Route::get(
    'vehicle-price/{id}', 
    [App\Http\Controllers\Api\AvailabilityController::class, 'price']
)->name('vehicle.price');

// Get reviews for a vehicle
Route::get(
    'reviews-vehicle/{id}', 
    [App\Http\Controllers\Api\ReviewController::class, 'vehicleReviews']
)->name('reviews.vehicle');

// Reviews of a renter from hosts
Route::get(
    'reviews-from-hosts/{id}', 
    [App\Http\Controllers\Api\ReviewController::class, 'reviewsFromHosts']
)->name('reviews.from-host');

// Reviews of a host from renters
Route::get(
    'reviews-from-renters/{id}', 
    [App\Http\Controllers\Api\ReviewController::class, 'reviewsFromRenters']
)->name('reviews.from-renters');


/*********************
 *  Auth API Routes  *
 ********************/

Route::middleware('auth:sanctum')->group(function() {

    // Vehicle availability check for authenticated users
    Route::get(
        'vehicle-availability-auth/{id}',
        [App\Http\Controllers\Api\AvailabilityController::class, 'authCheck']
    )->name('vehicle.availability.auth');

    /**************************
     *  Dashboard API Routes  *
     *************************/

    // Change user avatar
    Route::post(
        'dashboard/update-avatar', 
        [App\Http\Controllers\Api\Dashboard\ProfileController::class, 'updateAvatar']
    )->name('dashboard.update-avatar');

    // Update user profile
    Route::put(
        'dashboard/update-profile',
        [App\Http\Controllers\Api\Dashboard\ProfileController::class, 'updateProfile']
    )->name('dashboard.update-profile');

    // Create a new drivers license
    Route::post(
        'dashboard/create-drivers-license',
        [App\Http\Controllers\Api\Dashboard\DriversLicenseController::class, 'create']
    )->name('dashboard.create-driverse-license');

    // Show a users drivers license
    Route::get(
        'dashboard/show-drivers-license',
        [App\Http\Controllers\Api\Dashboard\DriversLicenseController::class, 'show']
    )->name('dashboard.show-drivers-license');

    // Show a users vehicles
    Route::get(
        'dashboard/index-users-vehicles',
        [App\Http\Controllers\Api\Dashboard\UserVehicleController::class, 'index']
    )->name('dashboard.show-users-vehicles');

    // Create a new vehicle
    Route::post(
        'dashboard/create-users-vehicles',
        [App\Http\Controllers\Api\Dashboard\UserVehicleController::class, 'create']
    )->name('dashboard.create-users-vehicle');

    // Update a vehicle
    Route::post(
        'dashboard/update-users-vehicles/{id}',
        [App\Http\Controllers\Api\Dashboard\UserVehicleController::class, 'update']
    )->name('dashboard.update-users-vehicle');

    // Delete an existing vehicle image
    Route::delete(
        'dashboard/delete-vehicle-image',
        [App\Http\Controllers\Api\Dashboard\UserVehicleController::class, 'deleteImage']
    )->name('dashboard.delete-vehicle-image');

    /***********************
     *  Review API Routes  *
     **********************/

    Route::get(
        'dashboard/host-users-reviews-complete',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofHostComplete']
    )->name('dashboard.host-users-reviews-complete');

    Route::get(
        'dashboard/host-users-reviews-uncomplete',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofHostUncomplete']
    )->name('dashboard.host-users-reviews-incomplete');

    /*************************
     *  Checkout API Routes  *
     ************************/

    // Checkout
    Route::post(
        'checkout',
        [App\Http\Controllers\Api\CheckoutController::class, 'store']
    )->name('checkout');
    
});
