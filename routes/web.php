<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\Auth\RegisterController as AuthRegisterController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\KirimEmailController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\TampilanController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


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
Route::post('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('verification.resend');

//tampilan HOMEPAGE
Route::get('/', [TampilanController::class, 'homepage'])->name('index.homepage');

//register customer
Route::post('/register', [AuthRegisterController::class, 'register'])->name('register');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('login'); // Ubah redirect ini
})->middleware(['auth', 'signed'])->name('verification.verify');

//login
Route::get('/login', [AuthLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthLoginController::class, 'login']);
// //tampilan login admin
// Route::get('/admin/login', [AdminController::class, 'index'])->name('login.admin');
// //submit login admin
// Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login.submit')->middleware('admin.redirect');

//hak akses
Route::middleware(['verified', 'auth'])->group(function () {
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/customer', [TampilanController::class, 'index'])->name('index.customer');
        Route::get('/customer/profile', [ProfileController::class, 'index'])->name('customer.profile');
        Route::get('/history/customer', [HistoryController::class, 'index'])->name('customer.history.index');
        Route::put('/customer/profile/update', [AuthRegisterController::class, 'update'])->name('customer.profile.update');
        Route::delete('/customer/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo.customer');
        Route::get('/customer/map', [MapController::class, 'index'])->name('customer.map.index');

        //device
        Route::get('/customer/device', [DeviceController::class, 'index'])->name('customer.device.index');
        Route::get('/device/create', [DeviceController::class, 'create'])->name('device.create');
        Route::post('/device', [DeviceController::class, 'store'])->name('device.store');
        Route::put('/device/{id_device}', [DeviceController::class, 'update'])->name('device.update');
        Route::delete('/device/{id}', [DeviceController::class, 'destroy'])->name('device.destroy');

    });

    Route::middleware(['role:admin'])->group(function() {
        Route::get('/admin', [TampilanController::class, 'admin'])->name('index.admin');
        Route::get('/admin/profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::put('/admin/profile/update', [AuthRegisterController::class, 'update'])->name('admin.profile.update');
        Route::delete('/admin/profile/delete', [ProfileController::class, 'deletePhoto'])->name('delete.photo');
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user');

        //device
        Route::get('/admin/device', [DeviceController::class, 'indexadmin'])->name('admin.device.index');
    });
});

//logout customer
Route::get('/logout', [AuthLoginController::class, 'logout'])->name('logout');

// //logout admin
// Route::post('/logout/admin', [AdminController::class, 'logoutadmin'])->name('logout.admin');



// Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
// ->middleware('guest')
// ->name('password.request');

// Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
// ->middleware('guest')
// ->name('password.email');

Route::get('kirim', [KirimEmailController::class, 'index']);




