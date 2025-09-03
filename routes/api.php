<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ProjectController;

// Public routes - tidak perlu auth
Route::get('/profile', [ProfileController::class, 'show']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/auth/check', [AuthController::class, 'check']);

// Protected admin routes - menggunakan Laravel 12 auth middleware
Route::middleware('auth')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/profile', [ProfileController::class, 'update']);

    Route::apiResource('experiences', ExperienceController::class)->except(['show']);
    Route::apiResource('skills', SkillController::class)->except(['show']);
    Route::apiResource('projects', ProjectController::class)->except(['show', 'index']);
});
