<?php

use App\Http\Controllers\V1\ArticleController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::middleware('api-log')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('categories', [CategoryController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout']);
        Route::patch('reset-password', [UserController::class, 'resetPassword']);

        Route::prefix('user')->group(function () {
            Route::post('/preferences', [UserPreferenceController::class, '__invoke']);
            Route::get('/preferences', [UserPreferenceController::class, 'index']);
            Route::get('/newsfeed', [UserPreferenceController::class, 'getNewsfeed']);
        });

        Route::prefix('articles')->group(function () {
            Route::get('/details/{articleId}', [ArticleController::class, 'view']);
            Route::get('/{categoryId?}/{sourceId?}', [ArticleController::class, 'index']);

        });
        Route::middleware('throttle:search-articles')->group(function () {
            Route::get('/search-articles', [ArticleController::class, 'search']);
        });
    });
});
