<?php

use Illuminate\Support\Facades\Route;
use Modules\Interview\app\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
