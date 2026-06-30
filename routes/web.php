<?php

use Illuminate\Support\Facades\Route;


Route::get('signup', function () {
    return view('Signup');
});

Route::get('/', function () {
    return view('dashboard');
});

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


Route::get('/orderfloat', function () {
    return view('orderfloat');
})->name('orderfloat');