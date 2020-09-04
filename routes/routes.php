<?php

use Illuminate\Support\Facades\Route;

/**
 * Routes for the package would go here
 */

$webMiddleware = config("support-ui.backend.middleware.web", ["web", "auth"]);
$apiMiddleware = config("support-ui.backend.middleware.api", ["auth:api"]);

Route::group([
    'layout' => config("support-ui.backend.template_to_extend", "layouts.app"),
    'prefix' => config("support-ui.backend.admin_route_prefix", ""),
    'as' => 'paksuco.',
], function () use ($webMiddleware, $apiMiddleware) {
    Route::resource('/faq/categories', "\Paksuco\Support\Controllers\FAQCategoryController")
        ->names("faqcategory")->middleware($webMiddleware);
    Route::post("/faq/upload", "\Paksuco\Support\Controllers\FAQController@upload")
        ->name("faq.upload")->middleware($webMiddleware);
    Route::resource('/faq', "\Paksuco\Support\Controllers\FAQController")
        ->names("faq")->middleware($webMiddleware);
});

Route::group([
    'prefix' => 'api',
    'middleware' => $apiMiddleware
], function () {
    Route::apiResources([
        'faq' => \Paksuco\Support\API\FaqItemEndpoint::class,
        'faqcategory' => \Paksuco\Support\API\FaqCategoryEndpoint::class,
    ]);
});
