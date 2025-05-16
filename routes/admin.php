<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\MailSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/parameters', ParameterController::class)->only(['index', 'edit', 'update']);
    Route::get('/mail/settings', [MailSettingsController::class, 'index'])->name('mail.settings.index');
    Route::post('/mail/settings', [MailSettingsController::class, 'update'])->name('mail.settings.update');
});