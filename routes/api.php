<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(
    ['prefix' => 'v1', 'namespace' => 'V1'],
    function () {
        require __DIR__.'/v1.php';
    }
);
