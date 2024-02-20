<?php

use App\Http\Controllers\Admin\UserController;
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
Route::get('/customer', function () {
    return view('customer.index');
});

Route::get('/admin/profile',[ProfileController::class, 'admin'])->name('admin.profile');
Route::get('/customer/profile',[ProfileController::class, 'customer'])->name('customer.profile');


Route::get('admin/users', [UserController::class, 'index'])->name('admin.user');
