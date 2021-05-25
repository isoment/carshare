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

Route::get('vehicle-make/list', 'App\Http\Controllers\Api\VehicleMakeListController')
    ->name('vehicle-make.list');

Route::get('top-hosts/list', 'App\Http\Controllers\Api\TopHostsListController')
    ->name('top-hosts.list');
