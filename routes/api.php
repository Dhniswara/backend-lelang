<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NiplController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HargaBidController;
use App\Http\Controllers\LelangBarangController;
use App\Http\Controllers\NiplCheckoutController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {

    // Profile
    Route::post('/edit/user/{id}', [AuthController::class, 'update']);

    // Test User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Get User yang login
    Route::get('/user/isLogin', function (Request $request) {
        return $request->user();
    });

    // Menampilkan barang untuk user
    Route::prefix('lelang-barang')->group(function () {
        Route::get('/', [LelangBarangController::class, 'index']);  
        Route::get('/{id}', [LelangBarangController::class, 'show']);
    });

    // Menampilkan category untuk user
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
    });

    // Menampilkan NIPL untuk user
    Route::prefix('nipl')->group(function () {
        Route::get('/', [NiplController::class, 'index']);
        Route::get('/{id}', [NiplController::class, 'show']);
    });

    // CRUD Kategori
    Route::middleware(['isadmin'])->prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // CRUD Barang Lelang
    Route::middleware(['isadmin'])->prefix('lelang-barang')->group(function () {
        Route::post('/', [LelangBarangController::class, 'store']);
        Route::put('/{id}/barang', [LelangBarangController::class, 'update']);
        Route::delete('/{id}/barang', [LelangBarangController::class, 'destroy']);
    });

    // Routing Buy nipl
    Route::prefix('nipl')->group(function () {
        // Beli NIPL (buat invoice Xendit)
        Route::post('/buy', [NiplCheckoutController::class, 'buyNipl']);

        // Webhook notifikasi Xendit (status pembayaran)
        Route::post('/notification/{id}', [NiplCheckoutController::class, 'notification']);

        Route::put('/{id}', [NiplController::class, 'update']);
        Route::delete('/{id}', [NiplController::class, 'destroy']);
    });

    // Harga bid / tawaran
    Route::prefix('harga-bid')->group(function () {
        Route::post('/', [HargaBidController::class, 'store']);
        Route::get('/', [HargaBidController::class, 'index']);
    });


    // XENDIT PAYMENT GATEWAY //

    // Barang

    // Detail barang
    Route::get('/checkout/item/{id}', [CheckoutController::class, 'showItem']);

    // Daftar transaksi user login
    Route::get('/checkout/transactions', [CheckoutController::class, 'transactions']);

    // Buat pembayaran (generate invoice)
    Route::post('/checkout/payment', [CheckoutController::class, 'payment']);
});

Route::post('/notification/webhook', [WebhookController::class, 'handle']);