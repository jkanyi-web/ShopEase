<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/products', function () {
    return view('products');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/orders', function () {
    return view('orders');
});
