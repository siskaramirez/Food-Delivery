<?php

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
Route::get('/profile', [PageController::class, 'profile'])->name('profile.page');

Route::get('/', [AuthController::class, 'landing'])->name('landing');
Route::get('/landing', [AuthController::class, 'landing'])->name('landing.page');

Route::get('/user/signup', [AuthController::class, 'showSignup'])->name('signup.page');
Route::post('/user/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::get('/user/signin', [AuthController::class, 'showSignin'])->name('signin.page');
Route::post('/user/signin', [AuthController::class, 'signin'])->name('signin.submit');


//route for admin

/*
Route::get('/blog', [UserController::class, 'getAllPosts']);
Route::get('/blog/create', [UserController::class, 'create']); 
Route::post('/blog/save', [UserController::class, 'save']);
*/
