<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\SitemapController;
use Illuminate\Support\Facades\Route;

Route::group([], function () {

    if (env('DISABLE_WEBSITE')) {
        Route::get('/', [LoginController::class, 'showLoginForm'])->name('homepage')
            ->middleware('inertia');
    } else {

        Route::controller(HomeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('homepage');
                Route::get('faq', 'faq')->name('faq');
                Route::get('contact', 'contact')->name('contact');
                Route::post('contact', 'handleContact')->name('handle_email_query');
                Route::get('blog', 'blog')->name('blog');
                Route::get('blog/{category_or_post}', 'blogPostsByCategory')->name('blog.content');
                Route::get('switchLanguage', 'switchLanguage')->name('switchLanguage');
            });

        Route::controller(SitemapController::class)
            ->group(function () {
                Route::get('sitemap', 'index');
                Route::get('sitemap.xml', 'index')->name('sitemap.xml');
                Route::get('menu-top.xml', 'topMenu')->name('top_menu_sitemap.xml');
                Route::get('menu-bottom.xml', 'footerMenu')->name('footer_menu_sitemap.xml');
                Route::get('blog.xml', 'blog')->name('blog.xml');
            });
    }

});



// if (!is_website_disable()) {
//     if (is_single_language()) {
//         Route::get('{slug}', [HomeController::class, 'page'])->name('page');

//     } else {
//         Route::group(['prefix' => '{lc?}'], function () {
//             Route::get('{slug}', [HomeController::class, 'page'])->name('page');
//         });
//     }
// } 