<?php
// routes/web.php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PortfolioPublicController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ProfileSettingController;
use App\Http\Controllers\Admin\AnalyticsController;

// Portfolio Public Routes
Route::get('/', [PortfolioPublicController::class, 'index'])->name('portfolio.home');
Route::get('/portfolio', [PortfolioPublicController::class, 'index'])->name('portfolio.index');

// Contact Form
Route::post('/contact', [PortfolioPublicController::class, 'contact'])->name('portfolio.contact');

// CV Download
Route::get('/download-cv', [PortfolioPublicController::class, 'downloadCV'])->name('portfolio.cv.download');

// Analytics API
Route::post('/api/analytics/track', [PortfolioPublicController::class, 'trackAnalytics'])->name('portfolio.analytics.track');

// Dashboard route (protected by auth)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Routes (protected by auth)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Portfolio Management
    Route::resource('portfolio', AdminPortfolioController::class);

    // Experience Management
    Route::resource('experience', ExperienceController::class);

    // Skills Management
    Route::resource('skills', SkillController::class);

    // Profile Settings
    Route::get('/profile-settings', [ProfileSettingController::class, 'index'])->name('profile.settings');
    Route::post('/profile-settings', [ProfileSettingController::class, 'update'])->name('profile.settings.update');

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');
        Route::get('/projects', [AnalyticsController::class, 'projects'])->name('projects');
        Route::get('/contact-forms', [AnalyticsController::class, 'contactForms'])->name('contact-forms');
    });
});

// Profile routes (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
