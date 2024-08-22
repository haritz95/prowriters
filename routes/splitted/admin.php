<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BidRequestController;
use App\Http\Controllers\Admin\SubmitWorkController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\OrderPaymentController;
use App\Http\Controllers\Admin\Business\UnitController;
use App\Http\Controllers\Admin\MessageThreadController;
use App\Http\Controllers\Admin\SystemLanguageController;
use App\Http\Controllers\Admin\TaskAttachmentController;
use App\Http\Controllers\Admin\TaskDiscussionController;
use App\Http\Controllers\Admin\Business\PackageController;
use App\Http\Controllers\Admin\Business\ServiceController;
use App\Http\Controllers\Admin\Business\SubjectController;
use App\Http\Controllers\Admin\Business\UrgencyController;
use App\Http\Controllers\Admin\QualityAssuranceController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\WalletAdjustmentController;
use App\Http\Controllers\Admin\Business\LanguageController;
use App\Http\Controllers\Admin\AIContentGeneratorController;
use App\Http\Controllers\Admin\Business\AssignmentController;
use App\Http\Controllers\Admin\Business\AuthorLevelController;
use App\Http\Controllers\Admin\Business\PaperFormatController;
use App\Http\Controllers\Admin\Business\AcademicLevelController;
use App\Http\Controllers\Admin\TaskInternalDiscussionController;
use App\Http\Controllers\Admin\PaymentPendingForApprovalController;
use App\Http\Controllers\Admin\Business\AdditionalServiceController;
use App\Http\Controllers\Admin\Business\GrammaticalPersonController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('dashboard/statistics', [DashboardController::class, 'statistics'])->name('dashboard.statistics');
Route::post('dashboard/graph/income', [DashboardController::class, 'incomeGraph'])->name('dashboard.graph.income');

Route::get('notifications', [DashboardController::class, 'notifications'])->name('notifications.index');

Route::get('customers/{user}/tasks', [CustomerController::class, 'tasks'])->name('customers.tasks');
Route::get('customers/{user}/payments', [CustomerController::class, 'payments'])->name('customers.payments');
Route::get('customers/{user}/wallet/transactions', [CustomerController::class, 'walletTransactions'])->name('customers.wallets.transactions');
Route::get('customers/{user}/wallet/adjust', [CustomerController::class, 'adjustWallet'])->name('customers.wallets.adjust.create');
Route::post('customers/{user}/wallet/adjust', [CustomerController::class, 'storeAdjustWallet'])->name('customers.wallets.adjust.store');

Route::get('customers/{user}/avatar', [CustomerController::class, 'avatar'])->name('customers.avatar');
Route::patch('customers/{user}/avatar', [CustomerController::class, 'updateAvatar'])->name('customers.avatar.update');

Route::get('customers/{user}/password', [CustomerController::class, 'password'])->name('customers.password');
Route::patch('customers/{user}/password', [CustomerController::class, 'updatePassword'])->name('customers.password.update');

Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');

Route::resource('customers', CustomerController::class)
    ->parameters([
        'customers' => 'user',
    ]);

Route::get('authors/{user}/tasks', [AuthorController::class, 'tasks'])->name('authors.tasks');
Route::get('authors/{user}/bills', [AuthorController::class, 'bills'])->name('authors.bills');
Route::get('authors/{user}/avatar', [AuthorController::class, 'avatar'])->name('authors.avatar');
Route::patch('authors/{user}/avatar', [AuthorController::class, 'updateAvatar'])->name('authors.avatar.update');
Route::get('authors/{user}/payments', [AuthorController::class, 'payments'])->name('authors.payments');
Route::get('authors/{user}/password', [AuthorController::class, 'password'])->name('authors.password');
Route::patch('authors/{user}/password', [AuthorController::class, 'updatePassword'])->name('authors.password.update');
Route::get('authors/search', [AuthorController::class, 'search'])->name('authors.search');

Route::resource('authors', AuthorController::class)->parameters([
    'authors' => 'user',
]);

Route::post('invoices/tasks/not-invoiced', [InvoiceController::class, 'notInvoicedTasks'])->name('invoices.tasks.not_invoiced');

Route::get('invoices/{invoice}/adjust-from-wallet', [InvoiceController::class, 'createAdjustPaymentFromWallet'])->name('invoices.adjust.from.wallet.create');
Route::post('invoices/{invoice}/adjust-from-wallet', [InvoiceController::class, 'storeAdjustPaymentAdjustFromWallet'])->name('invoices.adjust.from.wallet.store');

Route::resource('invoices', InvoiceController::class);
Route::resource('messageThreads', MessageThreadController::class);
Route::resource('messageThreads.messages', MessageController::class)->shallow();

Route::post('applicants/{applicant}/enroll', [ApplicantController::class, 'enroll'])->name('applicants.enroll');
Route::resource('applicants', ApplicantController::class)->except(['edit']);

