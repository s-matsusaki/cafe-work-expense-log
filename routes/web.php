<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\WorkSessionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MonthlyReportApiController;
use App\Http\Controllers\MonthlyReportController;

Route::middleware('guest')->group(function() {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

    Route::get('/reports/monthly', [MonthlyReportController::class, 'index'])
        ->name('reports.monthly');

    Route::get('/api/reports/monthly', [MonthlyReportApiController::class, 'show'])
        ->name('api.reports.monthly');

    Route::resource('cafes', CafeController::class);
    Route::resource('work-sessions', WorkSessionController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('books', BookController::class);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');