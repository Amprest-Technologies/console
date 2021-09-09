<?php

use App\Http\Controllers\Pay\C2B\MPesaController;
use App\Http\Controllers\Pay\PaymentHandlerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pay API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Pay API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Prepare a web checkout.
Route::get('/', [PaymentHandlerController::class, 'pay'])->name('express.prepare');

// Payment Handler API Web checkout page.
Route::get('/express', [
    PaymentHandlerController::class, 'checkout'
])->name('express.checkout');

// Ensure only subscribed users can call these endpoints.
Route::prefix('c2b')->namespace('C2B')->name('c2b.')->group(function () {
    Route::prefix('mpesa')->name('mpesa.')->group(function () {
        // Listen for incoming validation transactions
        Route::post('validate', [MPesaController::class, 'valid'])->name('validate');

        // Listen for incoming transactions.
        Route::post('broadcast', [MPesaController::class, 'broadcast'])->name('broadcast');

        Route::middleware(['subscribed:pay'])->group(function () {
            // Prepare an M-Pesa Transaction.
            Route::post('prepare', [MPesaController::class, 'prepare'])->name('prepare');
            // Check an M-Pesa Transaction.
            Route::post('check', [MPesaController::class, 'check'])->name('check');
        });
    });
});
