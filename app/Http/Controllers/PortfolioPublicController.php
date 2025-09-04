<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PortfolioPublicController extends Controller
{
    /**
     * Display the modern portfolio page
     */
    public function index()
    {
        try {
            // Cache portfolio data for better performance (1 hour)
            $portfolioData = Cache::remember('modern_portfolio_data', 3600, function () {
                return [
                    'portfolios' => Portfolio::select([
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
                    ])->orderBy('project_date', 'desc')->get(),

                    'experiences' => Experience::select([
                        'id',
                        'position',
                        'organization',
                        'description',
                        'start_date',
                        'end_date',
                        'is_current'
                    ])->orderBy('start_date', 'desc')->get(),

                    'skills' => Skill::select([
                        'id',
                        'name',
                        'category',
                        'level'
                    ])->orderBy('category')->orderBy('name')->get()->groupBy('category'),
                ];
            });

            // Enhanced profile data with comprehensive information
            $profile = $this->getEnhancedProfileData();

            // Calculate dynamic statistics
            $statistics = $this->calculatePortfolioStatistics($portfolioData);

            // Generate meta data for SEO
            $metaData = $this->generateMetaData($profile, $statistics);

            return Inertia::render('Portfolio/Index', [
                'portfolios' => $portfolioData['portfolios'],
                'experiences' => $portfolioData['experiences'],
                'skills' => $portfolioData['skills'],
                'profile' => $profile,
                'statistics' => $statistics,
                'meta' => $metaData,
                'config' => [
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url'),
                    'contact_email' => config('portfolio.contact_email', 'nrfznhnf@gmail.com'),
                    'github_username' => config('portfolio.github_username', 'nurfznhanif'),
                    'enable_contact_form' => config('portfolio.enable_contact_form', true),
                    'enable_animations' => config('portfolio.enable_animations', true),
                    'theme' => config('portfolio.theme', 'modern'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Portfolio index error: ' . $e->getMessage());

            // Fallback to basic data if cache fails
            return $this->fallbackPortfolioData();
        }
    }

    /**
     * API endpoint for portfolio data
     */
    public function api(Request $request)
    {
        try {
            $type = $request->get('type', 'all');
            $limit = $request->get('limit', 10);
            $search = $request->get('search', '');

            $data = Cache::remember("portfolio_api_{$type}_{$limit}_{$search}", 1800, function () use ($type, $limit, $search) {
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
     * Handle contact form submission
     */
    public function contact(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|min:2',
                'email' => 'required|email:rfc,dns|max:255',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|max:2000|min:10',
            ]);

            // Rate limiting check
            $key = 'contact_form_' . $request->ip();
            if (Cache::get($key, 0) >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many contact attempts. Please try again later.'
                ], 429);
            }

            Cache::put($key, Cache::get($key, 0) + 1, 3600); // 1 hour

            // Log contact attempt with comprehensive data
            Log::info('Portfolio contact form submission', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? 'Portfolio Contact',
                'message_length' => strlen($validated['message']),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString(),
                'referrer' => $request->header('referer'),
                'country' => $request->header('cf-ipcountry'), // If using Cloudflare
            ]);

            // Here you could integrate with email services like:
            // - Mail::send() for Laravel Mail
            // - Notification system
            // - Third-party services (SendGrid, Mailgun, etc.)
            // - Save to database for admin dashboard

            // For now, we'll simulate email sending
            $this->simulateEmailSending($validated, $request);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your message! I will get back to you within 24 hours.',
                'data' => [
                    'sent_at' => now()->toISOString(),
                    'message_id' => uniqid('msg_')
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your input and try again.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sorry, there was an error sending your message. Please try again later.'
            ], 500);
        }
    }

    /**
     * Download CV/Resume
     */
    public function downloadCV()
    {
        try {
            $cvPath = public_path('documents/Nurfauzan_Hanif_CV_Modern.pdf');

            if (!file_exists($cvPath)) {
                // Try alternative locations
                $alternativePaths = [
                    storage_path('app/public/documents/Nurfauzan_Hanif_CV.pdf'),
                    public_path('storage/documents/CV.pdf'),
                ];

                foreach ($alternativePaths as $path) {
                    if (file_exists($path)) {
                        $cvPath = $path;
                        break;
                    }
                }
            }

            if (!file_exists($cvPath)) {
                Log::warning('CV file not found at any location');
                return response()->json(['error' => 'CV not available'], 404);
            }

            // Log download attempt
            Log::info('CV download', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()
            ]);

            return response()->download($cvPath, 'Nurfauzan_Hanif_CV.pdf', [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'public, max-age=3600'
            ]);
        } catch (\Exception $e) {
            Log::error('CV download error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to download CV'], 500);
        }
    }

    /**
     * Get project by ID with related projects
     */
    public function showProject(Request $request, $id)
    {
        try {
            $project = Portfolio::findOrFail($id);

            // Get related projects (same type, excluding current)
            $relatedProjects = Portfolio::where('project_type', $project->project_type)
                ->where('id', '!=', $project->id)
                ->orderBy('project_date', 'desc')
                ->limit(3)
                ->get(['id', 'title', 'description', 'project_type', 'project_date', 'technologies']);

            $projectData = [
                'project' => $project,
                'related' => $relatedProjects,
                'meta' => [
                    'title' => $project->title . ' - Nurfauzan Hanif Portfolio',
                    'description' => substr(strip_tags($project->description), 0, 160),
                    'image' => asset('images/poto1.png'),
                ]
            ];

            if ($request->expectsJson()) {
                return response()->json($projectData);
            }

            return Inertia::render('Portfolio/ProjectDetail', $projectData);
        } catch (\Exception $e) {
            Log::error('Show project error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            return redirect()->route('portfolio.index')->with('error', 'Project not found');
        }
    }

    /**
     * Clear portfolio cache (for admin/automated cleanup)
     */
    public function clearCache()
    {
        try {
            $cacheKeys = [
                'modern_portfolio_data',
                'portfolio_api_*',
                'portfolio_stats',
            ];

            foreach ($cacheKeys as $key) {
                if (str_contains($key, '*')) {
                    // Clear pattern-based cache keys
                    $pattern = str_replace('*', '', $key);
                    Cache::forget($pattern);
                } else {
                    Cache::forget($key);
                }
            }

            // Clear project type and skill category caches
            $projectTypes = Portfolio::distinct()->pluck('project_type');
            foreach ($projectTypes as $type) {
                Cache::forget("portfolio_api_{$type}_*");
            }

            Log::info('Portfolio cache cleared');

            return response()->json([
                'success' => true,
                'message' => 'Portfolio cache cleared successfully',
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to clear cache'], 500);
        }
    }

    /**
     * Health check endpoint
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
                    'database' => 'connected',
                ],
                'performance' => [
                    'memory_usage' => memory_get_usage(true),
                    'peak_memory' => memory_get_peak_usage(true),
                    'cache_status' => Cache::store()->getStore() ? 'connected' : 'disconnected',
                ],
                'version' => '2.0.0'
            ];

            return response()->json($health);
        } catch (\Exception $e) {
            Log::error('Health check failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'unhealthy',
                'error' => 'Service temporarily unavailable',
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Get enhanced profile data
     */
    private function getEnhancedProfileData()
    {
        return [
            // Personal Information
            'name' => 'Nurfauzan Hanif',
            'title' => 'Informatics Engineer',
            'summary' => 'Fresh Graduate of Informatics Engineering at Universitas Islam Riau with a strong foundation in full-stack development, AI/ML, and data analysis. Graduated with a 3.76 GPA and equipped with hands-on experience in modern web technologies, mobile development, and research methodologies. Open to career opportunities where I can contribute my technical expertise, problem-solving skills, and passion for innovation to deliver impactful solutions.',

            // Contact Information
            'email' => 'nrfznhnf@gmail.com',
            'phone' => '0822-8568-5679',
            'location' => 'Pekanbaru, Riau, Indonesia',
            'github' => 'https://github.com/nurfznhanif',
            'linkedin' => 'https://linkedin.com/in/nurfauzan-hanif',
            'portfolio_url' => config('app.url'),

            // Professional Images
            'photo' => '/images/poto1.png',
            'avatar' => '/images/poto1.png',
            'cover_image' => '/images/cover-bg.jpg',

            // Academic Information
            'education' => [
                'degree' => 'S1 Teknik Informatika',
                'university' => 'Universitas Islam Riau',
                'faculty' => 'Fakultas Teknik',
                'gpa' => '3.76',
                'status' => 'Final Year Student',
                'graduation_year' => '2025',
                'start_year' => '2021',
                'thesis' => 'Analisis Sentimen Berbasis Aspek terhadap Diskursus Generasi Emas 2045 di Media Sosial menggunakan Latent Dirichlet Allocation dan Bidirectional Encoder Representations from Transformers (BERT)',
                'thesis_en' => 'Aspect-Based Sentiment Analysis of Golden Generation 2045 Discourse on Social Media using Latent Dirichlet Allocation and BERT',
                'research_interests' => [
                    'Natural Language Processing',
                    'Sentiment Analysis',
                    'Machine Learning',
                    'Social Media Analytics'
                ]
            ],

            // Professional Interests & Expertise Areas
            'interests' => [
                'Full-Stack Web Development',
                'Artificial Intelligence & ML',
                'Data Science & Analytics',
                'Mobile App Development',
                'API Development',
                'Database Design',
                'Teaching & Mentoring',
                'Research & Innovation',
                'Natural Language Processing',
                'Computer Vision'
            ],

            // Core Technical Competencies
            'core_skills' => [
                'Laravel & PHP Development',
                'Vue.js & JavaScript',
                'Python & AI/ML Libraries',
                'Database Management (MySQL, PostgreSQL)',
                'RESTful API Design',
                'Git Version Control',
                'Teaching & Knowledge Transfer',
                'Project Management',
                'Research Methodology',
                'Problem Solving'
            ],

            // Language Proficiencies
            'languages' => [
                [
                    'name' => 'Bahasa Indonesia',
                    'level' => 'Native',
                    'proficiency' => 100,
                    'description' => 'Native speaker'
                ],
                [
                    'name' => 'English',
                    'level' => 'Intermediate',
                    'proficiency' => 70,
                    'description' => 'Technical documentation, academic papers'
                ],
                [
                    'name' => 'Arabic',
                    'level' => 'Basic',
                    'proficiency' => 40,
                    'description' => 'Religious studies, basic conversation'
                ]
            ],

            // Professional Status & Availability
            'availability' => [
                'status' => 'Available for Opportunities',
                'type' => 'Full-time, Part-time, Internship',
                'start_date' => '2025-01-01',
                'looking_for' => [
                    'Software Developer Intern',
                    'Full-Stack Developer',
                    'Research Assistant',
                    'Lecturer Assistant',
                    'Junior Data Analyst'
                ],
                'preferred_roles' => [
                    'Backend Developer (Laravel, PHP)',
                    'Frontend Developer (Vue.js, React)',
                    'Full-Stack Web Developer',
                    'AI/ML Engineer Intern',
                    'Research & Development'
                ],
                'work_preferences' => [
                    'Remote Work',
                    'Hybrid Work',
                    'On-site (Pekanbaru, Riau)',
                    'Flexible Schedule'
                ]
            ],

            // Social Media & Professional Links
            'social_links' => [
                'github' => 'https://github.com/nurfznhanif',
                'linkedin' => 'https://linkedin.com/in/nurfauzan-hanif',
                'email' => 'nrfznhnf@gmail.com',
                'whatsapp' => 'https://wa.me/6282285685679',
                'telegram' => '@nurfznhanif',
            ],

            // Career Objectives & Goals
            'objectives' => [
                'short_term' => 'Complete undergraduate degree with honors and gain practical industry experience through internships or part-time work.',
                'medium_term' => 'Develop expertise in AI/ML applications and contribute to meaningful projects in tech industry.',
                'long_term' => 'Become a senior software engineer specializing in AI-driven applications and contribute to open-source community.',
                'passion' => 'Building technology solutions that make a positive impact on society and mentoring the next generation of developers.'
            ],

            // Personal Qualities & Soft Skills
            'qualities' => [
                'Leadership' => 'Led programming study groups and managed organizational activities',
                'Communication' => 'Experience teaching and presenting technical concepts to diverse audiences',
                'Problem Solving' => 'Strong analytical thinking with research background',
                'Teamwork' => 'Collaborative approach in academic and project environments',
                'Adaptability' => 'Quick learner who stays current with technology trends',
                'Time Management' => 'Successfully balancing studies, teaching, and personal projects'
            ],

            // Achievements & Recognition
            'achievements' => [
                [
                    'title' => 'High Academic Performance',
                    'description' => 'Maintaining GPA 3.76 while serving as Teaching Assistant',
                    'date' => '2021-2025',
                    'category' => 'Academic'
                ],
                [
                    'title' => 'Lecturer Assistant Role',
                    'description' => 'Selected as TA for multiple programming courses',
                    'date' => '2023-Present',
                    'category' => 'Professional'
                ],
                [
                    'title' => 'Research Publication (In Progress)',
                    'description' => 'Working on thesis research in NLP and sentiment analysis',
                    'date' => '2024-2025',
                    'category' => 'Research'
                ]
            ],

            // Fun Facts & Personal Touch
            'fun_facts' => [
                '🎓 Teaching programming to fellow students since 2023',
                '💻 Built multiple web applications using modern frameworks',
                '📊 Passionate about data analysis and visualization',
                '🤖 Currently researching AI applications in social media',
                '📚 Enjoys reading about emerging technologies',
                '🌱 Always learning new programming languages and frameworks'
            ],

            // Current Focus & Projects
            'current_focus' => [
                'Completing final year thesis research',
                'Lecturer assistant responsibilities',
                'Building portfolio projects',
                'Learning advanced AI/ML techniques',
                'Contributing to open-source projects',
                'Preparing for industry transition'
            ],

            // Certifications & Courses (if any)
            'certifications' => [
                // Add any relevant certifications here
                [
                    'name' => 'Web Development Fundamentals',
                    'issuer' => 'Self-Study & Practice',
                    'date' => '2023',
                    'skills' => ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel']
                ],
                [
                    'name' => 'Database Management',
                    'issuer' => 'University Coursework',
                    'date' => '2023',
                    'skills' => ['MySQL', 'PostgreSQL', 'Database Design']
                ]
            ],

            // Technical Stack Preferences
            'tech_stack' => [
                'backend' => ['Laravel', 'PHP', 'Node.js', 'Python'],
                'frontend' => ['Vue.js', 'JavaScript', 'HTML5', 'CSS3', 'Tailwind CSS'],
                'database' => ['MySQL', 'PostgreSQL', 'SQLite'],
                'tools' => ['Git', 'VS Code', 'Postman', 'Docker (learning)'],
                'deployment' => ['cPanel', 'Heroku', 'Vercel'],
                'ai_ml' => ['Python', 'TensorFlow', 'Scikit-learn', 'Pandas', 'NumPy']
            ],

            // Contact Preferences
            'contact_preferences' => [
                'best_time' => 'Monday-Friday, 9AM-5PM WIB',
                'response_time' => 'Within 24 hours',
                'preferred_method' => 'Email or WhatsApp',
                'languages' => 'Indonesian, English',
                'timezone' => 'Asia/Jakarta (WIB, UTC+7)'
            ]
        ];
    }

    /**
     * Calculate comprehensive portfolio statistics
     */
    private function calculatePortfolioStatistics($data)
    {
        $portfolios = $data['portfolios'];
        $experiences = $data['experiences'];
        $skills = $data['skills'];

        // Calculate years of experience
        $firstExperience = $experiences->sortBy('start_date')->first();
        $yearsOfExperience = $firstExperience
            ? now()->diffInYears($firstExperience->start_date)
            : 0;

        // Project statistics
        $projectTypes = $portfolios->groupBy('project_type');
        $technologies = [];
        $recentProjects = $portfolios->where('project_date', '>=', now()->subMonths(6));

        // Technology usage analysis
        foreach ($portfolios as $portfolio) {
            $techs = array_map('trim', explode(',', $portfolio->technologies ?? ''));
            foreach ($techs as $tech) {
                if (!empty($tech)) {
                    $technologies[$tech] = ($technologies[$tech] ?? 0) + 1;
                }
            }
        }

        // Sort technologies by usage
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

        // Experience analysis
        $currentPositions = $experiences->where('is_current', true);
        $totalExperience = $experiences->count();

        return [
            'overview' => [
                'total_projects' => $portfolios->count(),
                'years_experience' => max(1, $yearsOfExperience),
                'total_skills' => $totalSkills,
                'skill_categories' => $skills->count(),
                'current_positions' => $currentPositions->count(),
                'advanced_skills' => $advancedSkills,
                'skill_proficiency' => $totalSkills > 0 ? round(($advancedSkills / $totalSkills) * 100) : 0
            ],

            'projects' => [
                'by_type' => $projectTypes->map->count()->toArray(),
                'recent_count' => $recentProjects->count(),
                'latest' => $portfolios->first(),
                'featured' => $portfolios->take(6),
                'completion_rate' => 100 // Assuming all listed projects are completed
            ],

            'technologies' => [
                'all' => $technologies,
                'top_10' => $topTechnologies,
                'most_used' => array_key_first($topTechnologies),
                'diversity_score' => count($technologies),
                'usage_distribution' => $this->calculateTechDistribution($technologies)
            ],

            'experience' => [
                'total_positions' => $totalExperience,
                'current_roles' => $currentPositions->pluck('position')->toArray(),
                'organizations' => $experiences->pluck('organization')->unique()->count(),
                'career_progression' => $this->analyzeCareerProgression($experiences),
                'latest_position' => $experiences->first()
            ],

            'skills' => [
                'by_category' => $skillStats,
                'proficiency_breakdown' => [
                    'advanced' => $advancedSkills,
                    'intermediate' => array_sum(array_column($skillStats, 'intermediate')),
                    'beginner' => array_sum(array_column($skillStats, 'beginner')),
                ],
                'growth_areas' => $this->identifyGrowthAreas($skillStats)
            ],

            'performance_metrics' => [
                'portfolio_completeness' => $this->calculatePortfolioCompleteness($portfolios),
                'project_complexity' => $this->assessProjectComplexity($portfolios),
                'technology_modernness' => $this->assessTechnologyModernness($technologies),
                'career_momentum' => $this->calculateCareerMomentum($experiences)
            ],

            'generated_at' => now()->toISOString(),
            'cache_duration' => 3600 // 1 hour
        ];
    }

    /**
     * Generate comprehensive meta data for SEO and social sharing
     */
    private function generateMetaData($profile, $statistics)
    {
        $skillCount = $statistics['overview']['total_skills'];
        $projectCount = $statistics['overview']['total_projects'];
        $gpa = $profile['education']['gpa'];

        return [
            'title' => "{$profile['name']} - {$profile['title']} | Portfolio",
            'description' => "Passionate Computer Science student with {$projectCount}+ projects, {$skillCount}+ skills, and {$gpa} GPA. Specializing in full-stack development, AI/ML, and data analysis. Currently seeking opportunities in software development.",
            'keywords' => implode(', ', array_merge(
                $profile['core_skills'],
                array_slice($profile['interests'], 0, 5),
                ['Computer Science Student', 'Teaching Assistant', 'Pekanbaru', 'Indonesia']
            )),
            'author' => $profile['name'],
            'type' => 'portfolio',
            'url' => config('app.url'),
            'image' => asset($profile['photo']),
            'image_alt' => "{$profile['name']} - Computer Science Student",
            'locale' => 'id_ID',
            'site_name' => "{$profile['name']} Portfolio",
            'twitter_card' => 'summary_large_image',
            'og_type' => 'profile',
            'canonical_url' => config('app.url'),

            // Structured data for search engines
            'structured_data' => [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => $profile['name'],
                'jobTitle' => $profile['title'],
                'description' => $profile['summary'],
                'url' => config('app.url'),
                'image' => asset($profile['photo']),
                'email' => $profile['email'],
                'telephone' => $profile['phone'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Pekanbaru',
                    'addressRegion' => 'Riau',
                    'addressCountry' => 'Indonesia'
                ],
                'alumniOf' => [
                    '@type' => 'EducationalOrganization',
                    'name' => $profile['education']['university']
                ],
                'knows' => $profile['core_skills'],
                'sameAs' => array_values($profile['social_links'])
            ]
        ];
    }

    /**
     * Fallback method when cache fails
     */
    private function fallbackPortfolioData()
    {
        $basicProfile = [
            'name' => 'Nurfauzan Hanif',
            'title' => 'Computer Science Student',
            'email' => 'nrfznhnf@gmail.com',
            'education' => ['gpa' => '3.76']
        ];

        return Inertia::render('Portfolio/Index', [
            'portfolios' => [],
            'experiences' => [],
            'skills' => [],
            'profile' => $basicProfile,
            'statistics' => ['overview' => ['total_projects' => 0]],
            'meta' => ['title' => 'Portfolio - Nurfauzan Hanif'],
            'error' => 'Some data may be temporarily unavailable.'
        ]);
    }

    /**
     * Helper methods for statistics calculation
     */
    private function calculateTechDistribution($technologies)
    {
        $total = array_sum($technologies);
        $distribution = [];

        foreach ($technologies as $tech => $count) {
            $distribution[$tech] = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        }

        return $distribution;
    }

    private function analyzeCareerProgression($experiences)
    {
        $progression = [];
        $sortedExperiences = $experiences->sortBy('start_date');

        foreach ($sortedExperiences as $index => $experience) {
            $progression[] = [
                'position' => $experience->position,
                'organization' => $experience->organization,
                'start_date' => $experience->start_date,
                'is_current' => $experience->is_current,
                'order' => $index + 1
            ];
        }

        return $progression;
    }

    private function identifyGrowthAreas($skillStats)
    {
        $growthAreas = [];

        foreach ($skillStats as $category => $stats) {
            $beginnerRatio = $stats['total'] > 0 ? ($stats['beginner'] / $stats['total']) * 100 : 0;

            if ($beginnerRatio > 50) {
                $growthAreas[] = [
                    'category' => $category,
                    'priority' => 'high',
                    'reason' => 'High beginner skill ratio'
                ];
            }
        }

        return $growthAreas;
    }

    private function calculatePortfolioCompleteness($portfolios)
    {
        $completenessFactors = 0;
        $totalFactors = 6;

        if ($portfolios->count() > 0) $completenessFactors++;
        if ($portfolios->where('github_url', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->where('challenges', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->where('solutions', '!=', null)->count() > 0) $completenessFactors++;
        if ($portfolios->count() >= 5) $completenessFactors++;
        if ($portfolios->where('project_date', '>=', now()->subYear())->count() > 0) $completenessFactors++;

        return round(($completenessFactors / $totalFactors) * 100);
    }

    private function assessProjectComplexity($portfolios)
    {
        $complexityScore = 0;

        foreach ($portfolios as $portfolio) {
            $techCount = count(explode(',', $portfolio->technologies ?? ''));
            $hasGithub = !empty($portfolio->github_url);
            $hasChallenges = !empty($portfolio->challenges);
            $hasSolutions = !empty($portfolio->solutions);

            $projectScore = ($techCount * 10) + ($hasGithub ? 20 : 0) +
                ($hasChallenges ? 15 : 0) + ($hasSolutions ? 15 : 0);

            $complexityScore += min($projectScore, 100);
        }

        return $portfolios->count() > 0 ? round($complexityScore / $portfolios->count()) : 0;
    }

    private function assessTechnologyModernness($technologies)
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
            'Tailwind CSS'
        ];

        $modernTechCount = 0;
        foreach ($technologies as $tech => $count) {
            if (in_array($tech, $modernTechs)) {
                $modernTechCount += $count;
            }
        }

        $totalTechUsage = array_sum($technologies);
        return $totalTechUsage > 0 ? round(($modernTechCount / $totalTechUsage) * 100) : 0;
    }

    private function calculateCareerMomentum($experiences)
    {
        $momentum = 0;
        $currentPositions = $experiences->where('is_current', true)->count();
        $recentExperiences = $experiences->where('start_date', '>=', now()->subYear())->count();

        $momentum += ($currentPositions * 30);
        $momentum += ($recentExperiences * 20);
        $momentum += ($experiences->count() * 10);

        return min($momentum, 100);
    }

    /**
     * Simulate email sending (replace with actual email service)
     */
    private function simulateEmailSending($data, $request)
    {
        // This is where you would integrate with actual email services
        // Examples:
        // - Laravel Mail with SMTP
        // - SendGrid API
        // - Mailgun API
        // - Amazon SES

        Log::info('Email simulation - Contact form', [
            'to' => 'nrfznhnf@gmail.com',
            'subject' => $data['subject'] ?? 'Portfolio Contact',
            'from' => $data['email'],
            'sender_name' => $data['name'],
            'message_preview' => substr($data['message'], 0, 100) . '...',
            'timestamp' => now()
        ]);

        return true;
    }
}
