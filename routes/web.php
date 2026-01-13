<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [AuthController::class, 'landing'])->name('landing');
Route::get('/landing', [AuthController::class, 'landing'])->name('landing.page');

Route::get('/user/signup', [AuthController::class, 'showSignup'])->name('signup.page');
Route::post('/user/signup', [AuthController::class, 'signup'])->name('signup.submit');
Route::get('/user/signin', [AuthController::class, 'showSignin'])->name('signin.page');
Route::post('/user/signin', [AuthController::class, 'signin'])->name('signin.submit');

Route::get('/home', [PageController::class, 'home'])->name('home.page');
Route::get('/about', [PageController::class, 'about'])->name('about.page');
Route::get('/menu', [PageController::class, 'menu'])->name('menu.page');
Route::get('/menu/{id}', [PageController::class, 'show'])->name('menu.detail');
Route::get('/orders', [PageController::class, 'orders'])->name('orders.page');
Route::post('/order/store', [PageController::class, 'storeOrder'])->name('order.store');
Route::post('/order/cancel/{id}', [PageController::class, 'cancelOrder'])->name('order.cancel');
//Route::post('/order/delete/{id}', [PageController::class, 'deleteOrder'])->name('order.delete');
Route::get('/orders/history', [PageController::class, 'history'])->name('orders.history');
Route::get('/cart', [PageController::class, 'cart'])->name('cart.page');
Route::get('/cart/checkout', [PageController::class, 'checkout'])->name('cart.checkout');
Route::get('/profile', [PageController::class, 'profile'])->name('profile.page');
Route::get('/profile/edit', [PageController::class, 'edit'])->name('profile.edit');
Route::put('/profile/edit', [PageController::class, 'update'])->name('profile.update');
Route::get('/payment', [PageController::class, 'payment'])->name('payment.page');
Route::delete('/profile/delete/{id}', [PageController::class, 'deleteUser'])->name('profile.delete');

Route::get('/admin/home', [AdminController::class, 'home'])->name('home.admin');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout.submit');
Route::get('/admin/orders', [AdminController::class, 'orders'])->name('orders.admin');
Route::put('/admin/orders/update/{id}', [AdminController::class, 'updateOrder'])->name('order.update');
Route::get('/admin/menu', [AdminController::class, 'menu'])->name('menu.admin');
Route::patch('/admin/menu/add/{id}', [AdminController::class, 'addQuantity'])->name('menu.add');
Route::get('/admin/drivers', [AdminController::class, 'drivers'])->name('drivers.admin');
Route::patch('/admin/drivers/{license}', [AdminController::class, 'updateStatus'])->name('drivers.update');

//Route::get('/forgot-password', [AuthController::class, 'showSignin'])->name('password.request');
//Route::post('/forgot-password', [AuthController::class, 'handleReset'])->name('password.update');

