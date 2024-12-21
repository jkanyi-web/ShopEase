<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

Route::middleware('api')->group(function () {
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::post('/remove', [CartController::class, 'removeFromCart']);
        Route::get('/', [CartController::class, 'viewCart']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'placeOrder']);
        Route::get('/', [OrderController::class, 'viewOrders']);
    });

    // Product routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
    });

    // Authenticated user route
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});
