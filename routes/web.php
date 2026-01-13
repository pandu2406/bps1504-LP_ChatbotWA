<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/admin/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware('auth')->prefix('admin')->group(function () {
    // Dashboard - Super Admin only
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware('role:super_admin')
        ->name('admin.dashboard');

    // Training Monitor - All authenticated users
    Route::get('/training-monitor', [App\Http\Controllers\TrainingMonitorController::class, 'index'])
        ->name('admin.training.index');
    Route::get('/training-stats', [App\Http\Controllers\TrainingMonitorController::class, 'stats'])
        ->name('admin.training.stats');

    // Knowledge Base - View for all
    Route::get('/knowledge-base', [App\Http\Controllers\AiKnowledgeBaseController::class, 'index'])
        ->name('knowledge-base.index');

    // Knowledge Base - CRUD only for Super Admin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/knowledge-base/create', [App\Http\Controllers\AiKnowledgeBaseController::class, 'create'])
            ->name('knowledge-base.create');
        Route::post('/knowledge-base', [App\Http\Controllers\AiKnowledgeBaseController::class, 'store'])
            ->name('knowledge-base.store');
        Route::get('/knowledge-base/{knowledge_base}/edit', [App\Http\Controllers\AiKnowledgeBaseController::class, 'edit'])
            ->name('knowledge-base.edit');
        Route::put('/knowledge-base/{knowledge_base}', [App\Http\Controllers\AiKnowledgeBaseController::class, 'update'])
            ->name('knowledge-base.update');
        Route::delete('/knowledge-base/{knowledge_base}', [App\Http\Controllers\AiKnowledgeBaseController::class, 'destroy'])
            ->name('knowledge-base.destroy');
    });
});
