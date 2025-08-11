<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NiplController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HargaBidController;
use App\Http\Controllers\LelangBarangController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Test User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

    // Routing CRUD Barang Lelang
    Route::middleware(['isadmin'])->prefix('lelang-barang')->group(function () {
        Route::get('/', [LelangBarangController::class, 'index']);
        Route::get('/{id}', [LelangBarangController::class, 'show']);
        Route::post('/', [LelangBarangController::class, 'store']);
        Route::put('/{id}/barang', [LelangBarangController::class, 'update']);
        Route::delete('/{id}/barang', [LelangBarangController::class, 'destroy']);
    });

    // Routing nipl
    Route::prefix('nipl')->group(function () {
        Route::get('/', [NiplController::class, 'index']);
        Route::post('/', [NiplController::class, 'store']);
        Route::get('/{id}', [NiplController::class, 'show']);
        Route::put('/{id}', [NiplController::class, 'update']);
        Route::delete('/{id}', [NiplController::class, 'destroy']);

        Route::prefix('harga')->group(function () {
            Route::post('/', [HargaBidController::class, 'store']);
            Route::get('/', [HargaBidController::class, 'index']);
        });






        // // Midtrans: buat snap token (harus user terautentikasi)
        // Route::post('/payment/token', [PaymentController::class, 'token']);
        // // (Opsional) endpoint untuk cek status/refresh jika mau
        // Route::get('/payment/status/{order_id}', [PaymentController::class, 'status']);
    });

    // Webhook Midtrans: tidak memakai auth karena dipanggil oleh Midtrans
    // Route::post('/payment/notification', [PaymentController::class, 'notification']);
});
