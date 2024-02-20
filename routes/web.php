<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('login');
});


Route::get('/admin', function () {
    return view('admin.index');
});
Route::get('/homepage', function () {
    return view('layout.homepage');
});
Route::get('/load', function () {
    return view('layout.load');
});

Route::post('/customer', [LoginController::class, 'index'])->name('customer');

Route::get('/admin/profile',[ProfileController::class, 'admin'])->name('admin.profile');
Route::get('/customer/profile',[ProfileController::class, 'customer'])->name('customer.profile');

Route::get('/login', [RegisterController::class, 'create'])->name('login');//halaman login dan register
Route::post('/register', [RegisterController::class, 'store'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'dologin'])->name('login');

Route::get('/register', [RegisterController::class, 'index'])->name('register');


Route::get('admin/user', [UserController::class, 'index'])->name('admin.user');
Route::post('user', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('user.store');


