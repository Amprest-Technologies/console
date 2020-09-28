<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Welcome page.
Route::get('/', fn () =>  view('welcome'));

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // User dashboard.
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
        ->name('dashboard');

    // Project details.
    Route::get('projects/{project}', [
        DashboardController::class, 'projectDetails'
    ])->name('dashboard.projects.show');

    // Subscribe to a new service form.
    Route::get('projects/{project}/subscribe/{service}', [
        DashboardController::class, 'subscribeToService'
    ])->name('dashboard.projects.subscribe');
});
