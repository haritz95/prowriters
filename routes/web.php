<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\HomeController;

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

load_route('social_login');
load_route('installer');
load_route('core');

if (!is_website_disable()) {
    if (is_single_language()) {
        load_route('website');

    } else {
        Route::group(['prefix' => '{lc?}'], function () {
            load_route('website');
        });
    }
} else {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('homepage')
        ->middleware('inertia');
}




if (!is_website_disable()) {
    if (is_single_language()) {
        Route::get('{slug}', [HomeController::class, 'page'])->name('page');

    } else {
        Route::group(['prefix' => '{lc?}'], function () {
            Route::get('{slug}', [HomeController::class, 'page'])->name('page');
        });
    }
} 

