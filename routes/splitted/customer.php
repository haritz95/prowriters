<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\TaskController;
use App\Http\Controllers\Customer\RatingController;
use App\Http\Controllers\Customer\AccountController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\BidRequestController;
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\Customer\SubscriptionController;
use App\Http\Controllers\Customer\SubmittedWorkController;
use App\Http\Controllers\Customer\TaskAttachmentController;
use App\Http\Controllers\Customer\TaskDiscussionController;
use App\Http\Controllers\Customer\AIContentGeneratorController;

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('dashboard', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
Route::get('notifications', [DashboardController::class, 'notifications'])->name('notifications.index');

Route::prefix('tasks/{task}')
    ->name('tasks.')
    ->controller(SubmittedWorkController::class)->group(function () {

    

    Route::post('content/comment', 'storeContentComment')
        ->name('content.comment');

    Route::post('content/accept', 'accept')
        ->name('content.accept');

    Route::get('revisions/create', 'createRequestForRevision')
        ->name('revisions.create');

    Route::post('revisions/create', 'storeRequestForRevision')
        ->name('revisions.store');

});

Route::prefix('tasks/{task}')
    ->name('tasks.')
    ->controller(TaskController::class)->group(function () {

    Route::get('details', 'details')
        ->name('details');

    Route::get('financial', 'financial')
        ->name('financial');

    Route::get('extend-deadline', 'extendDeadline')
        ->name('extend.deadline');

    Route::post('extend-deadline', 'storeExtendedDeadline')
        ->name('post.extend.deadline');

    Route::post('archive', 'archive')
        ->name('archive');

    Route::post('unarchive', 'unarchive')
        ->name('unarchive');

});

Route::get('bidRequests/services', [BidRequestController::class, 'servicesList'])->name('bidRequests.services');
Route::get('bidRequests/services/{service}', [BidRequestController::class, 'create'])->name('bidRequests.create');
Route::post('bidRequests/services/{service}', [BidRequestController::class, 'store'])->name('bidRequests.store');
Route::get('bidRequests', [BidRequestController::class, 'index'])->name('bidRequests.index');

Route::prefix('bidRequests/{bidRequest}')
    ->name('bidRequests.')
    ->controller(BidRequestController::class)->group(function () {

    Route::post('accept/{bid}', 'acceptBid')
        ->name('accept');

    Route::get('brief', 'brief')
        ->name('brief');

    Route::get('author/{user}', 'authorProfile')
        ->name('author');

});
Route::resource('bidRequests', BidRequestController::class)->only(['show', 'edit', 'destroy']);

Route::post('tasks/payLater/{service}', [TaskController::class, 'payLater'])->name('tasks.payLater');

Route::resource('tasks', TaskController::class)->only(['index', 'show', 'destroy']);
Route::resource('tasks.discussions', TaskDiscussionController::class);
Route::resource('tasks.attachments', TaskAttachmentController::class);

Route::resource('tasks.ratings', RatingController::class)
    ->only(['create', 'store']);

Route::get('invoices/{invoice}/pay', [InvoiceController::class, 'makePayment'])
    ->name('invoices.pay');

Route::resource('invoices', InvoiceController::class)->only(['index', 'show']);

// Route::resource('projects', ProjectController::class);

Route::get('payments/{payment}/download', [PaymentController::class, 'download'])
    ->name('payments.download');

Route::get('payments/pendingApprovals', [PaymentController::class, 'pendingApprovals'])
    ->name('payments.pendingApprovals');

Route::resource('payments', PaymentController::class)->only(['index', 'show']);

Route::prefix('transactions')->group(function () {

    Route::get('/', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('funds/create', [TransactionController::class, 'createFund'])
        ->name('transactions.funds.create');

    Route::post('funds/create', [TransactionController::class, 'storeFund'])
        ->name('transactions.funds.store');
});

Route::prefix('account')
    ->controller(AccountController::class)->group(function () {

    Route::get('general', 'edit')->name('account.edit');
    Route::patch('general', 'update')->name('account.update');

    Route::get('location', 'location')->name('account.location');
    Route::patch('location', 'updateLocation')->name('account.location.update');

    Route::get('password', 'password')->name('account.password');
    Route::patch('password', 'updatePassword')->name('account.password.update');

    Route::get('avatar', 'avatar')->name('account.avatar');
    Route::patch('avatar', 'updateAvatar')->name('account.avatar.update');

    Route::get('language', 'language')->name('account.language');
    Route::patch('language', 'updateLanguage')->name('account.language.update');
});

// Ai Content Generator
Route::post('aIGeneratedContents/generate/{aIGeneratedContent?}', [AIContentGeneratorController::class, 'generateContent'])
    ->name('aIGeneratedContents.generate');
Route::resource('aIGeneratedContents', AIContentGeneratorController::class);

Route::get('subscriptions/plans', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('subscriptions/plans/{subscriptionPlan}/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
Route::post('subscriptions/plans/{subscriptionPlan}/create', [SubscriptionController::class, 'store'])->name('subscriptions.store');