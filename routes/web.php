<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\HistoryController as ApiHistoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Auth\RegisterController as AuthRegisterController;
use App\Http\Controllers\Auth\VerificationController as AuthVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KirimEmailController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\LocationController as AdminLocationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TampilanController;
use App\Http\Controllers\ValidationController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
Auth::routes(['verify' => true]);
//bawaan laravel
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

//tampilan HOMEPAGE
Route::get('/', [TampilanController::class, 'homepage'])->name('index.homepage');

//register customer
Route::post('/register', [AuthRegisterController::class, 'register'])->name('register');

Route::get('/email/resend', [AuthVerificationController::class, 'resend'])->name('verification.resend');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    // Cek apakah pengguna sudah login
    if (Auth::check()) {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Your email has been verified. Please log in.');
    } else {
        return redirect()->route('login')->with('success', 'Your email has been verified. Please log in.');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

//login
Route::get('/login', [AuthLoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthLoginController::class, 'login']);

//hak akses
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer', [TampilanController::class, 'index'])->name('index.customer');
        Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile');
        Route::get('/history/customer', [HistoryController::class, 'index'])->name('customer.history.index');
        Route::put('/customer/profile/update', [AuthRegisterController::class, 'update'])->name('customer.profile.update');
        Route::delete('/customer/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo.customer');
        Route::get('/customer/map', [HistoryController::class, 'map'])->name('customer.map.index');

        Route::get('/get-related-data/{deviceId}', [HistoryController::class, 'getRelatedData']);

        Route::get('/lastlocation',[MapController::class, 'lastloc'])->name('lastlocation');


        //device Customer

        Route::get('/customer/device', [DeviceController::class, 'index'])->name('customer.device.index');
        Route::get('/device/create', [DeviceController::class, 'create'])->name('device.create');
        Route::post('/device', [DeviceController::class, 'store'])->name('device.store');
        Route::put('/device/{id_device}', [DeviceController::class, 'update'])->name('device.update');
        Route::delete('/device/{id}', [DeviceController::class, 'destroy'])->name('device.destroy');
        Route::delete('/delete-photo/{id}', [DeviceController::class,'deletePhoto'])->name('delete-photo');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [TampilanController::class, 'admin'])->name('index.admin');
        Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/admin/profile/update', [AuthRegisterController::class, 'update'])->name('admin.profile.update');
        Route::delete('/admin/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo');
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user');
        Route::get('/admin/lastlocation',[LocationController::class, 'index'])->name('admin.lastlocation');

        //device Admin
        Route::get('/admin/device', [DeviceController::class, 'indexadmin'])->name('admin.device.index');
        Route::get('/admin/device/search', [DeviceController::class, 'search'])->name('admin.device.search');
    });
});

//logout customer
Route::get('/logout', [AuthLoginController::class, 'logout'])->name('logout');

// //logout admin
// Route::post('/logout/admin', [AdminController::class, 'logoutadmin'])->name('logout.admin');



// Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
// ->middleware('guest')
// ->name('password.request');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/password/reset/{token}/{email}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/validation', [ValidationController::class, 'index'])->name('validation');


 Route::get('/map', [MapController::class, 'index'])->name('map.index');

Route::get('/admin/get-related-data/{deviceId}', [HistoryController::class, 'fetchData'])->name('admin.fetch_data');
Route::get('/admin/map', [HistoryController::class, 'showMap'])->name('admin.map');
Route::get('/get-history-data', [HistoryController::class, 'getHistoryData'])->name('get-history-data');

Route::get('selectUsers', [HistoryController::class, 'selectUsers'])->name('selectUsers');
Route::get('selectDevice/{id}', [HistoryController::class, 'selectDevice'])->name('selectDevice');

Route::get('/filter-data', [HistoryController::class, 'filterData']);

//filter
// Route::get('/getHistoryByDevice/{deviceId}', [DeviceController::class, 'filter']);
Route::get('/gethistorybydevice/{deviceId}', [HistoryController::class, 'getHistoryByDevice'])->name('getHistoryByDevice');
Route::get('/history-filter', [HistoryController::class, 'filterByDate']);
Route::get('/getDevicesByUser', [DeviceController::class, 'filter']);
Route::get('/deviceuser/{id_device}', [MapController::class, 'deviceuser']);
Route::get('/autoselect/{userId}', [LocationController::class, 'autoselect']);



