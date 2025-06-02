<?php

use Illuminate\Support\Facades\Route;
use Modules\Interview\app\Http\Controllers\QuestionController;
use Modules\Interview\app\Http\Controllers\DashboardController;
use Modules\Interview\app\Http\Controllers\TemplateController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'dashboard'], function () {

    // Question routes
    Route::group(['prefix' => 'question'], function () {
        Route::get('/', [QuestionController::class, 'index'])->name('question.index');
        Route::post('/import', [QuestionController::class, 'import'])->name('question.import');
        Route::get('/export', [QuestionController::class, 'export'])->name('question.export');
        Route::post('/', [QuestionController::class, 'store'])->name('question.store');
        Route::put('/{type}', [QuestionController::class, 'update'])->name('question.update');
        Route::delete('/{type}', [QuestionController::class, 'destroy'])->name('question.destroy');
    });

    // Template routes
    Route::group(['prefix' => 'template'], function () {
        Route::get('/', [TemplateController::class, 'index'])->name('template.index');
        Route::post('/import', [TemplateController::class, 'import'])->name('template.import');
        Route::get('/export', [TemplateController::class, 'export'])->name('template.export');
        Route::post('/', [TemplateController::class, 'store'])->name('template.store');
        Route::put('/{template}', [TemplateController::class, 'update'])->name('template.update');
        Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('template.destroy');
    });
});
