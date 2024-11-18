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

        Route::post('/user/preferences', [UserPreferenceController::class, '__invoke']);
        Route::get('/user/preferences', [UserPreferenceController::class, 'index']);

        Route::prefix('articles')->group(function () {
            Route::get('/{categoryId?}/{sourceId?}', [ArticleController::class, 'index']);

        });

        Route::get('/search-articles', [ArticleController::class, 'search']);
    });

    Route::get('news-cron', [ArticleController::class, 'downloadNews']);

});
