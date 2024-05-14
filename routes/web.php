<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Auth\RegisterController as AuthRegisterController;
use App\Http\Controllers\Auth\VerificationController as AuthVerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Auth\PhoneVerificationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TampilanController;
use App\Http\Controllers\TypeNotifController;
use App\Http\Controllers\ValidationController;
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
    if (Auth::user()->email) {
        return view('auth.verify'); // Jika pengguna mendaftar dengan email
    } elseif (Auth::user()->phone) {
        return redirect()->route('email.verification.notice'); // Jika pengguna mendaftar dengan nomor telepon
    } else {
        abort(403, 'Unauthorized action.'); // Handle kondisi jika tidak ada informasi email atau nomor telepon
    }
})->middleware('auth')->name('verification.notice');

Route::get('/phone/verify/{token}', [PhoneVerificationController::class, 'verify'])->name('phone.verify');
Route::post('/loginWithToken', [PhoneVerificationController::class, 'loginWithToken'])->name('loginWithToken');

Route::post('/phone-verification/resend', [AuthVerificationController::class, 'resendPhoneVerification'])->name('phone.verification.resend');
Route::get('/phone/verify', function () {
    if (Auth::user()->phone) {
        return view('auth.verifyphone'); // Jika pengguna mendaftar dengan nomor telepon
    } elseif (Auth::user()->email) {
        return redirect()->route('verification.notice'); // Jika pengguna mendaftar dengan email
    } else {
        abort(403, 'Unauthorized action.'); // Handle kondisi jika tidak ada informasi email atau nomor telepon
    }
})->middleware('auth')->name('phone.verification.notice');

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
Route::middleware(['auth', 'ensureVerified'])->group(function () {
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer', [TampilanController::class, 'index'])->name('index.customer');
        Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile');
        Route::get('/history/customer', [HistoryController::class, 'index'])->name('customer.history.index');
        Route::put('/customer/profile/update', [AuthRegisterController::class, 'update'])->name('customer.profile.update');
        Route::delete('/customer/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo.customer');
        Route::get('/customer/map', [HistoryController::class, 'map'])->name('customer.map.index');
        Route::get('/get-related-data/{deviceId}', [HistoryController::class, 'getRelatedData']);
        Route::get('/customer/lastlocation', [MapController::class, 'lastloc'])->name('lastlocation');
        Route::get('/customer/notification', [NotificationController::class, 'index'])->name('customer.notification.index');
        Route::post('/customer/notification', [NotificationController::class, 'store'])->name('customer.notification.store');

        // //url send wa
        // Route::get('/send-history', [NotificationController::class, 'notificationtype'])->name('customer.notification.send-history');
        
        //device Customer
        Route::get('/customer/device', [DeviceController::class, 'index'])->name('customer.device.index');
        Route::get('/device/create', [DeviceController::class, 'create'])->name('device.create');
        Route::post('/device', [DeviceController::class, 'store'])->name('device.store');
        Route::put('/device/{id_device}', [DeviceController::class, 'update'])->name('device.update');
        Route::delete('/device/{id}', [DeviceController::class, 'destroy'])->name('device.destroy');
        Route::delete('/delete-photo/{id}', [DeviceController::class, 'deletePhoto'])->name('delete-photo');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [TampilanController::class, 'admin'])->name('index.admin');
        Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/admin/profile/update', [AuthRegisterController::class, 'update'])->name('admin.profile.update');
        Route::delete('/admin/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo');
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user');
        Route::get('/admin/map', [HistoryController::class, 'showMap'])->name('admin.map');
        Route::get('/admin/lastlocation', [LocationController::class, 'index'])->name('admin.lastlocation');

        //device Admin
        Route::get('/admin/device', [DeviceController::class, 'indexadmin'])->name('admin.device.index');
        Route::get('/admin/device/search', [DeviceController::class, 'search'])->name('admin.device.search');

        //admin settings
        Route::get('/admin/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::put('/admin/pengaturan/{id}', [SettingsController::class, 'updatepengaturan'])->name('pengaturan.update');
        Route::post('/admin/pengaturan/store', [SettingsController::class, 'storepengaturan'])->name('pengaturan.store');
        Route::put('/admin/about/{id}', [SettingsController::class, 'updateabout'])->name('about.update');
        Route::post('/admin/about/store', [SettingsController::class, 'storeabout'])->name('about.store');
        Route::post('/admin/team/store', [SettingsController::class, 'storeteam'])->name('team.store');
        Route::put('/admin/informasi/{id}', [SettingsController::class, 'informasi'])->name('informasi.update');
        Route::put('/admin/team1/{id}', [SettingsController::class, 'updateteam1'])->name('team1.update');
        Route::put('/admin/team2/{id}', [SettingsController::class, 'updateteam2'])->name('team2.update');
        Route::put('/admin/team3/{id}', [SettingsController::class, 'updateteam3'])->name('team3.update');
        Route::put('/admin/team4/{id}', [SettingsController::class, 'updateteam4'])->name('team4.update');
        Route::put('/admin/informasicontact/{id}', [SettingsController::class, 'updateinformasicontact'])->name('informasicontact.update');
        Route::post('/admin/informasicontact/store', [SettingsController::class, 'storeinformasicontact'])->name('informasicontact.store');
        Route::put('/admin/informasisosmed/{id}', [SettingsController::class, 'updateinformasisosmed'])->name('informasisosmed.update');
        Route::post('/admin/informasisosmed/store', [SettingsController::class, 'storeinformasisosmed'])->name('informasisosmed.store');
    });
});

