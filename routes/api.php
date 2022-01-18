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

// The users review ratings
Route::get(
    'show-review-rating/{id?}',
    [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'showReviewRating']
)->name('dashboard.show-review-rating');

/*********************
 *  Auth API Routes  *
 ********************/

Route::middleware('auth:sanctum')->group(function() {

    // Vehicle availability check for authenticated users
    Route::get(
        'vehicle-availability-auth/{id}',
        [App\Http\Controllers\Api\AvailabilityController::class, 'authCheck']
    )->name('vehicle.availability.auth');

    // A list of dates the user has bookings
    Route::get(
        'users-booking-dates',
        [App\Http\Controllers\Api\AvailabilityController::class, 'userBookedDates']
    )->name('');

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
    )->name('dashboard.create-drivers-license');

    // Show a users drivers license
    Route::get(
        'dashboard/show-drivers-license',
        [App\Http\Controllers\Api\Dashboard\DriversLicenseController::class, 'show']
    )->name('dashboard.show-drivers-license');

    /************************
     *  Vehicle API Routes  *
     ***********************/

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

    // Users completed reviews of hosts
    Route::get(
        'dashboard/host-users-reviews-complete',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofHostComplete']
    )->name('dashboard.host-users-reviews-complete');

    // Users uncompleted reviews of hosts
    Route::get(
        'dashboard/host-users-reviews-uncompleted',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofHostUncompleted']
    )->name('dashboard.host-users-reviews-incomplete');

    // Create a review of a host
    Route::post(
        'dashboard/create-review-of-host',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'createReviewOfHost']
    )->name('dashboard.create-review-of-host');

    // Users completed reviews of renters
    Route::get(
        'dashboard/renter-users-reviews-complete',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofRenterComplete']
    )->name('dashboard.renter-users-reviews-complete');

    // Users uncompleted reviews of renters
    Route::get(
        'dashboard/renter-users-reviews-uncompleted',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'ofRenterUncompleted']
    )->name('dashboard.renter-users-reviews-complete');

    // Create a review of a renter
    Route::post(
        'dashboard/create-review-of-renter',
        [App\Http\Controllers\Api\Dashboard\UserReviewController::class, 'createReviewOfRenter']
    )->name('dashboard.create-review-of-renter');

    /************************
     *  Booking API Routes  *
     ***********************/

    // Get a count of the users bookings and cancellations
    Route::get(
        'dashboard/booking-counts',
        [App\Http\Controllers\Api\Dashboard\UserBookingController::class, 'showBookingCounts']
    )->name('dashboard.booking-counts');

    // Get an index of the users bookings
    Route::get(
        'dashboard/booking-index',
        [App\Http\Controllers\Api\Dashboard\UserBookingController::class, 'bookingIndex']
    )->name('dashboard.booking-index');

    // Show a users booking
    Route::get(
        'dashboard/show-booking/{id}',
        [App\Http\Controllers\Api\Dashboard\UserBookingController::class, 'bookingShow']
    )->name('dashboard.booking-show');

    // Cancel a booking
    Route::delete(
        'dashboard/booking-delete/{id}',
        [App\Http\Controllers\Api\Dashboard\UserBookingController::class, 'bookingDelete']
    )->name('dashboard.booking-delete');

    // Show a users order
    Route::get(
        'dashboard/order-show/{id}',
        'App\Http\Controllers\Api\Dashboard\UserShowOrderController'
    )->name('dashboard.order-show');

    // Determine refund type and amount
    Route::get(
        'dashboard/booking-refund-amount/{id}',
        [App\Http\Controllers\Api\Dashboard\UserBookingController::class, 'showBookingRefund']
    )->name('dashboard.booking-refund-amount');

    /*************************
     *  Checkout API Routes  *
     ************************/

    // Checkout
    Route::post(
        'checkout',
        [App\Http\Controllers\Api\CheckoutController::class, 'store']
    )->name('checkout');
    
});
