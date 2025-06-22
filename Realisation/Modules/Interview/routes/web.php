<?php

use Illuminate\Support\Facades\Route;
use Modules\Interview\app\Http\Controllers\QuestionController;
use Modules\Interview\app\Http\Controllers\DashboardController;
use Modules\Interview\app\Http\Controllers\EvaluationController;
use Modules\Interview\app\Http\Controllers\InterviewController;
use Modules\Interview\app\Http\Controllers\TemplateController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'dashboard'], function () {

    // Interview routes
    Route::group(['prefix' => 'interview'], function () {
        Route::get('/', [InterviewController::class, 'index'])->name('interview.index');
        Route::post('/import', [InterviewController::class, 'import'])->name('interview.import');
        Route::get('/export', [InterviewController::class, 'export'])->name('interview.export');
        Route::post('/', [InterviewController::class, 'store'])->name('interview.store');
        Route::put('/{interview}', [InterviewController::class, 'update'])->name('interview.update');
        Route::delete('/{interview}', [InterviewController::class, 'destroy'])->name('interview.destroy');
    });

    // Evaluation routes
    Route::group(['prefix' => 'evaluation'], function () {
        Route::post('/', [EvaluationController::class, 'store'])->name('evaluation.store');
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

    // Question routes
    Route::group(['prefix' => 'question'], function () {
        Route::get('/', [QuestionController::class, 'index'])->name('question.index');
        Route::post('/import', [QuestionController::class, 'import'])->name('question.import');
        Route::get('/export', [QuestionController::class, 'export'])->name('question.export');
        Route::post('/', [QuestionController::class, 'store'])->name('question.store');
        Route::put('/{type}', [QuestionController::class, 'update'])->name('question.update');
        Route::delete('/{type}', [QuestionController::class, 'destroy'])->name('question.destroy');
    });
});
