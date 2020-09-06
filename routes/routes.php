<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes for the package would go here
 */

Route::group([
    'layout' => config("support-ui.backend.template_to_extend", "layouts.app"),
    'prefix' => config("support-ui.backend.admin_route_prefix", ""),
    'middleware' => config("support-ui.backend.middleware.web.auth"),
    'as' => 'paksuco.',
], function () {
    Route::resource('/faq/categories', "\Paksuco\Support\Controllers\FAQCategoryController")
        ->names("faqcategory");
    Route::post("/faq/upload", "\Paksuco\Support\Controllers\FAQController@upload")
        ->name("faq.upload");
    Route::resource('/faq', "\Paksuco\Support\Controllers\FAQController")
        ->names("faq");
});

Route::group([
    'prefix' => 'api',
    'middleware' => config("support-ui.backend.middleware.api.guest"),
], function () {
    Route::apiResources([
        'faq' => \Paksuco\Support\API\FaqItemEndpoint::class,
        'faqcategory' => \Paksuco\Support\API\FaqCategoryEndpoint::class,
    ]);
});
