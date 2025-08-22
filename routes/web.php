<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

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
    return view('welcome');
});

// Route::get('/lelang/{id}', [CheckoutController::class, 'showItem'])->name('lelang.show');
// Route::post('/pay', [CheckoutController::class, 'createInvoice'])->name('pay'); 
// Route::get('/transactions', [CheckoutController::class, 'transactions'])->name('transactions.index');
// Route::get('/payment/result', [CheckoutController::class, 'result'])->name('payment.result');


// Route::get('/checkout', [CheckoutController::class, 'checkout']);
// Route::post('/webhook/xendit', 'CheckoutController@webhook');

// web.php
Route::get('/lelang/{id}', [CheckoutController::class, 'showItem'])->name('lelang.show');
// Route::post('/pay', [CheckoutController::class, 'createInvoice'])->name('pay');
Route::get('/transactions', [CheckoutController::class, 'transactions'])->name('transactions.index');
// Route::get('/payment/result', [CheckoutController::class, 'result'])->name('payment.result');
// Route::post('/xendit/webhook', [CheckoutController::class, 'webhook'])->name('payment.webhook');

Route::post('/payment', [CheckoutController::class, 'payment'])->name('payment');
Route::get('/notification/{id}', [CheckoutController::class, 'notification'])->name('notification');

