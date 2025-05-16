<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ParameterController;
use App\Http\Controllers\Admin\MailSettingsController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::location('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Campaign Routes
    Route::resource('campaigns', CampaignController::class);
    Route::get('/my-campaigns', [CampaignController::class, 'myCampaigns'])->name('my-campaigns');

    // Donation Routes
    Route::get('/campaigns/{campaign}/donate', [DonationController::class, 'create'])->name('donations.create');
    Route::post('/campaigns/{campaign}/donate', [DonationController::class, 'store'])->name('donations.store');
    Route::get('/campaigns/{campaign}/donations', [CampaignController::class, 'showDonations'])->name('campaigns.donations');
    Route::get('/campaigns/{campaign}/donations/{donation}/transactions/{transaction}/success', [DonationController::class, 'donationSuccess'])->name('donations.success');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/donations/history', [DonationController::class, 'index'])->name('donations.history');
    
    // Optional: Route for donation confirmation (if you create a dedicated page)
    // Route::get('/donations/{donation}/confirmation', [DonationController::class, 'showConfirmation'])->name('donations.confirmation');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/parameters', ParameterController::class)->only(['index', 'edit', 'update']);
    Route::get('/mail/settings', [MailSettingsController::class, 'index'])->name('mail.settings.index');
    Route::post('/mail/settings', [MailSettingsController::class, 'update'])->name('mail.settings.update');
});

require __DIR__.'/auth.php';