Route::get('calendar', [TaskController::class, 'calendar'])->name('calendar');

Route::get('tasks/chooseService', [TaskController::class, 'servicesList'])->name('tasks.chooseService');
Route::get('tasks/services/{service}', [TaskController::class, 'create'])->name('tasks.create');
Route::post('tasks/services/{service}', [TaskController::class, 'store'])->name('tasks.store');
// Route::patch('tasks/services/{task}', [TaskController::class, 'update'])->name('tasks.update');

Route::prefix('tasks/{task}')
    ->name('tasks.')
    ->controller(TaskController::class)
    ->group(function () {

        Route::get('edit/status', 'editStatus')->name('edit.status');
        Route::patch('set/status', 'updateStatus')->name('update.status');

        Route::get('assign', 'editAssignee')->name('edit.assignee');
        Route::post('assign', 'updateAssignee')->name('update.assignee');

        Route::get('assign/editor', 'editEditor')->name('edit.editor');
        Route::post('assign/editor', 'updateEditor')->name('update.editor');

        Route::get('edit/dates', 'changeDates')->name('edit.dates');
        Route::patch('edit/dates', 'updateDates')->name('update.dates');

        Route::get('financial', 'financial')->name('financial.index');

        Route::post('archive', 'archive')->name('archive');
        Route::post('unarchive', 'unarchive')->name('unarchive');

        Route::get('follow', 'follow')->name('follow');
        Route::get('unFollow', 'unFollow')->name('unFollow');

    });

//Route::get('tasks/{task}/works', [SubmitWorkController::class, 'index'])->name('tasks.works.index');

Route::get('tasks/{task}/content', [SubmitWorkController::class, 'content'])->name('tasks.content');
Route::get('tasks/{task}/content/edit', [SubmitWorkController::class, 'editContent'])->name('tasks.content.edit');
Route::patch('tasks/{task}/content/update', [SubmitWorkController::class, 'updateContent'])->name('tasks.content.update');
Route::post('tasks/{task}/content/comment', [SubmitWorkController::class, 'storeContentComment'])->name('tasks.content.comment');

Route::delete('tasks/{task}/revisionRequests/{revisionRequest}', [SubmitWorkController::class, 'destroyRevisionRequest'])->name('tasks.revisionRequests.destroy');

Route::resource('tasks.works', SubmitWorkController::class);

Route::resource('tasks', TaskController::class)->except(['create', 'store']);
Route::resource('tasks.discussions', TaskDiscussionController::class)->shallow();
Route::resource('tasks.internal-discussions', TaskInternalDiscussionController::class)->shallow();
Route::resource('tasks.attachments', TaskAttachmentController::class);
Route::resource('tasks.payments', OrderPaymentController::class);

Route::resource('tasks.qa', QualityAssuranceController::class)
    ->only(['index', 'create', 'store', 'destroy']);

load_route('website_admin');

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
Route::resource('bidRequests', BidRequestController::class);

Route::resource('announcements', AnnouncementController::class);

Route::prefix('account')
    ->controller(AccountController::class)->group(function () {

    Route::get('contact', 'edit')->name('account.edit');
    Route::patch('update', 'update')->name('account.update');
    Route::get('location', 'location')->name('account.location');
    Route::patch('location', 'updateLocation')->name('account.location.update');

    Route::get('language', 'language')->name('account.language');
    Route::patch('language', 'updateLanguage')->name('account.language.update');

    Route::get('password', 'password')->name('account.password');
    Route::patch('password', 'updatePassword')->name('account.password.update');

    Route::get('avatar', 'avatar')->name('account.avatar');
    Route::patch('avatar', 'updateAvatar')->name('account.avatar.update');
});

Route::get('users/searchAll', [UserController::class, 'searchAll'])->name('users.searchAll');

