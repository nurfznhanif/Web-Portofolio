<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\ProfileSetting;
use App\Models\SocialLink;
use App\Models\Achievement;
use App\Models\Language;
use App\Models\Interest;
use App\Models\Certification;
use App\Models\ContactMessage;
use App\Models\PortfolioAnalytic;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class PortfolioPublicController extends Controller
{
    /**
     * Display the modern portfolio page - Now fully dynamic
     */
    public function index(Request $request)
    {
        try {
            // Track page view
            $this->trackEvent('page_view', 'portfolio_home', null, $request->ip());

            // Cache key with version for easy invalidation
            $cacheKey = 'dynamic_portfolio_data_v2';
            $cacheDuration = (int) ProfileSetting::getValue('cache_duration', 3600);

            // Get portfolio data from cache or database
            $portfolioData = Cache::remember($cacheKey, $cacheDuration, function () {
                return [
                    'portfolios' => $this->getPortfoliosData(),
                    'experiences' => $this->getExperiencesData(),
                    'skills' => $this->getSkillsData(),
                ];
            });

            // Get dynamic profile data
            $profile = $this->getDynamicProfileData();

            // Calculate statistics
            $statistics = $this->calculateDynamicStatistics($portfolioData);

            // Generate meta data
            $metaData = $this->generateDynamicMetaData($profile, $statistics);

            // Get configuration settings
            $config = $this->getConfigurationSettings();

            return Inertia::render('Portfolio/Index', [
                'portfolios' => $portfolioData['portfolios'],
                'experiences' => $portfolioData['experiences'],
                'skills' => $portfolioData['skills'],
                'profile' => $profile,
                'statistics' => $statistics,
                'meta' => $metaData,
                'config' => $config,
                'socialLinks' => $this->getSocialLinksData(),
                'achievements' => $this->getAchievementsData(),
                'languages' => $this->getLanguagesData(),
                'interests' => $this->getInterestsData(),
                'certifications' => $this->getCertificationsData(),
            ]);
        } catch (\Exception $e) {
            Log::error('Dynamic portfolio index error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->fallbackPortfolioData();
        }
    }

    /**
     * Enhanced contact form handling with dynamic settings
     */
    public function contact(Request $request)
    {
        try {
            // Check if contact form is enabled
            if (!ProfileSetting::getValue('enable_contact_form', true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contact form is currently disabled.'
                ], 503);
            }

            // Enhanced rate limiting
            $key = 'contact_form_' . $request->ip();
            $maxAttempts = 5;
            $decayMinutes = 60;

            if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'success' => false,
                    'message' => "Too many contact attempts. Please try again in " . ceil($seconds / 60) . " minutes."
                ], 429);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|min:2|regex:/^[\pL\s\-]+$/u',
                'email' => 'required|email:rfc,dns|max:255',
                'subject' => 'nullable|string|max:255|min:3',
                'message' => 'required|string|max:2000|min:10',
                'honeypot' => 'nullable|max:0', // Honeypot field for bot detection
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please check your input and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Check for spam patterns
            if ($this->detectSpam($validated)) {
                Log::warning('Spam detected in contact form', [
                    'email' => $validated['email'],
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Your message appears to be spam. Please try again with a genuine message.'
                ], 422);
            }

            // Hit rate limiter
            RateLimiter::hit($key, $decayMinutes * 60);

            // Enhanced data collection
            $contactData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? 'Portfolio Contact',
                'message' => $validated['message'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'country' => $request->header('cf-ipcountry'),
                'status' => 'new'
            ];

            // Save to database
            $contactMessage = ContactMessage::create($contactData);

            // Track contact event
            $this->trackEvent('contact_form_submission', 'contact', [
                'message_id' => $contactMessage->id,
                'has_subject' => !empty($validated['subject'])
            ], $request->ip());

            // Log contact attempt with comprehensive data
            Log::info('Portfolio contact form submission', [
                'message_id' => $contactMessage->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? 'Portfolio Contact',
                'message_length' => strlen($validated['message']),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
                'referrer' => $request->header('referer'),
                'country' => $request->header('cf-ipcountry'),
            ]);

            // Send email notification (implement as needed)
            $this->sendContactNotification($contactMessage);

            // Send auto-reply to user
            $this->sendAutoReply($contactMessage);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! I will get back to you within 24 hours.',
                'data' => [
                    'sent_at' => now()->toISOString(),
                    'message_id' => $contactMessage->id,
                    'reference' => 'MSG-' . strtoupper(Str::random(8))
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage(), [
                'request_data' => $request->except(['password', 'token']),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again later.'
            ], 500);
        }
    }

    /**
     * Download CV/Resume with analytics
     */
    public function downloadCV(Request $request)
    {
        try {
            // Track download
            $this->trackEvent('cv_download', 'download', [
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer')
            ], $request->ip());

            // Get CV path from settings
            $cvPath = ProfileSetting::getValue('cv_file_path', 'documents/Nurfauzan_Hanif_CV.pdf');
            $cvFileName = ProfileSetting::getValue('cv_file_name', 'Nurfauzan_Hanif_CV.pdf');

            // Check multiple possible locations
            $possiblePaths = [
                public_path($cvPath),
                public_path('documents/Nurfauzan_Hanif_CV_Modern.pdf'),
                storage_path('app/public/' . $cvPath),
                public_path('storage/' . $cvPath),
            ];

            $actualPath = null;
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $actualPath = $path;
                    break;
                }
            }

            if (!$actualPath) {
                Log::warning('CV file not found at any location', [
                    'searched_paths' => $possiblePaths,
                    'ip' => $request->ip()
                ]);

                return response()->json(['error' => 'CV not available for download'], 404);
            }

            // Log download attempt
            Log::info('CV download', [
                'file_path' => $actualPath,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
                'referrer' => $request->header('referer')
            ]);

            return response()->download($actualPath, $cvFileName, [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'public, max-age=3600',
                'Content-Disposition' => 'attachment; filename="' . $cvFileName . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('CV download error: ' . $e->getMessage(), [
                'ip' => $request->ip(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json(['error' => 'Failed to download CV'], 500);
        }
    }

    /**
     * Get project details with analytics
     */
    public function showProject(Request $request, $id)
    {
        try {
            $project = Portfolio::findOrFail($id);

            // Track project view
            $this->trackEvent('project_view', 'project_detail', [
                'project_id' => $project->id,
                'project_title' => $project->title,
                'project_type' => $project->project_type
            ], $request->ip());

            // Get related projects
            $relatedProjects = Portfolio::where('project_type', $project->project_type)
                ->where('id', '!=', $project->id)
                ->orderBy('project_date', 'desc')
                ->limit(3)
                ->get(['id', 'title', 'description', 'project_type', 'project_date', 'technologies']);

            $projectData = [
                'project' => $project,
                'related' => $relatedProjects,
                'meta' => [
                    'title' => $project->title . ' - ' . ProfileSetting::getValue('name', 'Portfolio'),
                    'description' => Str::limit(strip_tags($project->description), 160),
                    'image' => ProfileSetting::getValue('photo', asset('images/default-avatar.png')),
                    'url' => url()->current()
                ]
            ];

            if ($request->expectsJson()) {
                return response()->json($projectData);
            }

            return Inertia::render('Portfolio/ProjectDetail', $projectData);
        } catch (\Exception $e) {
            Log::error('Show project error: ' . $e->getMessage(), [
                'project_id' => $id,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            return redirect()->route('portfolio.index')
                ->with('error', 'Project not found');
        }
    }

    /**
     * API endpoint with enhanced filtering
     */
    public function api(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $limit = (int) $request->get('limit', ProfileSetting::getValue('items_per_page', 10));
            $search = $request->get('search', '');
            $category = $request->get('category', '');

            $cacheKey = "portfolio_api_{$type}_{$limit}_{$search}_{$category}";
            $data = Cache::remember($cacheKey, 1800, function () use ($type, $limit, $search, $category) {
                $query = Portfolio::query();

                // Apply search filter
                if (!empty($search)) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('technologies', 'like', "%{$search}%");
                    });
                }

                // Apply type filter
                if ($type !== 'all') {
                    $query->where('project_type', $type);
                }

                $portfolios = $query->select([
                    'id',
                    'title',
                    'description',
                    'technologies',
                    'project_type',
                    'project_date',
                    'github_url'
                ])->orderBy('project_date', 'desc')
                    ->limit($limit)
                    ->get();

                return [
                    'portfolios' => $portfolios,
                    'total' => $query->count(),
                    'types' => Portfolio::distinct()->pluck('project_type')->values(),
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Portfolio API error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch portfolio data'], 500);
        }
    }

    /**
     * Clear cache with better management
     */
    public function clearCache(Request $request)
    {
        try {
            // Only allow if authenticated (add auth middleware)
            $patterns = [
                'dynamic_portfolio_data_v2',
                'portfolio_api_*',
                'dynamic_profile_data',
                'portfolio_stats_*',
                'social_links_data',
                'achievements_data',
                'languages_data',
                'interests_data',
                'certifications_data'
            ];

            $clearedCount = 0;
            foreach ($patterns as $pattern) {
                if (str_contains($pattern, '*')) {
                    // For wildcard patterns, we'd need a more sophisticated approach
                    // For now, clear known variations
                    $basePattern = str_replace('*', '', $pattern);
                    Cache::forget($basePattern);
                    $clearedCount++;
                } else {
                    if (Cache::forget($pattern)) {
                        $clearedCount++;
                    }
                }
            }

            // Log cache clear
            Log::info('Portfolio cache cleared', [
                'cleared_count' => $clearedCount,
                'patterns' => $patterns,
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Portfolio cache cleared successfully. {$clearedCount} cache keys cleared.",
                'timestamp' => now(),
                'cleared_count' => $clearedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to clear cache'], 500);
        }
    }

    /**
     * Enhanced health check
     */
    public function health()
    {
        try {
            $health = [
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'portfolios' => Portfolio::count(),
                    'experiences' => Experience::count(),
                    'skills' => Skill::count(),
                    'profile_settings' => ProfileSetting::count(),
                    'social_links' => SocialLink::active()->count(),
                    'achievements' => Achievement::active()->count(),
                    'languages' => Language::active()->count(),
                    'interests' => Interest::active()->count(),
                    'certifications' => Certification::active()->count(),
                    'contact_messages' => ContactMessage::count(),
                    'unread_messages' => ContactMessage::unread()->count(),
                    'database' => 'connected'
                ],
                'performance' => [
                    'memory_usage' => $this->formatBytes(memory_get_usage(true)),
                    'peak_memory' => $this->formatBytes(memory_get_peak_usage(true)),
                    'cache_status' => Cache::getStore() ? 'connected' : 'disconnected',
                ],
                'configuration' => [
                    'contact_form_enabled' => ProfileSetting::getValue('enable_contact_form', true),
                    'analytics_enabled' => ProfileSetting::getValue('enable_analytics', true),
                    'animations_enabled' => ProfileSetting::getValue('enable_animations', true),
                    'theme' => ProfileSetting::getValue('theme', 'modern'),
                    'cache_duration' => ProfileSetting::getValue('cache_duration', 3600),
                ],
                'version' => '2.1.0'
            ];

            return response()->json($health);
        } catch (\Exception $e) {
            Log::error('Health check failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'unhealthy',
                'error' => 'Service temporarily unavailable',
                'timestamp' => now()->toISOString(),
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Analytics endpoint for admin dashboard
     */
    public function analytics(Request $request)
    {
        try {
            $days = (int) $request->get('days', 30);
            $eventType = $request->get('event_type', null);

            $analytics = PortfolioAnalytic::when($eventType, function ($query) use ($eventType) {
                return $query->byEventType($eventType);
            })->lastDays($days)->get();

            $stats = [
                'total_views' => $analytics->where('event_type', 'page_view')->count(),
                'unique_visitors' => $analytics->where('event_type', 'page_view')->unique('ip_address')->count(),
                'cv_downloads' => $analytics->where('event_type', 'cv_download')->count(),
                'contact_forms' => $analytics->where('event_type', 'contact_form_submission')->count(),
                'project_views' => $analytics->where('event_type', 'project_view')->count(),
                'popular_projects' => $analytics->where('event_type', 'project_view')
                    ->groupBy('data.project_id')
                    ->map->count()
                    ->sortDesc()
                    ->take(5),
                'daily_stats' => $analytics->groupBy(function ($item) {
                    return $item->created_at->format('Y-m-d');
                })->map->count(),
                'top_referrers' => $analytics->whereNotNull('referrer')
                    ->groupBy('referrer')
                    ->map->count()
                    ->sortDesc()
                    ->take(10)
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'period' => "{$days} days",
                'generated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Analytics error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch analytics'], 500);
        }
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    /**
     * Get portfolios data with optimized queries
     */
    private function getPortfoliosData()
    {
        return Portfolio::select([
            'id',
            'title',
            'description',
            'technologies',
            'challenges',
            'solutions',
            'github_url',
            'project_type',
            'project_date',
            'created_at'
        ])->orderBy('project_date', 'desc')->get();
    }

    /**
     * Get experiences data
     */
    private function getExperiencesData()
    {
        return Experience::select([
            'id',
            'position',
            'organization',
            'description',
            'start_date',
            'end_date',
            'is_current'
        ])->orderBy('start_date', 'desc')->get();
    }

    /**
     * Get skills data grouped by category
     */
    private function getSkillsData()
    {
        return Skill::select(['id', 'name', 'category', 'level'])
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
    }

    /**
     * Get dynamic profile data from settings
     */
    private function getDynamicProfileData()
    {
        $cacheKey = 'dynamic_profile_data';
        return Cache::remember($cacheKey, 3600, function () {
            $settings = ProfileSetting::where('is_public', true)->get();
            $profile = [];

            foreach ($settings as $setting) {
                // Handle nested keys (e.g., 'education_gpa' -> ['education']['gpa'])
                if (str_contains($setting->key, '_')) {
                    $keys = explode('_', $setting->key, 2);
                    if (count($keys) === 2) {
                        $profile[$keys[0]][$keys[1]] = $setting->value;
                    } else {
                        $profile[$setting->key] = $setting->value;
                    }
                } else {
                    $profile[$setting->key] = $setting->value;
                }
            }

            // Add computed fields
            $profile['portfolio_url'] = config('app.url');
            $profile['generated_at'] = now()->toISOString();

            return $profile;
        });
    }

    /**
     * Get social links data
     */
    private function getSocialLinksData()
    {
        $cacheKey = 'social_links_data';
        return Cache::remember($cacheKey, 3600, function () {
            return SocialLink::active()->ordered()->get();
        });
    }

    /**
     * Get achievements data
     */
    private function getAchievementsData()
    {
        $cacheKey = 'achievements_data';
        return Cache::remember($cacheKey, 3600, function () {
            return Achievement::active()->ordered()->get();
        });
    }

    /**
     * Get languages data
     */
    private function getLanguagesData()
    {
        $cacheKey = 'languages_data';
        return Cache::remember($cacheKey, 3600, function () {
            return Language::active()->ordered()->get();
        });
    }

    /**
     * Get interests data
     */
    private function getInterestsData()
    {
        $cacheKey = 'interests_data';
        return Cache::remember($cacheKey, 3600, function () {
            return Interest::active()->ordered()->get();
        });
    }

    /**
     * Get certifications data
     */
    private function getCertificationsData()
    {
        $cacheKey = 'certifications_data';
        return Cache::remember($cacheKey, 3600, function () {
            return Certification::active()->ordered()->get();
        });
    }

    /**
     * Calculate dynamic statistics
     */
    private function calculateDynamicStatistics($portfolioData)
    {
        $portfolios = $portfolioData['portfolios'];
        $experiences = $portfolioData['experiences'];
        $skills = $portfolioData['skills'];

        // Calculate years of experience
        $firstExperience = $experiences->sortBy('start_date')->first();
        $yearsOfExperience = $firstExperience
            ? max(1, now()->diffInYears($firstExperience->start_date))
            : 1;

        // Technology analysis
        $technologies = [];
        foreach ($portfolios as $portfolio) {
            if ($portfolio->technologies) {
                $techs = array_map('trim', explode(',', $portfolio->technologies));
                foreach ($techs as $tech) {
                    if (!empty($tech)) {
                        $technologies[$tech] = ($technologies[$tech] ?? 0) + 1;
                    }
                }
            }
        }

        arsort($technologies);
        $topTechnologies = array_slice($technologies, 0, 10, true);

        // Skills analysis
        $skillStats = [];
        $totalSkills = 0;
        $advancedSkills = 0;

        foreach ($skills as $category => $skillList) {
            $categoryStats = [
                'total' => $skillList->count(),
                'advanced' => $skillList->where('level', 'mahir')->count(),
                'intermediate' => $skillList->where('level', 'menengah')->count(),
                'beginner' => $skillList->where('level', 'pemula')->count(),
            ];

            $skillStats[$category] = $categoryStats;
            $totalSkills += $categoryStats['total'];
            $advancedSkills += $categoryStats['advanced'];
        }

        // Recent activity
        $recentProjects = $portfolios->where('project_date', '>=', now()->subMonths(6));
        $currentPositions = $experiences->where('is_current', true);

        return [
            'overview' => [
                'total_projects' => $portfolios->count(),
                'years_experience' => $yearsOfExperience,
                'total_skills' => $totalSkills,
                'skill_categories' => $skills->count(),
                'current_positions' => $currentPositions->count(),
                'advanced_skills' => $advancedSkills,
                'skill_proficiency' => $totalSkills > 0 ? round(($advancedSkills / $totalSkills) * 100) : 0,
                'recent_projects' => $recentProjects->count()
            ],

            'projects' => [
                'by_type' => $portfolios->groupBy('project_type')->map->count()->toArray(),
                'recent_count' => $recentProjects->count(),
                'latest' => $portfolios->first(),
                'featured' => $portfolios->take(6),
                'completion_rate' => 100
            ],

            'technologies' => [
                'all' => $technologies,
                'top_10' => $topTechnologies,
                'most_used' => count($topTechnologies) > 0 ? array_key_first($topTechnologies) : null,
                'diversity_score' => count($technologies),
                'modern_score' => $this->calculateModernTechScore($technologies)
            ],

            'experience' => [
                'total_positions' => $experiences->count(),
                'current_roles' => $currentPositions->pluck('position')->toArray(),
                'organizations' => $experiences->pluck('organization')->unique()->count(),
                'latest_position' => $experiences->first()
            ],

            'skills' => [
                'by_category' => $skillStats,
                'proficiency_breakdown' => [
                    'advanced' => $advancedSkills,
                    'intermediate' => array_sum(array_column($skillStats, 'intermediate')),
                    'beginner' => array_sum(array_column($skillStats, 'beginner')),
                ]
            ],

            'performance_metrics' => [
                'portfolio_completeness' => $this->calculatePortfolioCompleteness($portfolios),
                'project_complexity' => $this->assessProjectComplexity($portfolios),
                'career_momentum' => $this->calculateCareerMomentum($experiences)
            ],

            'generated_at' => now()->toISOString(),
            'cache_duration' => ProfileSetting::getValue('cache_duration', 3600)
        ];
    }

    /**
     * Generate dynamic meta data
     */
    private function generateDynamicMetaData($profile, $statistics)
    {
        $name = $profile['name'] ?? 'Portfolio';
        $title = $profile['title'] ?? 'Developer';
        $summary = $profile['summary'] ?? 'Portfolio website';
        $photo = $profile['photo'] ?? '/images/default-avatar.png';

        $skillCount = $statistics['overview']['total_skills'] ?? 0;
        $projectCount = $statistics['overview']['total_projects'] ?? 0;
        $gpa = $profile['education']['gpa'] ?? '';

        $description = "Passionate {$title} with {$projectCount}+ projects and {$skillCount}+ skills" .
            ($gpa ? ", GPA {$gpa}" : '') .
            ". Specializing in full-stack development, AI/ML, and data analysis.";

        return [
            'title' => ProfileSetting::getValue('site_title', "{$name} - {$title} | Portfolio"),
            'description' => ProfileSetting::getValue('site_description', $description),
            'keywords' => ProfileSetting::getValue('site_keywords', "{$name}, {$title}, Developer, Portfolio"),
            'author' => $name,
            'type' => 'portfolio',
            'url' => config('app.url'),
            'image' => asset($photo),
            'image_alt' => "{$name} - {$title}",
            'locale' => 'id_ID',
            'site_name' => "{$name} Portfolio",
            'twitter_card' => 'summary_large_image',
            'og_type' => 'profile',
            'canonical_url' => config('app.url'),

            'structured_data' => [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => $name,
                'jobTitle' => $title,
                'description' => $summary,
                'url' => config('app.url'),
                'image' => asset($photo),
                'email' => $profile['email'] ?? '',
                'telephone' => $profile['phone'] ?? '',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Pekanbaru',
                    'addressRegion' => 'Riau',
                    'addressCountry' => 'Indonesia'
                ]
            ]
        ];
    }

    /**
     * Get configuration settings
     */
    private function getConfigurationSettings()
    {
        return [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'contact_email' => ProfileSetting::getValue('email', 'contact@example.com'),
            'enable_contact_form' => ProfileSetting::getValue('enable_contact_form', true),
            'enable_animations' => ProfileSetting::getValue('enable_animations', true),
            'enable_analytics' => ProfileSetting::getValue('enable_analytics', true),
            'theme' => ProfileSetting::getValue('theme', 'modern'),
            'items_per_page' => (int) ProfileSetting::getValue('items_per_page', 6),
            'cache_duration' => (int) ProfileSetting::getValue('cache_duration', 3600)
        ];
    }

    /**
     * Track analytics events
     */
    private function trackEvent(string $eventType, ?string $page = null, ?array $data = null, ?string $ipAddress = null)
    {
        if (!ProfileSetting::getValue('enable_analytics', true)) {
            return false;
        }

        try {
            PortfolioAnalytic::track($eventType, $page, $data, $ipAddress);
            return true;
        } catch (\Exception $e) {
            Log::warning('Failed to track event: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Detect spam in contact form
     */
    private function detectSpam(array $data): bool
    {
        $spamKeywords = ['viagra', 'casino', 'loan', 'bitcoin', 'crypto', 'investment', 'make money'];
        $message = strtolower($data['message']);
        $name = strtolower($data['name']);
        $email = strtolower($data['email']);

        // Check for spam keywords
        foreach ($spamKeywords as $keyword) {
            if (str_contains($message, $keyword) || str_contains($name, $keyword)) {
                return true;
            }
        }

        // Check for suspicious patterns
        if (preg_match('/https?:\/\//', $message) && substr_count($message, 'http') > 2) {
            return true; // Too many links
        }

        if (strlen($message) > 1500 && str_word_count($message) < 50) {
            return true; // Too many characters with few words
        }

        if (preg_match('/(.)\1{4,}/', $message)) {
            return true; // Repeated characters
        }

        return false;
    }

    /**
     * Send contact notification (implement as needed)
     */
    private function sendContactNotification(ContactMessage $message)
    {
        // Implement email notification to admin
        // Could use Laravel Mail, third-party services, etc.

        Log::info('Contact notification would be sent', [
            'message_id' => $message->id,
            'from' => $message->email,
            'name' => $message->name
        ]);
    }

    /**
     * Send auto-reply to user
     */
    private function sendAutoReply(ContactMessage $message)
    {
        // Implement auto-reply email to user

        Log::info('Auto-reply would be sent', [
            'message_id' => $message->id,
            'to' => $message->email
        ]);
    }

    /**
     * Calculate modern technology score
     */
    private function calculateModernTechScore(array $technologies): int
    {
        $modernTechs = [
            'Laravel',
            'Vue.js',
            'React',
            'Node.js',
            'Python',
            'TypeScript',
            'Docker',
            'Kubernetes',
            'MongoDB',
            'PostgreSQL',
            'Redis',
            'TensorFlow',
            'PyTorch',
            'Next.js',
            'Tailwind CSS',
            'GraphQL'
        ];

        $modernTechCount = 0;
        $totalUsage = array_sum($technologies);

        foreach ($technologies as $tech => $count) {
            if (in_array($tech, $modernTechs)) {
                $modernTechCount += $count;
            }
        }

        return $totalUsage > 0 ? round(($modernTechCount / $totalUsage) * 100) : 0;
    }

    /**
     * Calculate portfolio completeness
     */
    private function calculatePortfolioCompleteness($portfolios): int
    {
        if ($portfolios->isEmpty()) return 0;

        $completenessFactors = 0;
        $totalFactors = 6;

        if ($portfolios->count() >= 3) $completenessFactors++;
        if ($portfolios->where('github_url', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->where('challenges', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->where('solutions', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->count() >= 5) $completenessFactors++;
        if ($portfolios->where('project_date', '>=', now()->subYear())->count() > 0) $completenessFactors++;

        return round(($completenessFactors / $totalFactors) * 100);
    }

    /**
     * Assess project complexity
     */
    private function assessProjectComplexity($portfolios): int
    {
        if ($portfolios->isEmpty()) return 0;

        $totalComplexity = 0;

        foreach ($portfolios as $portfolio) {
            $complexity = 0;

            // Technology count (max 40 points)
            $techCount = count(array_filter(explode(',', $portfolio->technologies ?? '')));
            $complexity += min($techCount * 5, 40);

            // Additional factors
            if ($portfolio->github_url) $complexity += 20;
            if ($portfolio->challenges) $complexity += 20;
            if ($portfolio->solutions) $complexity += 20;

            $totalComplexity += min($complexity, 100);
        }

        return round($totalComplexity / $portfolios->count());
    }

    /**
     * Calculate career momentum
     */
    private function calculateCareerMomentum($experiences): int
    {
        $momentum = 0;

        $currentPositions = $experiences->where('is_current', true)->count();
        $recentExperiences = $experiences->where('start_date', '>=', now()->subYear())->count();
        $totalExperiences = $experiences->count();

        $momentum += ($currentPositions * 30);
        $momentum += ($recentExperiences * 25);
        $momentum += ($totalExperiences * 15);

        return min($momentum, 100);
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($size, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * Fallback method when main system fails
     */
    private function fallbackPortfolioData()
    {
        $basicProfile = [
            'name' => ProfileSetting::getValue('name', 'Portfolio Owner'),
            'title' => ProfileSetting::getValue('title', 'Developer'),
            'email' => ProfileSetting::getValue('email', 'contact@example.com'),
            'education' => ['gpa' => ProfileSetting::getValue('education_gpa', '0.00')]
        ];

        return Inertia::render('Portfolio/Index', [
            'portfolios' => collect([]),
            'experiences' => collect([]),
            'skills' => collect([]),
            'profile' => $basicProfile,
            'statistics' => ['overview' => ['total_projects' => 0]],
            'meta' => ['title' => 'Portfolio'],
            'config' => $this->getConfigurationSettings(),
            'socialLinks' => collect([]),
            'achievements' => collect([]),
            'languages' => collect([]),
            'interests' => collect([]),
            'certifications' => collect([]),
            'error' => 'Some data may be temporarily unavailable. Please try again later.'
        ]);
    }
}
