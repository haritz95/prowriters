<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemController;

Route::prefix('install')
    
    ->controller(SystemController::class)->group(function (){

    Route::get('/', 'index');

    Route::get('/system-check', 'index')
    ->name('installer_page');

    Route::get('/database', 'database_information')
    ->name('run_installation_step_2_page');

    Route::post('/database', 'setup_database_connection')
    ->name('run_installation_step_2');

    Route::get('/database/connected', 'db_connected')
    ->name('db_connected');
    
    Route::post('/run', 'run_page')
    ->name('run_installation_step_4_page');

    Route::post('/setup/db', 'setup_database')
    ->name('run_installation_step_4');

    Route::get('/status', 'installation_result')
    ->name('installation_result');

    Route::get('/failed', 'installation_failed')
    ->name('installation_failed');

     Route::get('/download/{path}', 'download')
     ->name('download_error_log');

});