<?php

use App\Http\Controllers\Message\SMS\SMSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Message API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Message API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Version the endpoints.
Route::prefix('v1')->group(function () {
    // Prefix the routes for bulk sms.
    Route::prefix('sms')->name('sms.')->group(function () {
        // Ensure only subscribed users can call these endpoints.
        Route::middleware(['subscribed:message'])->group(function () {
            // Prefix all routes with the project identifier.
            Route::prefix('{project:uuid}')->group(function () {
                Route::post('/analytics', [SMSController::class, 'analytics'])->name('analytics');
                Route::post('/analyse', [SMSController::class, 'analyse'])->name('analyse');
                Route::post('/send/{id}', [SMSController::class, 'send'])->name('send');
            });
        });
    });
});
