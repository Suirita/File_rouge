<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Modules\Branch\app\Http\Controllers\BranchController;

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'dashboard/branch', 'middleware' => 'auth', 'verified'], function () {
    Route::get('/', [BranchController::class, 'index'])->name('branch');
    Route::get('/export', [BranchController::class, 'export'])->name('branch.export');
    Route::post('/import', [BranchController::class, 'import'])->name('branch.import');
    Route::post('/', [BranchController::class, 'store'])->name('branch.store');
    Route::put('/{branch}', [BranchController::class, 'update'])->name('branch.update');
    Route::delete('/{branch}', [BranchController::class, 'destroy'])->name('branch.destroy');
});
