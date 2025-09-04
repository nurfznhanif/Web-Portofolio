<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PortfolioPublicController extends Controller
{
    /**
     * Display the main portfolio page
     */
    public function index()
    {
        // Cache the portfolio data for better performance (cache for 1 hour)
        $portfolioData = Cache::remember('portfolio_public_data', 3600, function () {
            return [
                'portfolios' => Portfolio::orderBy('project_date', 'desc')->get(),
                'experiences' => Experience::orderBy('start_date', 'desc')->get(),
                'skills' => Skill::orderBy('category')->orderBy('name')->get()->groupBy('category'),
            ];
        });

        // Enhanced profile data based on CV and portfolio documents
        $profile = [
            // Personal Information
            'name' => 'Nurfauzan Hanif',
            'title' => 'Computer Science Student & Teaching Assistant',
            'summary' => 'Final year Computer Science student at Universitas Islam Riau with GPA 3.76, specializing in full-stack web development, artificial intelligence, and data analysis. Experienced as Teaching Assistant for multiple programming courses and active in student organizations. Passionate about modern web technologies, machine learning, and sharing knowledge through teaching.',

            // Contact Information (from CV)
            'email' => 'nrfznhnf@gmail.com',
            'phone' => '0822-8568-5679',
            'location' => 'Pekanbaru, Riau, Indonesia',
            'github' => 'https://github.com/nurfznhanif',
            'linkedin' => 'https://linkedin.com/in/nurfauzan-hanif', // Optional

            // Professional Image
            'photo' => '/images/poto1.png',
            'avatar' => '/images/avatar.png', // Smaller version for nav

            // Academic Information
            'education' => [
                'degree' => 'S1 Teknik Informatika',
                'university' => 'Universitas Islam Riau',
                'faculty' => 'Fakultas Teknik',
                'gpa' => '3.76',
                'status' => 'Final Year Student',
                'graduation_year' => '2025',
                'thesis' => 'Analisis Sentimen Berbasis Aspek terhadap Diskursus Generasi Emas 2045 di Media Sosial menggunakan Latent Dirichlet Allocation dan Bidirectional Encoder Representations from Transformers (BERT)',
                'thesis_en' => 'Aspect-Based Sentiment Analysis of Golden Generation 2045 Discourse on Social Media using Latent Dirichlet Allocation and Bidirectional Encoder Representations from Transformers (BERT)'
            ],

            // Professional Interests & Expertise
            'interests' => [
                'Web Development',
                'Full-Stack Development',
                'Artificial Intelligence',
                'Machine Learning',
                'Data Analysis',
                'Mobile Programming',
                'Teaching & Mentoring',
                'Research',
                'Natural Language Processing',
                'Computer Vision'
            ],

            // Core Competencies
            'core_skills' => [
                'Full-Stack Development',
                'API Development',
                'Database Design',
                'AI/ML Implementation',
                'Teaching & Mentoring',
                'Team Leadership',
                'Project Management',
                'Research & Analysis'
            ],

            // Languages
            'languages' => [
                [
                    'name' => 'Bahasa Indonesia',
                    'level' => 'Native',
                    'proficiency' => 100
                ],
                [
                    'name' => 'English',
                    'level' => 'Basic',
                    'proficiency' => 60
                ]
            ],

            // Certifications & Achievements (if any)
            'certifications' => [
                // Add any certifications here
            ],

            // Current Status
            'availability' => [
                'status' => 'Available for Internship/Part-time',
                'looking_for' => ['Internship', 'Part-time Work', 'Research Collaboration'],
                'preferred_roles' => ['Full-Stack Developer', 'Backend Developer', 'AI/ML Intern', 'Research Assistant']
            ],

            // Social Media & Professional Links
            'social_links' => [
                'github' => 'https://github.com/nurfznhanif',
                'email' => 'nrfznhnf@gmail.com',
                // Add other social media links as needed
            ],

            // Career Objectives
            'objectives' => 'Seeking opportunities to apply my technical skills in real-world projects while continuing to grow as a developer and researcher. Particularly interested in positions that combine web development with AI/ML technologies and offer mentoring opportunities.',

            // Fun Facts
            'fun_facts' => [
                'Teaches programming to fellow students',
                'Led multiple student organizations',
                'Completed thesis research on AI and social media analysis',
                'Experienced in both frontend and backend development',
                'Active contributor to open-source projects'
            ]
        ];

        // Calculate additional statistics
        $statistics = $this->calculateStatistics($portfolioData);

        return Inertia::render('Portfolio/Index', [
            'portfolios' => $portfolioData['portfolios'],
            'experiences' => $portfolioData['experiences'],
            'skills' => $portfolioData['skills'],
            'profile' => $profile,
            'statistics' => $statistics,
            'meta' => $this->getMetaData($profile)
        ]);
    }

    /**
     * Get portfolio data for API endpoint
     */
    public function api(Request $request)
    {
        $portfolioData = Cache::remember('portfolio_api_data', 3600, function () {
            return [
                'portfolios' => Portfolio::select('id', 'title', 'description', 'technologies', 'project_type', 'project_date', 'github_url')
                    ->orderBy('project_date', 'desc')
                    ->get(),
                'experiences' => Experience::select('id', 'position', 'organization', 'description', 'start_date', 'end_date', 'is_current')
                    ->orderBy('start_date', 'desc')
                    ->get(),
                'skills' => Skill::select('id', 'name', 'category', 'level')
                    ->orderBy('category')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('category'),
            ];
        });

        return response()->json($portfolioData);
    }

    /**
     * Download CV/Resume
     */
    public function downloadCV()
    {
        $filePath = public_path('documents/Nurfauzan_Hanif_CV.pdf');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Nurfauzan_Hanif_CV.pdf');
        }

        abort(404, 'CV file not found');
    }

    /**
     * Contact form submission
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Here you could send email, save to database, etc.
        // For now, we'll just return a success response

        // Optional: Log the contact attempt
        \Log::info('Contact form submission', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'Portfolio Contact',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message! I will get back to you soon.'
        ]);
    }

    /**
     * Calculate portfolio statistics
     */
    private function calculateStatistics($data)
    {
        $portfolios = $data['portfolios'];
        $experiences = $data['experiences'];
        $skills = $data['skills'];

        // Calculate years of experience
        $firstExperience = $experiences->sortBy('start_date')->first();
        $yearsOfExperience = $firstExperience
            ? now()->diffInYears($firstExperience->start_date)
            : 0;

        // Calculate project statistics
        $projectTypes = $portfolios->groupBy('project_type');
        $technologies = [];

        foreach ($portfolios as $portfolio) {
            $techs = explode(', ', $portfolio->technologies);
            foreach ($techs as $tech) {
                $tech = trim($tech);
                $technologies[$tech] = ($technologies[$tech] ?? 0) + 1;
            }
        }

        // Most used technologies (top 5)
        arsort($technologies);
        $topTechnologies = array_slice($technologies, 0, 5, true);

        // Skills statistics
        $skillStats = [];
        foreach ($skills as $category => $skillList) {
            $skillStats[$category] = [
                'total' => $skillList->count(),
                'advanced' => $skillList->where('level', 'mahir')->count(),
                'intermediate' => $skillList->where('level', 'menengah')->count(),
                'beginner' => $skillList->where('level', 'pemula')->count(),
            ];
        }

        return [
            'total_projects' => $portfolios->count(),
            'years_experience' => $yearsOfExperience,
            'total_skills' => $skills->flatten()->count(),
            'skill_categories' => $skills->count(),
            'current_positions' => $experiences->where('is_current', true)->count(),
            'project_types' => $projectTypes->keys()->toArray(),
            'top_technologies' => $topTechnologies,
            'skill_breakdown' => $skillStats,
            'latest_project' => $portfolios->first(),
            'current_experience' => $experiences->where('is_current', true)->first()
        ];
    }

    /**
     * Get meta data for SEO
     */
    private function getMetaData($profile)
    {
        return [
            'title' => $profile['name'] . ' - ' . $profile['title'],
            'description' => $profile['summary'],
            'keywords' => implode(', ', $profile['core_skills']) . ', ' . implode(', ', $profile['interests']),
            'author' => $profile['name'],
            'type' => 'portfolio',
            'url' => url('/'),
            'image' => asset($profile['photo']),
            'locale' => 'en_US',
            'site_name' => $profile['name'] . ' Portfolio'
        ];
    }

    /**
     * Get filtered projects by type
     */
    public function projectsByType(Request $request, $type)
    {
        $projects = Cache::remember("projects_by_type_{$type}", 1800, function () use ($type) {
            return Portfolio::where('project_type', $type)
                ->orderBy('project_date', 'desc')
                ->get();
        });

        if ($request->expectsJson()) {
            return response()->json($projects);
        }

        return Inertia::render('Portfolio/ProjectsByType', [
            'projects' => $projects,
            'type' => $type,
            'total' => $projects->count()
        ]);
    }

    /**
     * Show single project details
     */
    public function showProject(Request $request, $id)
    {
        $project = Portfolio::findOrFail($id);

        // Get related projects (same type)
        $relatedProjects = Portfolio::where('project_type', $project->project_type)
            ->where('id', '!=', $project->id)
            ->orderBy('project_date', 'desc')
            ->limit(3)
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'project' => $project,
                'related' => $relatedProjects
            ]);
        }

        return Inertia::render('Portfolio/ProjectDetail', [
            'project' => $project,
            'related' => $relatedProjects
        ]);
    }

    /**
     * Search projects
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([]);
        }

        $projects = Portfolio::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('technologies', 'like', "%{$query}%")
            ->orderBy('project_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json($projects);
    }

    /**
     * Get skills by category
     */
    public function skillsByCategory(Request $request, $category)
    {
        $skills = Cache::remember("skills_category_{$category}", 1800, function () use ($category) {
            return Skill::where('category', $category)
                ->orderBy('name')
                ->get();
        });

        if ($request->expectsJson()) {
            return response()->json($skills);
        }

        return response()->json($skills);
    }

    /**
     * Get experience timeline
     */
    public function experienceTimeline(Request $request)
    {
        $experiences = Experience::orderBy('start_date', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json($experiences);
        }

        return response()->json($experiences);
    }

    /**
     * Health check for portfolio
     */
    public function health()
    {
        try {
            $portfolioCount = Portfolio::count();
            $experienceCount = Experience::count();
            $skillCount = Skill::count();

            return response()->json([
                'status' => 'healthy',
                'data' => [
                    'portfolios' => $portfolioCount,
                    'experiences' => $experienceCount,
                    'skills' => $skillCount
                ],
                'timestamp' => now()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Portfolio data unavailable',
                'timestamp' => now()
            ], 500);
        }
    }

    /**
     * Clear portfolio cache (admin only)
     */
    public function clearCache()
    {
        Cache::forget('portfolio_public_data');
        Cache::forget('portfolio_api_data');

        // Clear all project type caches
        $projectTypes = Portfolio::distinct()->pluck('project_type');
        foreach ($projectTypes as $type) {
            Cache::forget("projects_by_type_{$type}");
        }

        // Clear skill category caches
        $skillCategories = Skill::distinct()->pluck('category');
        foreach ($skillCategories as $category) {
            Cache::forget("skills_category_{$category}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Portfolio cache cleared successfully'
        ]);
    }
}
