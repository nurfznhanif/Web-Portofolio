<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\PortfolioPublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public Portfolio Route
Route::get('/', [PortfolioPublicController::class, 'index'])->name('portfolio.index');

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Dashboard Routes (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Profile Routes (Alias)
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');

    // Admin Portfolio Routes
    Route::prefix('admin/portfolio')->name('admin.portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/create', [PortfolioController::class, 'create'])->name('create');
        Route::post('/', [PortfolioController::class, 'store'])->name('store');
        Route::get('/{portfolio}', [PortfolioController::class, 'show'])->name('show');
        Route::get('/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('edit');
        Route::put('/{portfolio}', [PortfolioController::class, 'update'])->name('update');
        Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])->name('destroy');
    });

    // Admin Experience Routes
    Route::prefix('admin/experience')->name('admin.experience.')->group(function () {
        Route::get('/', [ExperienceController::class, 'index'])->name('index');
        Route::get('/create', [ExperienceController::class, 'create'])->name('create');
        Route::post('/', [ExperienceController::class, 'store'])->name('store');
        Route::get('/{experience}/edit', [ExperienceController::class, 'edit'])->name('edit');
        Route::put('/{experience}', [ExperienceController::class, 'update'])->name('update');
        Route::delete('/{experience}', [ExperienceController::class, 'destroy'])->name('destroy');
    });

    // Admin Skills Routes
    Route::prefix('admin/skill')->name('admin.skill.')->group(function () {
        Route::get('/', [SkillController::class, 'index'])->name('index');
        Route::get('/create', [SkillController::class, 'create'])->name('create');
        Route::post('/', [SkillController::class, 'store'])->name('store');
        Route::get('/{skill}/edit', [SkillController::class, 'edit'])->name('edit');
        Route::put('/{skill}', [SkillController::class, 'update'])->name('update');
        Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
