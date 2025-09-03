<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    ProfileController,
    ProjectController,
    ExperienceController,
    SkillController,
    AuthController,
    DashboardController
};

// Public routes
Route::get('/', function () {
    return Inertia::render('Portfolio/Index', [
        'profile' => \App\Models\Profile::first(),
        'projects' => \App\Models\Project::orderBy('featured', 'desc')->get(),
        'experiences' => \App\Models\Experience::orderBy('start_date', 'desc')->get(),
        'skills' => \App\Models\Skill::all(),
    ]);
})->name('portfolio');

// API endpoints for AJAX calls
Route::prefix('api')->group(function () {
    Route::get('/profile', function () {
        return response()->json(\App\Models\Profile::first());
    });
    Route::get('/projects', function () {
        return response()->json(\App\Models\Project::orderBy('featured', 'desc')->get());
    });
    Route::get('/experiences', function () {
        return response()->json(\App\Models\Experience::orderBy('start_date', 'desc')->get());
    });
    Route::get('/skills', function () {
        return response()->json(\App\Models\Skill::all());
    });
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    })->name('login')->middleware('guest');

    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', DashboardController::class)->name('admin.dashboard');

        // Profile Management
        Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');

        // Projects Management
        Route::resource('projects', ProjectController::class)->names([
            'index' => 'admin.projects.index',
            'create' => 'admin.projects.create',
            'store' => 'admin.projects.store',
            'show' => 'admin.projects.show',
            'edit' => 'admin.projects.edit',
            'update' => 'admin.projects.update',
            'destroy' => 'admin.projects.destroy',
        ]);

        // Experiences Management
        Route::resource('experiences', ExperienceController::class)->names([
            'index' => 'admin.experiences.index',
            'create' => 'admin.experiences.create',
            'store' => 'admin.experiences.store',
            'show' => 'admin.experiences.show',
            'edit' => 'admin.experiences.edit',
            'update' => 'admin.experiences.update',
            'destroy' => 'admin.experiences.destroy',
        ]);

        // Skills Management
        Route::resource('skills', SkillController::class)->names([
            'index' => 'admin.skills.index',
            'create' => 'admin.skills.create',
            'store' => 'admin.skills.store',
            'show' => 'admin.skills.show',
            'edit' => 'admin.skills.edit',
            'update' => 'admin.skills.update',
            'destroy' => 'admin.skills.destroy',
        ]);
    });
});
