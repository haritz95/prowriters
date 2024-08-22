<?php
Auth::routes(['verify' => true]);

// Authentication Routes...
Route::get('connexion', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('connexion', 'Auth\LoginController@login');
Route::post('deconnexion', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('inscription', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('inscription', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('mot-de-passe/reinitialiser', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('mot-de-passe/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('mot-de-passe/reinitialiser/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('mot-de-passe/reinitialiser', 'Auth\ResetPasswordController@reset');

// Confirm Password (added in v6.2)
Route::get('mot-de-passe/confirmer', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('mot-de-passe/confirmer', 'Auth\ConfirmPasswordController@confirm');

// Email Verification Routes...
Route::get('email/verifier', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verifier/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify'); // v6.x
/* Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
Route::post('email/renvoyer', 'Auth\VerificationController@resend')->name('verification.resend');