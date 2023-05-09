<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:api'])->group(function () {
    Route::controller(ProductController::class)->group(function () {
        Route::prefix('product/')->group(function () {
            Route::post('list', 'index');
            Route::post('add', 'store');
            Route::get('edit/{id}', 'edit');
            Route::post('edit/{id}', 'update');
        });
    });
});
