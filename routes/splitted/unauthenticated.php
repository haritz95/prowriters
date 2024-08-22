<?php

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Customer\InvoiceController;

Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])
    ->name('public.invoices.show');

Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])
    ->name('public.invoices.download');

// Handle File Uploads and Downloads
Route::prefix('attachments')->group(function () {

    Route::get('{attachment}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');

    Route::post('upload', [AttachmentController::class, 'upload'])
        ->name('attachments.store');

    // Route::delete('upload', 'AttachmentController@remove');
});
// End of Handle File Uploads and Downloads

Route::group(['middleware' => ['guest']], function () {

    Route::get('author/register', [ApplicantController::class, 'create'])
        ->name('public.author.application.create');

    Route::post('author/register', [ApplicantController::class, 'store'])
        ->name('public.author.application.store');

       

});


Route::get('changeLanguage', function(Request $request){
   
    if($request->switchTo)
    {
        Setting::switchLanguage($request, $request->switchTo);
    }
    
    return redirect()->back();

})->name('public.changeLanguage');