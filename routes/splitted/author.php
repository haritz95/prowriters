<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Author\BidController;
use App\Http\Controllers\Author\TaskController;
use App\Http\Controllers\Author\RatingController;
use App\Http\Controllers\Author\AccountController;
use App\Http\Controllers\Author\MessageController;
use App\Http\Controllers\Author\FindWorkController;
use App\Http\Controllers\Author\DashboardController;
use App\Http\Controllers\Author\BidRequestController;
use App\Http\Controllers\Author\SubmitWorkController;
use App\Http\Controllers\Author\AnnouncementController;
use App\Http\Controllers\Author\MessageThreadController;
use App\Http\Controllers\Author\PaymentRequestController;
use App\Http\Controllers\Author\TaskAttachmentController;
use App\Http\Controllers\Author\TaskDiscussionController;
use App\Http\Controllers\Author\TaskInternalDiscussionController;

Route::get('/', function () {
    return redirect()->route('author.dashboard');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
Route::get('notifications', [DashboardController::class, 'notifications'])->name('notifications.index');

Route::prefix('tasks/{task}')
    ->name('tasks.')
    ->controller(TaskController::class)
    ->group(function () {

        Route::get('content', 'content')->name('content');
        Route::patch('content', 'updateContent')->name('content.update');

        Route::post('start-working', 'startWorking')->name('start_working');
        Route::post('archive', 'archive')->name('archive');
        Route::post('unarchive', 'unarchive')->name('unarchive');

    });

Route::resource('tasks', TaskController::class)
    ->only(['index', 'show']);

Route::resource('tasks.discussions', TaskDiscussionController::class);
Route::resource('tasks.internal-discussions', TaskInternalDiscussionController::class);
Route::resource('tasks.attachments', TaskAttachmentController::class);

Route::resource('tasks.works', SubmitWorkController::class)
    ->only(['index', 'create', 'store']);

// Route::get('find-work', [TaskController::class,'findWork'])->name('find.work');

Route::prefix('find-work')->group(function () {

    Route::get('/', [FindWorkController::class, 'index'])
        ->name('find.work.index');

    Route::get('{task}', [FindWorkController::class, 'show'])
        ->name('find.work.show');

    Route::post('{task}/accept', [FindWorkController::class, 'accept'])
        ->name('find.work.accept');

});

Route::resource('messageThreads', MessageThreadController::class);
Route::resource('messageThreads.messages', MessageController::class)->shallow();

Route::resource('announcements', AnnouncementController::class)->only(['index', 'show']);

Route::get('calendar', [TaskController::class, 'calendar'])->name('calendar');

Route::resource('bidRequests', BidRequestController::class)->only(['index', 'show']);

Route::resource('bidRequests.bids', BidController::class)->only(['create', 'store', 'destroy'])->shallow();

Route::post('paymentRequests/{paymentRequest}/archive', [PaymentRequestController::class, 'archive'])->name('paymentRequests.archive');
Route::post('paymentRequests/{paymentRequest}/unarchive', [PaymentRequestController::class, 'unarchive'])->name('paymentRequests.unarchive');
Route::resource('paymentRequests', PaymentRequestController::class)->except(['destroy']);

Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index');

Route::prefix('account')
    ->name('account.')
    ->controller(AccountController::class)->group(function () {

    Route::get('profile', 'profile')->name('profile');

    Route::get('contact', 'edit')->name('edit');
    Route::patch('update', 'update')->name('update');

    Route::get('location', 'location')->name('location');
    Route::patch('location', 'updateLocation')->name('location.update');

    Route::get('language', 'language')->name('language');
    Route::patch('language', 'updateLanguage')->name('language.update');

    Route::get('bio', 'bio')->name('bio');
    Route::patch('bio', 'updateBio')->name('bio.update');

    Route::get('skill', 'skill')->name('skill');
    Route::patch('skill', 'updateSkill')->name('skill.update');

    Route::get('password', 'password')->name('password');
    Route::patch('password', 'updatePassword')->name('password.update');

    Route::get('avatar', 'avatar')->name('avatar');
    Route::patch('avatar', 'updateAvatar')->name('avatar.update');

    Route::get('payment', 'paymentSettings')->name('payment.settings');
    Route::patch('payment', 'updatePaymentSettings')->name('payment.settings.update');

    Route::get('language', 'language')->name('language');
    Route::patch('language', 'updateLanguage')->name('language.update');
});
