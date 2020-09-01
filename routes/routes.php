<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes for the package would go here
 */

Route::group([
    'layout' => config("support-ui.backend.template_to_extend", "layouts.app"),
    'prefix' => config("support-ui.backend.admin_route_prefix", ""),
    'as' => 'paksuco.',
], function () {
    Route::resource('/faq/categories', "\Paksuco\Support\Controllers\FAQCategoryController")
        ->names("faqcategory")
        ->middleware(config("support-ui.backend.middleware", []));

    Route::resource('/faq', "\Paksuco\Support\Controllers\FAQController")
        ->names("faq")
        ->middleware(config("support-ui.backend.middleware", []));
});
