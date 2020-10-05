<?php

use App\Http\Controllers\Pay\PaymentHandlerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['subscribed:pay'])->group(function () {
    // Prepare a web checkout.
    Route::get('/', [PaymentHandlerController::class, 'pay'])->name('express.prepare');

    // Web checkout pages.
    Route::get('/express', [
        PaymentHandlerController::class, 'checkout'
    ])->name('express.checkout');
// });
