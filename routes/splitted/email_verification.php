<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

//Route::get('email/verify', [LoginController::class, 'verifyEmail'])->middleware('auth')->name('verification.notice');

// Route::post('email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('verification_link_sent', true);

// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Forgot Password
// Route::get('forgot-password', [LoginController::class, 'forGotPassword'])->middleware('guest')->name('password.request');

//Route::post('forgot-password', [LoginController::class, 'handleForgotPasswordRequest'])->middleware('guest')->name('password.email');


// Route::get('reset-password/{token}', [LoginController::class, 'resetPassword'])->middleware('guest')->name('password.reset');

// Route::post('reset-password', [LoginController::class, 'handleResetPassword'])->middleware('guest')->name('password.update');

// End of Forgot Password


/* */
Route::get('/confirm-password', function () {
    return view('auth.confirm-password');
})->middleware('auth')->name('password.confirm');


Route::post('/confirm-password', function (Request $request) {
    if (! Hash::check($request->password, $request->user()->password)) {
        return back()->withErrors([
            'password' => ['The provided password does not match our records.']
        ]);
    }
 
    $request->session()->passwordConfirmed();
 
    return redirect()->intended();
})->middleware(['auth', 'throttle:6,1']);