// Super Admin Only
Route::middleware(['super_admin_only'])->group(function () {

    // Users/Admins
    Route::prefix('users/{user}')
        ->name('users.')
        ->controller(UserController::class)
        ->group(function () {

            Route::get('avatar', 'avatar')->name('avatar');
            Route::patch('avatar', 'updateAvatar')->name('avatar.update');

            Route::get('password', 'password')->name('password');
            Route::patch('password', 'updatePassword')->name('password.update');

            Route::get('permission', 'permission')->name('permission');
            Route::patch('permission', 'updatePermission')->name('permission.update');

        });

    Route::resource('users', UserController::class);

    // Discount Coupon
    Route::resource('coupons', CouponController::class);

    // Settings
    Route::prefix('settings')->name('settings.')
        ->group(function () {

            Route::resource('subscriptionPlans', SubscriptionPlanController::class);

            Route::resource('systemLanguages', SystemLanguageController::class);

            Route::controller(SettingsController::class)
                ->group(function () {

                    Route::get('/', 'general')->name('general');
                    Route::post('/', 'updateGeneral')->name('general.update');

                    Route::get('currency', 'currency')->name('currency');
                    Route::post('currency', 'updateCurrency')->name('currency.update');

                    Route::get('service', 'service')->name('service');
                    Route::post('service', 'updateService')->name('service.update');

                    Route::get('recaptcha', 'recaptcha')->name('recaptcha');
                    Route::post('recaptcha', 'updateRecaptcha')->name('recaptcha.update');

                    Route::get('googleTagManager', 'googleTagManager')->name('googleTagManager');
                    Route::post('googleTagManager', 'updateGoogleTagManager')->name('googleTagManager.update');

                    Route::get('app', 'appSettings')->name('appSettings');
                    Route::post('app', 'updateAppSettings')->name('appSettings.update');

                    Route::get('social-login', 'socialLogin')->name('social.login');
                    Route::post('social-login', 'updateSocialLogin')->name('social.login.update');

                    Route::get('social-links', 'socialLinks')->name('social.links');
                    Route::post('social-links', 'updateSocialLinks')->name('social.links.update');

                    Route::get('email', 'email')->name('email');
                    Route::post('email', 'updateEmail')->name('email.update');

                    Route::get('email/test', 'testEmail')->name('email.test');
                    Route::post('email/test', 'sendTestEmail')->name('email.test.update');

                    Route::get('cache/clear', 'cache')->name('cache');
                    Route::post('cache/clear', 'clearCache')->name('clear.cache');

                    Route::get('payment/gateways', 'paymentGateways')->name('payment.gateways');

                    Route::get('website/ui', 'websiteUi')->name('websiteUi');
                    Route::patch('website/ui', 'updateWebsiteUi')->name('websiteUi.update');

                });
        });

    // Payment Settings
    load_route('payment_settings');

    // Business
    Route::prefix('business')->group(function () {

        Route::get('/', [ServiceController::class, 'business'])->name('business');

        Route::prefix('services/{service}')->group(function () {

            Route::get('configurationHome', [ServiceController::class, 'configurationHome'])->name('services.configurationHome');
            Route::resource('assignments', AssignmentController::class);
            Route::resource('assignments.units', UnitController::class);

            Route::resource('packages', PackageController::class);

            Route::resource('academicLevels', AcademicLevelController::class);
            Route::resource('paperFormats', PaperFormatController::class);

            Route::resource('languages', LanguageController::class);
            Route::resource('grammaticalPeople', GrammaticalPersonController::class);
        });

        Route::resource('services', ServiceController::class);

        Route::resource('urgencies', UrgencyController::class);
        Route::resource('subjects', SubjectController::class);

        Route::resource('authorLevels', AuthorLevelController::class);
        Route::resource('additionalServices', AdditionalServiceController::class);
        //Route::resource('serviceLevels', ServiceLevelController::class);

    });

    // Transactions
    Route::prefix('transactions')->group(function () {

        Route::get('', [TransactionController::class, 'index'])->name('transactions.index');

        Route::resource('walletAdjustments', WalletAdjustmentController::class)->only(['index', 'show']);

        Route::get('payments/{payment}/download', [PaymentController::class, 'download'])->name('payments.download');

        Route::resource('payments', PaymentController::class)->except(['destroy', 'edit', 'update']);

        Route::prefix('pending')
            ->controller(PaymentPendingForApprovalController::class)
            ->group(function () {

                Route::get('approvals', 'index')
                    ->name('payments.pendingApprovals.index');

                Route::get('approvals/{pendingApproval}', 'show')
                    ->name('payments.pendingApprovals.show');

                Route::get('approvals/{pendingApproval}/approve', 'approve')
                    ->name('payments.pendingApprovals.approve');

                Route::get('approvals/{pendingApproval}/disapprove', 'disapprove')
                    ->name('payments.pendingApprovals.disapprove');
            });

    });

    // Bills from authors
    Route::prefix('bills')->controller(BillController::class)->group(function () {

        Route::get('', 'index')
            ->name('bills.index');

        Route::get('{bill}', 'show')
            ->name('bills.show');

        Route::post('{bill}/status/change/paid', 'markAsPaid')
            ->name('bills.mark.as_paid');

        Route::post('{bill}/status/change/unpaid', 'markAsUnpaid')
            ->name('bills.mark.as_unpaid');
    });

    // Reports
    Route::prefix('reports')
        ->name('reports.')
        ->controller(ReportController::class)->group(function () {

        Route::get('income', 'incomeStatement')->name('income.statement');
        Route::get('wallet/balance', 'walletBalance')->name('wallet.balance');

        Route::get('activityLog', 'activityLog')->name('activityLog.index');

    });

    Route::get('reports/ratings', [RatingController::class, 'index'])->name('reports.ratings.index');
});

// Ai Content Generator
Route::post('aIGeneratedContents/generate/{aIGeneratedContent?}', [AIContentGeneratorController::class, 'generateContent'])
    ->name('aIGeneratedContents.generate');
Route::resource('aIGeneratedContents', AIContentGeneratorController::class);
