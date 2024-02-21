<?php

use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Auth\RegisterController as AuthRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TampilanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//bawaan laravel ui
Auth::routes();
//bawaan laravel
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//tampilan HOMEPAGE
Route::get('/',[TampilanController::class, 'homepage'])->name('index.homepage');


Route::post('/register',[AuthRegisterController::class, 'register'])->name('register')->middleware('auth.redirectIfNotLoggedIn');
Route::get('/login', [AuthLoginController::class, 'showLoginForm'])->name('login');


//admin
Route::get('/admin',[TampilanController::class, 'admin'])->name('index.admin');
Route::get('/admin/profile',[ProfileController::class, 'admin'])->name('admin.profile');
Route::get('/customer/profile',[ProfileController::class, 'customer'])->name('customer.profile');


Route::middleware('auth')->group(function () {
    Route::get('/customer', [TampilanController::class, 'index'])->name('index.customer');
});

Route::get('/logout', [AuthLoginController::class, 'logout'])->name('logout');




