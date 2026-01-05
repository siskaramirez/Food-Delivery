<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/home', [PageController::class, 'home'])->name('home.page');
Route::get('/menu', [PageController::class, 'menu'])->name('menu.page');
Route::get('/menu/{id}', [PageController::class, 'show'])->name('menu.detail');
Route::get('/about', [PageController::class, 'about'])->name('about.page');
Route::get('/orders', [PageController::class, 'orders'])->name('orders.page');
Route::get('/cart', [PageController::class, 'cart'])->name('cart.page');
Route::get('/cart/checkout', [PageController::class, 'checkout'])->name('cart.checkout');
Route::post('/order/store', [PageController::class, 'storeOrder'])->name('order.store');
Route::post('/order/cancel/{id}', [PageController::class, 'cancelOrder'])->name('order.cancel');

Route::get('/profile', [PageController::class, 'profile'])->name('profile.page');
Route::get('/profile/edit', [PageController::class, 'edit'])->name('profile.edit');
Route::post('/profile/edit', [PageController::class, 'update'])->name('profile.update');

Route::get('/', [AuthController::class, 'landing'])->name('landing');
Route::get('/landing', [AuthController::class, 'landing'])->name('landing.page');

Route::get('/user/signup', [AuthController::class, 'showSignup'])->name('signup.page');
Route::post('/user/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::get('/user/signin', [AuthController::class, 'showSignin'])->name('signin.page');
Route::post('/user/signin', [AuthController::class, 'signin'])->name('signin.submit');

Route::get('/payment', [AuthController::class, 'payment'])->name('payment.page');

//Route::get('/admin', [AuthController::class, 'admin'])->name('admin.page');
Route::get('/admin/home', [AdminController::class, 'home'])->name('home.admin');
Route::get('/admin/orders', [AdminController::class, 'orders'])->name('orders.admin');
Route::get('/admin/drivers', [AdminController::class, 'drivers'])->name('drivers.admin');

Route::get('/logout', [AdminController::class, 'logout'])->name('logout.submit');
Route::delete('/admin/user/delete/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
Route::put('/admin/order/update/{id}', [AdminController::class, 'updateOrder'])->name('order.update');

//Route::get('/forgot-password', [AuthController::class, 'showSignin'])->name('password.request');
//Route::post('/forgot-password', [AuthController::class, 'handleReset'])->name('password.update');


//route for admin


