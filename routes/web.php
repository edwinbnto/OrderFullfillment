<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    return view('Signup');
});

Route::get('/signup', function () {
    return view('Signup');
})->name('signup');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/order', [OrderController::class, 'index'])->name('orders');

Route::get('/orders', [OrderController::class, 'orders'])->name('orders');
Route::get('/packing', [OrderController::class, 'packing'])->name('packing');

Route::post('/orders/{id}/prepare', [OrderController::class, 'prepare'])->name('orders.prepare');

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

