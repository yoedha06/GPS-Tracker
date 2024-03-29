<?php

use App\Http\Controllers\api\HistoryController; //paka a kecil
use App\Http\Controllers\api\LocationController; //tidak terpakai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/history', [HistoryController::class, 'index']);
Route::post('/history/store', [HistoryController::class, 'store']);

Route::get('/location', [LocationController::class, 'index']);
Route::post('/location/store', [LocationController::class, 'store']);
