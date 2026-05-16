<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\WorkSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('cafes', CafeController::class);

Route::resource('work-sessions', WorkSessionController::class);
