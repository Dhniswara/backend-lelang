<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NiplController;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HargaBidController;
use App\Http\Controllers\MidtransController;
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

    // Profile
    Route::post('/edit/user/{id}', [AuthController::class, 'update']);

    // Test User
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);


    // Menampilkan barang untuk user
    Route::prefix('lelang-barang')->group(function () {
        Route::get('/', [LelangBarangController::class, 'index']);
        Route::get('/{id}', [LelangBarangController::class, 'show']);
    });

    // CRUD Kategori
    Route::middleware(['isadmin'])->prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);      
            Route::post('/', [CategoryController::class, 'store']);     
            Route::get('/{id}', [CategoryController::class, 'show']);   
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']); 
    });

    // CRUD Barang Lelang
    Route::middleware(['isadmin'])->prefix('lelang-barang')->group(function () {
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
    });

    // Harga bid / tawaran
    Route::prefix('harga-bid')->group(function () {
        Route::post('/', [HargaBidController::class, 'store']);
        Route::get('/', [HargaBidController::class, 'index']);
    });

    // Midtrans
    // Route::post('/midtrans/create', [MidtransController::class, 'createTransaction']);
    // Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler']);

    // Xendit
    // Route::post('/xendit/create-invoice', [XenditController::class, 'createInvoice']);
    // Route::post('/xendit/callback', [XenditController::class, 'callback']);
});
