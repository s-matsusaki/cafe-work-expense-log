<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\WorkSessionController;
use App\Http\Controllers\ExpenseController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('cafes', CafeController::class);

Route::resource('work-sessions', WorkSessionController::class);

Route::resource('expenses', ExpenseController::class);