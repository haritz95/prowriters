<?php

use App\Models\Locale\SystemLanguage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Website\PostController;
use App\Http\Controllers\Admin\SystemTranslationController;
use App\Http\Controllers\Admin\Website\SystemPageController;
use App\Http\Controllers\Admin\Website\FaqCategoryController;
use App\Http\Controllers\Admin\Website\FaqQuestionController;
use App\Http\Controllers\Admin\Website\TestimonialController;
use App\Http\Controllers\Admin\Website\WebsiteMenuController;
use App\Http\Controllers\Admin\Website\WebsitePageController;
use App\Http\Controllers\Admin\Website\PostCategoryController;
use App\Http\Controllers\Admin\Website\HomePageSectionController;

Route::get('manage/content', function () {
    return inertia('Admin/Manage/Index', [
        'data' => [
            'title'     => __('Manage Website & Content'),
            'languages' => SystemLanguage::all(),
        ],
    ]);
})->name('manage.content');

Route::group(['prefix' => 'manage/content/{language}', 'as' => 'manage.content.'], function () {

    Route::get('hero', [HomePageSectionController::class, 'heroEdit'])
        ->name('index');

      
        Route::get('systemTranslations', [SystemTranslationController::class, 'index'])
        ->name('systemTranslations.index');
        
        Route::post('systemTranslations', [SystemTranslationController::class, 'update'])
        ->name('systemTranslations.update');
        
        Route::get('applyTranslation', [SystemTranslationController::class, 'applyTranslation'])
        ->name('systemTranslations.applyTranslation');
        
        Route::get('importTranslationTexts', [SystemTranslationController::class, 'importTranslationTexts'])
        ->name('systemTranslations.importTranslationTexts');

    Route::prefix('homepageSections')
        ->name('homepage.section.')
        ->controller(HomePageSectionController::class)->group(function () {

        Route::get('hero', 'heroEdit')->name('hero');
        Route::patch('hero', 'heroUpdate')->name('hero.update');

        Route::get('about', 'aboutEdit')->name('about');
        Route::patch('about', 'aboutUpdate')->name('about.update');

        Route::get('howItWorks', 'howItWorksEdit')->name('howItWorks');
        Route::patch('howItWorks', 'howItWorksUpdate')->name('howItWorks.update');

        Route::get('whyChooseUs', 'whyChooseUsEdit')->name('whyChooseUs');
        Route::patch('whyChooseUs', 'whyChooseUsUpdate')->name('whyChooseUs.update');

        Route::get('footer', 'footerEdit')->name('footer');
        Route::patch('footer', 'footerUpdate')->name('footer.update');

    });

    Route::prefix('systemPages')
        ->name('systemPages.')
        ->controller(SystemPageController::class)->group(function () {

        Route::get('home', 'homeEdit')->name('home');
        Route::patch('home', 'homeUpdate')->name('home.update');

        Route::get('blog', 'blogEdit')->name('blog');
        Route::patch('blog', 'blogUpdate')->name('blog.update');

        Route::get('faq', 'faqEdit')->name('faq');
        Route::patch('faq', 'faqUpdate')->name('faq.update');

        Route::get('contact', 'contactEdit')->name('contact');
        Route::patch('contact', 'contactUpdate')->name('contact.update');

        Route::get('login', 'loginEdit')->name('login');
        Route::patch('login', 'loginUpdate')->name('login.update');

        Route::get('registration', 'registrationEdit')->name('registration');
        Route::patch('registration', 'registrationUpdate')->name('registration.update');

        Route::get('forgotPassword', 'forgotPasswordEdit')->name('forgotPassword');
        Route::patch('forgotPassword', 'forgotPasswordUpdate')->name('forgotPassword.update');

        Route::get('authorApplication', 'authorApplicationEdit')->name('authorApplication');
        Route::patch('authorApplication', 'authorApplicationUpdate')->name('authorApplication.update');

    });

    Route::resource('faqCategories', FaqCategoryController::class);

    Route::resource('faqQuestions', FaqQuestionController::class);

    Route::resource('testimonials', TestimonialController::class);

    Route::resource('websitePages', WebsitePageController::class);

    Route::resource('websiteMenus', WebsiteMenuController::class);

    Route::resource('postCategories', PostCategoryController::class);
    Route::resource('posts', PostController::class);

    // Route::prefix('language/translate')->group(function () {

    //     Route::get('/', 'LanguageLineController@index')
    //         ->name('app_translate_texts');

    //     Route::post('/', 'LanguageLineController@datatable')
    //         ->name('datatable_app_translate_texts');

    //     Route::get('text/create', 'LanguageLineController@create')
    //         ->name('app_translate_texts_create');

    //     Route::post('text/create', 'LanguageLineController@store')
    //         ->name('app_translate_texts_store');

    //     Route::post('update', 'LanguageLineController@updateTranslation')
    //         ->name('update_app_translation');

    //     Route::post('export/json', 'LanguageLineController@exportToJson')
    //         ->name('export_json_app_translate_texts');
    // });

});
