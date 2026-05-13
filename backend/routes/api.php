<?php

use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\CaseStudyController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\HeroController;
use App\Http\Controllers\Api\V1\NavigationController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\ResourceController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SiteController;
use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::get('/site', [SiteController::class, 'index']);
    Route::get('/navigation', [NavigationController::class, 'index']);
    Route::get('/hero', [HeroController::class, 'index']);

    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('/services/categories', [ServiceController::class, 'categories']);

    Route::get('/case-studies', [CaseStudyController::class, 'index']);
    Route::get('/case-studies/categories', [CaseStudyController::class, 'categories']);
    Route::get('/case-studies/{slug}', [CaseStudyController::class, 'show']);

    Route::get('/about', [AboutController::class, 'index']);
    Route::get('/contact', [ContactController::class, 'info']);
    Route::post('/contact', [ContactController::class, 'submit'])->middleware('throttle:10,1');

    Route::get('/resources/publications', [ResourceController::class, 'publications']);
    Route::get('/resources/forms', [ResourceController::class, 'forms']);
    Route::get('/resources/news', [ResourceController::class, 'news']);

    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);
});
