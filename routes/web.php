<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ProfileSettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\PortfolioPublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ==========================================
// PUBLIC PORTFOLIO ROUTES
// ==========================================

// Main portfolio page - now fully dynamic
Route::get('/', [PortfolioPublicController::class, 'index'])->name('portfolio.index');

// Contact form submission
Route::post('/contact', [PortfolioPublicController::class, 'contact'])->name('portfolio.contact');

// CV Download
Route::get('/download-cv', [PortfolioPublicController::class, 'downloadCV'])->name('portfolio.download-cv');

// Project detail page
Route::get('/project/{id}', [PortfolioPublicController::class, 'showProject'])->name('portfolio.project');

// API endpoints for public consumption
Route::prefix('api')->group(function () {
    Route::get('/portfolio', [PortfolioPublicController::class, 'api'])->name('api.portfolio');
    Route::get('/health', [PortfolioPublicController::class, 'health'])->name('api.health');
    Route::get('/analytics', [PortfolioPublicController::class, 'analytics'])->name('api.analytics');

    // Analytics tracking endpoint
    Route::post('/analytics/track', function (Illuminate\Http\Request $request) {
        try {
            \App\Models\PortfolioAnalytic::track(
                $request->input('event_type'),
                $request->input('page'),
                $request->input('data'),
                $request->ip()
            );
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to track'], 500);
        }
    })->name('api.analytics.track');
});

// Cache management (public for webhooks, but should be secured in production)
Route::post('/clear-cache', [PortfolioPublicController::class, 'clearCache'])->name('portfolio.clear-cache');

