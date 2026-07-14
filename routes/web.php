<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('Signup');
});

Route::get('/signup', function () {
    return view('Signup');
})->name('signup');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::post('/orders/{id}/prepare', [OrderController::class, 'prepare'])->name('orders.prepare');
Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

Route::get('/packing', [PackingController::class, 'index'])->name('packing');

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

Route::post('/login',[LoginController::class,'login'])->name('login');

Route::get('/logout',[LoginController::class,'logout'])->name('logout');