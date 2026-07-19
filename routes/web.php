<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\MaterialRequestController;

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

Route::post(
    '/packing/process/{id}',
    [PackingController::class, 'processOrder']
)->name('packing.process');

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

Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping');
Route::get('/shipping/{shipmentId}/drivers', [ShippingController::class, 'drivers'])->name('shipping.drivers');
Route::post('/shipping/{shipmentId}/assign-driver', [ShippingController::class, 'assignDriver'])->name('shipping.assign-driver');

Route::post('/material-requests', [MaterialRequestController::class, 'store']);