//logout customer
Route::get('/logout', [AuthLoginController::class, 'logout'])->name('logout');

Route::post('/create-last-location', [MapController::class, 'createLastLocation'])->name('create.lastlocation');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::post('/forgot-password/phone', [ForgotPasswordController::class, 'sendResetLinkPhone'])->name('password.phone');

Route::get('/password/email/{token}/{email}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::get('/password/phone/{token}/{phone}', [ResetPasswordController::class, 'showResetForm'])->name('password.phone.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/validation', [ValidationController::class, 'index'])->name('validation');

Route::get('/validation-phone', [ValidationController::class, 'indexPhone'])->name('validation.phone');


Route::get('/map', [MapController::class, 'index'])->name('map.index');

Route::get('/admin/get-related-data/{deviceId}', [HistoryController::class, 'fetchData'])->name('admin.fetch_data');

Route::get('/get-history-data', [HistoryController::class, 'getHistoryData'])->name('get-history-data');

Route::get('/get-device-history/{deviceId}', [HistoryController::class, 'getDeviceHistory']);

Route::get('/filter-data', [HistoryController::class, 'filterData']);
Route::get('/customer/fetch-latest-data', [HistoryController::class, 'fetchLatestData']);
//filter Route::get('/fetch_geospatial_data', [MapController::class, 'fetchGeospatialData']);
// Route::get('/getHistoryByDevice/{deviceId}', [DeviceController::class, 'filter']);
Route::get('/gethistorybydevice/{deviceId}', [HistoryController::class, 'getHistoryByDevice'])->name('getHistoryByDevice');
Route::get('/history-filter', [HistoryController::class, 'filterByDate']);
Route::get('/getDevicesByUser', [DeviceController::class, 'filter']);
Route::get('/deviceuser/{id_device}', [MapController::class, 'deviceuser']);
Route::get('/autoselec\t/{userId}', [LocationController::class, 'autoselect']);


//lastlocation
Route::get('/lastlocation/{deviceId}', [MapController::class, 'getLastLocation']);
Route::get('/latestlocation/{deviceId}', [MapController::class, 'getLatestLocation']);



Route::get('/admin/lastlocation/{deviceId}', [LocationController::class, 'getDeviceHistory']);
Route::get('/admin/latestlocation/{deviceId}', [LocationController::class, 'getLatestLocation']);




//filter chart
Route::get('/chart', [TampilanController::class, 'customer']);
Route::get('/admin-chart', [TampilanController::class, 'grafikadmin']);

//map history
// Route::get('/customer/map', [HistoryController::class, 'updateMapData']);
Route::post('/filter-history', [HistoryController::class, 'filter'])->name('filter.history');


Route::get('/admin/map/filter', [HistoryController::class, 'filterByDeviceAndUser'])->name('admin.history.index');
Route::post('/admin/filter-history', [HistoryController::class, 'filterHistory'])->name('admin.filter.history');

Route::post('/customer/typenotif', [TypeNotifController::class, 'store'])->name('store.notiftype');
Route::post('/customer/notificationAuto', [NotificationController::class, 'NotificationAuto'])->name('customer.notifauto');
