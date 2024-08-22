<?php

use App\Http\Controllers\Auth\SocialLogin\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('social_login_google');

Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('handle_google_auth_callback');

// Route::get('auth/facebook', 'Auth\SocialLogin\FacebookController@redirectToFacebook')
//     ->name('social_login_facebook');
// Route::get('auth/facebook/callback', 'Auth\SocialLogin\FacebookController@handleFacebookCallback')
//     ->name('handle_facebook_auth_callback');

// Route::get('auth/twitter', 'Auth\SocialLogin\TwitterController@redirectToTwitter')
//     ->name('social_login_twitter');
// Route::get('auth/twitter/callback', 'Auth\SocialLogin\TwitterController@handleTwitterCallback')
//     ->name('handle_twitter_auth_callback');

// Route::get('auth/linkedin', 'Auth\SocialLogin\LinkedinController@redirectToLinkedin')
//     ->name('social_login_linkedin');
// Route::get('auth/linkedin/callback', 'Auth\SocialLogin\LinkedinController@handleLinkedinCallback')
//     ->name('handle_linkedin_auth_callback');