// Legacy welcome route
Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// ==========================================
// ADMIN DASHBOARD ROUTES (Protected)
// ==========================================

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Profile Routes (Alias)
    Route::get('/admin/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');

    // ==========================================
    // EXISTING ADMIN ROUTES
    // ==========================================

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

    // ==========================================
    // NEW DYNAMIC ADMIN ROUTES
    // ==========================================

    // Profile Settings Management
    Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
        Route::get('/', [ProfileSettingController::class, 'index'])->name('index');
        Route::get('/create', [ProfileSettingController::class, 'create'])->name('create');
        Route::post('/', [ProfileSettingController::class, 'store'])->name('store');
        Route::get('/{setting}/edit', [ProfileSettingController::class, 'edit'])->name('edit');
        Route::put('/{setting}', [ProfileSettingController::class, 'update'])->name('update');
        Route::delete('/{setting}', [ProfileSettingController::class, 'destroy'])->name('destroy');

        // Bulk operations
        Route::post('/bulk-update', [ProfileSettingController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export', [ProfileSettingController::class, 'export'])->name('export');
        Route::post('/import', [ProfileSettingController::class, 'import'])->name('import');
    });

    // Social Links Management
    Route::prefix('admin/social-links')->name('admin.social-links.')->group(function () {
        Route::get('/', [SocialLinkController::class, 'index'])->name('index');
        Route::get('/create', [SocialLinkController::class, 'create'])->name('create');
        Route::post('/', [SocialLinkController::class, 'store'])->name('store');
        Route::get('/{socialLink}/edit', [SocialLinkController::class, 'edit'])->name('edit');
        Route::put('/{socialLink}', [SocialLinkController::class, 'update'])->name('update');
        Route::delete('/{socialLink}', [SocialLinkController::class, 'destroy'])->name('destroy');

        // Reorder links
        Route::post('/reorder', [SocialLinkController::class, 'reorder'])->name('reorder');
    });

    // Achievements Management
    Route::prefix('admin/achievements')->name('admin.achievements.')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('index');
        Route::get('/create', [AchievementController::class, 'create'])->name('create');
        Route::post('/', [AchievementController::class, 'store'])->name('store');
        Route::get('/{achievement}', [AchievementController::class, 'show'])->name('show');
        Route::get('/{achievement}/edit', [AchievementController::class, 'edit'])->name('edit');
        Route::put('/{achievement}', [AchievementController::class, 'update'])->name('update');
        Route::delete('/{achievement}', [AchievementController::class, 'destroy'])->name('destroy');

        // Feature/unfeature achievements
        Route::patch('/{achievement}/toggle-featured', [AchievementController::class, 'toggleFeatured'])->name('toggle-featured');
    });

    // Languages Management
    Route::prefix('admin/languages')->name('admin.languages.')->group(function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::get('/create', [LanguageController::class, 'create'])->name('create');
        Route::post('/', [LanguageController::class, 'store'])->name('store');
        Route::get('/{language}/edit', [LanguageController::class, 'edit'])->name('edit');
        Route::put('/{language}', [LanguageController::class, 'update'])->name('update');
        Route::delete('/{language}', [LanguageController::class, 'destroy'])->name('destroy');
    });

    // Interests Management
    Route::prefix('admin/interests')->name('admin.interests.')->group(function () {
        Route::get('/', [InterestController::class, 'index'])->name('index');
        Route::get('/create', [InterestController::class, 'create'])->name('create');
        Route::post('/', [InterestController::class, 'store'])->name('store');
        Route::get('/{interest}/edit', [InterestController::class, 'edit'])->name('edit');
        Route::put('/{interest}', [InterestController::class, 'update'])->name('update');
        Route::delete('/{interest}', [InterestController::class, 'destroy'])->name('destroy');

        // Feature/unfeature interests
        Route::patch('/{interest}/toggle-featured', [InterestController::class, 'toggleFeatured'])->name('toggle-featured');
    });

    // Certifications Management
    Route::prefix('admin/certifications')->name('admin.certifications.')->group(function () {
        Route::get('/', [CertificationController::class, 'index'])->name('index');
        Route::get('/create', [CertificationController::class, 'create'])->name('create');
        Route::post('/', [CertificationController::class, 'store'])->name('store');
        Route::get('/{certification}', [CertificationController::class, 'show'])->name('show');
        Route::get('/{certification}/edit', [CertificationController::class, 'edit'])->name('edit');
        Route::put('/{certification}', [CertificationController::class, 'update'])->name('update');
        Route::delete('/{certification}', [CertificationController::class, 'destroy'])->name('destroy');
    });

    // Contact Messages Management
    Route::prefix('admin/messages')->name('admin.messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{message}', [ContactMessageController::class, 'show'])->name('show');
        Route::patch('/{message}/mark-read', [ContactMessageController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/{message}/mark-replied', [ContactMessageController::class, 'markAsReplied'])->name('mark-replied');
        Route::delete('/{message}', [ContactMessageController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-actions', [ContactMessageController::class, 'bulkActions'])->name('bulk-actions');

        // Reply to messages
        Route::post('/{message}/reply', [ContactMessageController::class, 'reply'])->name('reply');

        // Export messages
        Route::get('/export', [ContactMessageController::class, 'export'])->name('export');
    });

    // Analytics & Statistics
    Route::prefix('admin/analytics')->name('admin.analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');
        Route::get('/projects', [AnalyticsController::class, 'projects'])->name('projects');
        Route::get('/contact-forms', [AnalyticsController::class, 'contactForms'])->name('contact-forms');
        Route::get('/downloads', [AnalyticsController::class, 'downloads'])->name('downloads');

        // Export analytics data
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');

        // Real-time stats API
        Route::get('/api/stats', [AnalyticsController::class, 'getStats'])->name('api.stats');
        Route::get('/api/chart-data', [AnalyticsController::class, 'getChartData'])->name('api.chart-data');
    });

    // ==========================================
    // SYSTEM MANAGEMENT ROUTES
    // ==========================================

    // Cache Management
    Route::prefix('admin/system')->name('admin.system.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Admin/System/Index');
        })->name('index');

        Route::post('/clear-cache', [PortfolioPublicController::class, 'clearCache'])->name('clear-cache');

        Route::get('/health-check', [PortfolioPublicController::class, 'health'])->name('health-check');

        // Database operations
        Route::post('/optimize-database', function () {
            try {
                \Illuminate\Support\Facades\Artisan::call('optimize');
                \Illuminate\Support\Facades\Artisan::call('config:cache');
                \Illuminate\Support\Facades\Artisan::call('route:cache');
                \Illuminate\Support\Facades\Artisan::call('view:cache');

                return response()->json([
                    'success' => true,
                    'message' => 'Database and application optimized successfully'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Optimization failed: ' . $e->getMessage()
                ], 500);
            }
        })->name('optimize-database');

        // Backup operations
        Route::post('/backup', function () {
            try {
                // Simple backup implementation
                $backupPath = storage_path('app/backups');
                if (!file_exists($backupPath)) {
                    mkdir($backupPath, 0755, true);
                }

                $filename = 'portfolio_backup_' . date('Y_m_d_H_i_s') . '.sql';
                $filepath = $backupPath . '/' . $filename;

                // This is a simplified backup - in production, use proper backup tools
                $command = sprintf(
                    'mysqldump -u%s -p%s %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    $filepath
                );

                exec($command);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'filename' => $filename
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Backup failed: ' . $e->getMessage()
                ], 500);
            }
        })->name('backup');
    });

    // ==========================================
    // API ROUTES FOR ADMIN
    // ==========================================

    Route::prefix('admin/api')->name('admin.api.')->group(function () {
        // Quick stats for dashboard widgets
        Route::get('/quick-stats', function () {
            return response()->json([
                'portfolios' => \App\Models\Portfolio::count(),
                'experiences' => \App\Models\Experience::count(),
                'skills' => \App\Models\Skill::count(),
                'messages' => \App\Models\ContactMessage::unread()->count(),
                'total_views' => \App\Models\PortfolioAnalytic::where('event_type', 'page_view')->count(),
                'cv_downloads' => \App\Models\PortfolioAnalytic::where('event_type', 'cv_download')->count(),
            ]);
        })->name('quick-stats');

        // Search across all content
        Route::get('/search', function (\Illuminate\Http\Request $request) {
            $query = $request->get('q', '');
            $results = [];

            if (strlen($query) >= 2) {
                // Search portfolios
                $portfolios = \App\Models\Portfolio::where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'title', 'project_type']);

                foreach ($portfolios as $portfolio) {
                    $results[] = [
                        'type' => 'portfolio',
                        'id' => $portfolio->id,
                        'title' => $portfolio->title,
                        'subtitle' => $portfolio->project_type,
                        'url' => route('admin.portfolio.show', $portfolio->id)
                    ];
                }

                // Search experiences
                $experiences = \App\Models\Experience::where('position', 'like', "%{$query}%")
                    ->orWhere('organization', 'like', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'position', 'organization']);

                foreach ($experiences as $experience) {
                    $results[] = [
                        'type' => 'experience',
                        'id' => $experience->id,
                        'title' => $experience->position,
                        'subtitle' => $experience->organization,
                        'url' => route('admin.experience.edit', $experience->id)
                    ];
                }

                // Search skills
                $skills = \App\Models\Skill::where('name', 'like', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'name', 'category']);

                foreach ($skills as $skill) {
                    $results[] = [
                        'type' => 'skill',
                        'id' => $skill->id,
                        'title' => $skill->name,
                        'subtitle' => $skill->category,
                        'url' => route('admin.skill.edit', $skill->id)
                    ];
                }

                // Search profile settings
                $settings = \App\Models\ProfileSetting::where('key', 'like', "%{$query}%")
                    ->orWhere('value', 'like', "%{$query}%")
                    ->limit(5)
                    ->get(['id', 'key', 'category']);

                foreach ($settings as $setting) {
                    $results[] = [
                        'type' => 'setting',
                        'id' => $setting->id,
                        'title' => $setting->key,
                        'subtitle' => $setting->category,
                        'url' => route('admin.settings.edit', $setting->id)
                    ];
                }
            }

            return response()->json($results);
        })->name('search');

        // Bulk operations endpoint
        Route::post('/bulk-operations', function (\Illuminate\Http\Request $request) {
            $action = $request->get('action');
            $type = $request->get('type');
            $ids = $request->get('ids', []);

            try {
                switch ($type) {
                    case 'portfolio':
                        $model = \App\Models\Portfolio::class;
                        break;
                    case 'experience':
                        $model = \App\Models\Experience::class;
                        break;
                    case 'skill':
                        $model = \App\Models\Skill::class;
                        break;
                    case 'message':
                        $model = \App\Models\ContactMessage::class;
                        break;
                    default:
                        throw new \Exception('Invalid type');
                }

                $count = 0;
                switch ($action) {
                    case 'delete':
                        $count = $model::whereIn('id', $ids)->delete();
                        break;
                    case 'activate':
                        if (in_array($type, ['skill', 'achievement', 'interest'])) {
                            $count = $model::whereIn('id', $ids)->update(['is_active' => true]);
                        }
                        break;
                    case 'deactivate':
                        if (in_array($type, ['skill', 'achievement', 'interest'])) {
                            $count = $model::whereIn('id', $ids)->update(['is_active' => false]);
                        }
                        break;
                    case 'mark_read':
                        if ($type === 'message') {
                            $count = $model::whereIn('id', $ids)->update([
                                'status' => 'read',
                                'read_at' => now()
                            ]);
                        }
                        break;
                }

                return response()->json([
                    'success' => true,
                    'message' => "Successfully {$action}d {$count} items",
                    'count' => $count
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bulk operation failed: ' . $e->getMessage()
                ], 500);
            }
        })->name('bulk-operations');
    });

    // ==========================================
    // AJAX ENDPOINTS FOR DYNAMIC FEATURES
    // ==========================================

    // Auto-save for form drafts
    Route::post('/admin/auto-save/{type}/{id?}', function (\Illuminate\Http\Request $request, $type, $id = null) {
        try {
            $data = $request->all();
            $key = "auto_save_{$type}_" . ($id ?? 'new') . '_' . auth()->id();

            \Illuminate\Support\Facades\Cache::put($key, $data, 300); // 5 minutes

            return response()->json([
                'success' => true,
                'message' => 'Draft saved',
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auto-save failed'
            ], 500);
        }
    })->name('admin.auto-save');

    // Get auto-saved draft
    Route::get('/admin/get-draft/{type}/{id?}', function ($type, $id = null) {
        try {
            $key = "auto_save_{$type}_" . ($id ?? 'new') . '_' . auth()->id();
            $draft = \Illuminate\Support\Facades\Cache::get($key);

            return response()->json([
                'success' => true,
                'data' => $draft,
                'has_draft' => !is_null($draft)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve draft'
            ], 500);
        }
    })->name('admin.get-draft');

    // File upload endpoint
    Route::post('/admin/upload', function (\Illuminate\Http\Request $request) {
        try {
            $request->validate([
                'file' => 'required|file|max:10240', // 10MB max
                'type' => 'required|string|in:image,document,cv'
            ]);

            $file = $request->file('file');
            $type = $request->get('type');

            // Determine upload path and allowed types
            switch ($type) {
                case 'image':
                    $path = 'uploads/images';
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    break;
                case 'document':
                    $path = 'uploads/documents';
                    $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    break;
                case 'cv':
                    $path = 'uploads/cv';
                    $allowedMimes = ['application/pdf'];
                    break;
                default:
                    throw new \Exception('Invalid upload type');
            }

            // Validate MIME type
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new \Exception('File type not allowed');
            }

            // Generate unique filename
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Store file
            $filePath = $file->storeAs($path, $filename, 'public');

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $filePath,
                    'url' => asset('storage/' . $filePath),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    })->name('admin.upload');

    // Preview endpoint for markdown/rich text
    Route::post('/admin/preview', function (\Illuminate\Http\Request $request) {
        try {
            $content = $request->get('content', '');
            $type = $request->get('type', 'html');

            switch ($type) {
                case 'markdown':
                    // If you have a markdown parser installed
                    // $html = \Parsedown::instance()->text($content);
                    $html = nl2br(e($content)); // Fallback
                    break;
                case 'html':
                default:
                    $html = $content;
                    break;
            }

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview failed'
            ], 500);
        }
    })->name('admin.preview');
});

require __DIR__ . '/auth.php';
