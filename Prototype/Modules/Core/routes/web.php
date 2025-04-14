<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
