<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('Signup');
});

Route::get('/signup', function () {
    return view('Signup');
})->name('signup');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/order', function () {
    return view('order');
})->name('orders');

Route::get('/packing', function () {
    return view('packing');
})->name('packing');

Route::get('/shipping', function () {
    return view('shipping');
})->name('shipping');

Route::get('/return', function () {
    return view('return');
})->name('return');

Route::get('/database', function () {
    return view('database');
})->name('database');

Route::get('/contactus', function () {
    return view('contactus');
})->name('contactus');

Route::get('/orderfloat', function () {
    return view('orderfloat');
})->name('orderfloat');

Route::get('/packingfloat', function () {
    return view('packingfloat');
})->name('packingfloat');

Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');