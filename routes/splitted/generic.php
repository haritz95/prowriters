<?php

use App\Http\Controllers\Common\RedirectController;
use App\Http\Controllers\NotificationController;

Route::get('common/redirect/{order}', [RedirectController::class, 'order'])->name('orders.show');

Route::prefix('notifications')->group(function () {

    Route::get('count', [NotificationController::class, 'push_notification'])
        ->name('get_push_notification_count');

    Route::get('unread', [NotificationController::class, 'get_unread_notifications'])
        ->name('get_unread_notifications');

    Route::get('notifications/redirect/{id}', [NotificationController::class, 'redirect_url'])
        ->name('notification_redirect_url');
    
    Route::get('all', [NotificationController::class, 'index'])
        ->name('notifications_index'); 

    Route::post('mark/read/all', [NotificationController::class, 'mark_all_notification_as_read'])
        ->name('notification_all_mark_as_read');
});

