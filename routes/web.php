<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\WorkSessionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])
->name('dashboard');

Route::resource('cafes', CafeController::class);

Route::resource('work-sessions', WorkSessionController::class);

Route::resource('expenses', ExpenseController::class);