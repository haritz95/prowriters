<?php

use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['localization', 'inertia']], function () {

    load_route('unauthenticated');
    load_route('checkout');
    Auth::routes(['verify' => true]);
    load_route('email_verification');

    // Authenticated Users
    Route::group(['middleware' => ['auth', 'verified']], function () {

        load_route('generic');

        // For Customers
        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['user_type:' . UserType::CUSTOMER]], function () {
            Route::get('/', function () {
                return redirect()->route('customer.dashboard');
            });
            load_route('customer');
        });

        // // For Admins
        Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['user_type:' . UserType::ADMIN]], function () {
            load_route('admin');
        });

        // For Authors
        Route::group(['prefix' => 'author', 'as' => 'author.', 'middleware' => ['user_type:' . UserType::AUTHOR]], function () {
            load_route('author');
        });
    });
});

Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
