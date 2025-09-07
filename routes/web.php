<?php
// routes/web.php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PortfolioPublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController; // NEW: Add this import

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ProfileSettingController;
use App\Http\Controllers\Admin\AnalyticsController;

// NEW: Missing Admin Controllers
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\SocialLinkController;

/*
|--------------------------------------------------------------------------
| PUBLIC PORTFOLIO ROUTES
|--------------------------------------------------------------------------
*/

// Portfolio Public Routes
Route::get('/', [PortfolioPublicController::class, 'index'])->name('portfolio.home');
Route::get('/portfolio', [PortfolioPublicController::class, 'index'])->name('portfolio.index');

// Contact Form
Route::post('/contact', [PortfolioPublicController::class, 'contact'])->name('portfolio.contact');

// CV Download
Route::get('/download-cv', [PortfolioPublicController::class, 'downloadCV'])->name('portfolio.cv.download');

// Analytics API
Route::post('/api/analytics/track', [PortfolioPublicController::class, 'trackAnalytics'])->name('portfolio.analytics.track');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

// Dashboard route (protected by auth)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Profile routes (protected by auth) - UPDATED WITH PASSWORD MANAGEMENT
Route::middleware('auth')->group(function () {
    // Basic Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NEW: Password Management Routes
    Route::get('/profile/password', [PasswordController::class, 'edit'])->name('profile.password');
    Route::put('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');

    // NEW: AJAX Password Utilities
    Route::post('/api/password/check-strength', [PasswordController::class, 'checkStrength'])->name('password.check-strength');
    Route::post('/api/password/generate', [PasswordController::class, 'generatePassword'])->name('password.generate');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES - PROTECTED BY AUTH
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    /*
    |--------------------------------------------------------------------------
    | CONTENT MANAGEMENT
    |--------------------------------------------------------------------------
    */

    // Portfolio Management (EXISTING)
    Route::resource('portfolio', AdminPortfolioController::class);

    // Experience Management (EXISTING)
    Route::resource('experience', ExperienceController::class);

    // Skills Management (EXISTING)
    Route::resource('skills', SkillController::class);

    // Achievements Management (NEW)
    Route::resource('achievements', AchievementController::class);

    // Certifications Management (NEW)
    Route::resource('certifications', CertificationController::class);

    // Languages Management (NEW)
    Route::resource('languages', LanguageController::class);

    // Interests Management (NEW)
    Route::resource('interests', InterestController::class);

    // Social Links Management (NEW)
    Route::resource('social-links', SocialLinkController::class);

    /*
    |--------------------------------------------------------------------------
    | SETTINGS & CONFIGURATION
    |--------------------------------------------------------------------------
    */

    // Profile Settings (EXISTING)
    Route::get('/profile-settings', [ProfileSettingController::class, 'index'])->name('profile.settings');
    Route::post('/profile-settings', [ProfileSettingController::class, 'update'])->name('profile.settings.update');

    /*
    |--------------------------------------------------------------------------
    | ANALYTICS & REPORTS
    |--------------------------------------------------------------------------
    */

    // Analytics (EXISTING)
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');
        Route::get('/projects', [AnalyticsController::class, 'projects'])->name('projects');
        Route::get('/contact-forms', [AnalyticsController::class, 'contactForms'])->name('contact-forms');
    });

    /*
    |--------------------------------------------------------------------------
    | BULK OPERATIONS & UTILITIES (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    // Bulk Delete Routes (Optional - for mass operations)
    Route::delete('/portfolio/bulk-delete', [AdminPortfolioController::class, 'bulkDelete'])->name('portfolio.bulk-delete');
    Route::delete('/experience/bulk-delete', [ExperienceController::class, 'bulkDelete'])->name('experience.bulk-delete');
    Route::delete('/skills/bulk-delete', [SkillController::class, 'bulkDelete'])->name('skills.bulk-delete');
    Route::delete('/achievements/bulk-delete', [AchievementController::class, 'bulkDelete'])->name('achievements.bulk-delete');
    Route::delete('/certifications/bulk-delete', [CertificationController::class, 'bulkDelete'])->name('certifications.bulk-delete');
    Route::delete('/languages/bulk-delete', [LanguageController::class, 'bulkDelete'])->name('languages.bulk-delete');
    Route::delete('/interests/bulk-delete', [InterestController::class, 'bulkDelete'])->name('interests.bulk-delete');
    Route::delete('/social-links/bulk-delete', [SocialLinkController::class, 'bulkDelete'])->name('social-links.bulk-delete');

    // Toggle Featured/Active Status (Optional - for quick toggles)
    Route::patch('/portfolio/{portfolio}/toggle-featured', [AdminPortfolioController::class, 'toggleFeatured'])->name('portfolio.toggle-featured');
    Route::patch('/achievements/{achievement}/toggle-featured', [AchievementController::class, 'toggleFeatured'])->name('achievements.toggle-featured');
    Route::patch('/interests/{interest}/toggle-featured', [InterestController::class, 'toggleFeatured'])->name('interests.toggle-featured');
    Route::patch('/social-links/{socialLink}/toggle-active', [SocialLinkController::class, 'toggleActive'])->name('social-links.toggle-active');

    // Reorder Items (Optional - for drag & drop ordering)
    Route::post('/portfolio/reorder', [AdminPortfolioController::class, 'reorder'])->name('portfolio.reorder');
    Route::post('/experience/reorder', [ExperienceController::class, 'reorder'])->name('experience.reorder');
    Route::post('/skills/reorder', [SkillController::class, 'reorder'])->name('skills.reorder');
    Route::post('/achievements/reorder', [AchievementController::class, 'reorder'])->name('achievements.reorder');
    Route::post('/social-links/reorder', [SocialLinkController::class, 'reorder'])->name('social-links.reorder');

    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS FOR ADMIN (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    // AJAX/API endpoints for real-time data
    Route::prefix('api')->name('api.')->group(function () {

        // Statistics endpoints
        Route::get('/stats/overview', [DashboardController::class, 'getOverviewStats'])->name('stats.overview');
        Route::get('/stats/portfolio', [AdminPortfolioController::class, 'getStats'])->name('stats.portfolio');
        Route::get('/stats/experience', [ExperienceController::class, 'getStats'])->name('stats.experience');
        Route::get('/stats/skills', [SkillController::class, 'getStats'])->name('stats.skills');

        // Search endpoints
        Route::get('/search/portfolio', [AdminPortfolioController::class, 'search'])->name('search.portfolio');
        Route::get('/search/experience', [ExperienceController::class, 'search'])->name('search.experience');
        Route::get('/search/skills', [SkillController::class, 'search'])->name('search.skills');
        Route::get('/search/achievements', [AchievementController::class, 'search'])->name('search.achievements');
        Route::get('/search/certifications', [CertificationController::class, 'search'])->name('search.certifications');

        // Validation endpoints
        Route::post('/validate/portfolio', [AdminPortfolioController::class, 'validateField'])->name('validate.portfolio');
        Route::post('/validate/experience', [ExperienceController::class, 'validateField'])->name('validate.experience');
    });

    /*
    |--------------------------------------------------------------------------
    | EXPORT & IMPORT ROUTES (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    // Export routes
    Route::get('/export/portfolio', [AdminPortfolioController::class, 'export'])->name('export.portfolio');
    Route::get('/export/experience', [ExperienceController::class, 'export'])->name('export.experience');
    Route::get('/export/skills', [SkillController::class, 'export'])->name('export.skills');
    Route::get('/export/achievements', [AchievementController::class, 'export'])->name('export.achievements');
    Route::get('/export/certifications', [CertificationController::class, 'export'])->name('export.certifications');

    // Import routes
    Route::post('/import/portfolio', [AdminPortfolioController::class, 'import'])->name('import.portfolio');
    Route::post('/import/experience', [ExperienceController::class, 'import'])->name('import.experience');
    Route::post('/import/skills', [SkillController::class, 'import'])->name('import.skills');
});

/*
|--------------------------------------------------------------------------
| INCLUDE AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
