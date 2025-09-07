<?php
// app/Http/Controllers/PortfolioPublicController.php
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
use App\Models\PortfolioAnalytic;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioPublicController extends Controller
{
    public function index()
    {
        // Track page view
        PortfolioAnalytic::track('page_view', 'portfolio_home');

        // Get profile data
        $profile = ProfileSetting::first() ?? $this->getDefaultProfile();

        // Get portfolio projects
        $portfolios = Portfolio::ordered()->get();

        // Get experiences
        $experiences = Experience::ordered()->get();

        // Get skills grouped by category
        $skills = Skill::getGroupedSkills();

        // Get social links
        $socialLinks = SocialLink::active()->ordered()->get();

        // Get achievements
        $achievements = Achievement::orderBy('date_achieved', 'desc')->get();

        // Get languages
        $languages = Language::all();

        // Get interests
        $interests = Interest::all();

        // Get certifications
        $certifications = Certification::orderBy('issue_date', 'desc')->get();

        // Calculate statistics
        $statistics = [
            'overview' => [
                'total_projects' => $portfolios->count(),
                'years_experience' => $this->calculateYearsOfExperience($experiences),
                'total_skills' => $skills->flatten()->count(),
                'total_achievements' => $achievements->count(),
            ],
            'projects_by_type' => $portfolios->groupBy('project_type')->map->count(),
            'skills_by_category' => $skills->map->count(),
        ];

        // Meta data for SEO
        $meta = [
            'title' => $profile->name . ' - Portfolio',
            'description' => $profile->summary,
            'keywords' => 'web developer, fullstack developer, laravel, vue.js, portfolio',
            'author' => $profile->name,
            'image' => $profile->photo_url,
        ];

        // Configuration
        $config = [
            'enable_contact_form' => true,
            'enable_analytics' => true,
            'enable_cv_download' => true,
        ];

        return Inertia::render('Portfolio/Index', [
            'profile' => $profile,
            'portfolios' => $portfolios,
            'experiences' => $experiences,
            'skills' => $skills,
            'socialLinks' => $socialLinks,
            'achievements' => $achievements,
            'languages' => $languages,
            'interests' => $interests,
            'certifications' => $certifications,
            'statistics' => $statistics,
            'meta' => $meta,
            'config' => $config,
        ]);
    }

    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
            'honeypot' => 'nullable|string|max:0', // Spam protection
        ]);

        // Check honeypot
        if ($request->honeypot) {
            return response()->json(['success' => false, 'message' => 'Spam detected.'], 422);
        }

        // Rate limiting (basic implementation)
        $ip = $request->ip();
        $recentContacts = PortfolioAnalytic::where('event_type', 'contact_form_submit')
            ->where('ip_address', $ip)
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();

        if ($recentContacts >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Too many contact attempts. Please try again later.'
            ], 429);
        }

        // Track contact form submission
        PortfolioAnalytic::track('contact_form_submit', 'contact', [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'has_subject' => !empty($request->subject),
        ]);

        // Here you could send email, save to database, etc.
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message! I will get back to you soon.',
        ]);
    }

    public function downloadCV()
    {
        // Track CV download
        PortfolioAnalytic::track('cv_download', 'cv');

        $profile = ProfileSetting::first();
        $fileName = ($profile ? str_replace(' ', '_', $profile->name) : 'CV') . '.pdf';
        $filePath = storage_path('app/public/cv/' . $fileName);

        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName);
        }

        // Return 404 if CV file doesn't exist
        abort(404, 'CV file not found');
    }

    public function trackAnalytics(Request $request)
    {
        $request->validate([
            'event_type' => 'required|string',
            'page' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        PortfolioAnalytic::track(
            $request->event_type,
            $request->page,
            $request->data
        );

        return response()->json(['success' => true]);
    }

    private function getDefaultProfile()
    {
        return (object) [
            'name' => 'Nurfauzan Hanif',
            'title' => 'Title',
            'summary' => 'Summary',
            'email' => 'nrfznhnf@gmail.com',
            'phone' => '0822-8568-5679',
            'location' => 'Pekanbaru, Riau',
            'photo' => null,
            'education' => [
                'degree' => 'Bachelor of Informatics Engineering',
                'university' => 'Universitas Islam Riau',
                'gpa' => '3.76',
                'graduation_year' => '2025',
            ],
            'thesis_title' => 'Aspect-Based Sentiment Analysis of the 2045 Golden Generation Discourse on Social Media Using Latent Dirichlet Allocation and Bidirectional Encoder Representation Transformers',
            'availability' => [
                'status' => 'Available for opportunities',
                'type' => 'full-time',
            ],
            'photo_url' => asset('images/default-avatar.png'),
        ];
    }

    private function calculateYearsOfExperience($experiences)
    {
        if ($experiences->isEmpty()) {
            return 1;
        }

        $earliestStart = $experiences->min('start_date');
        return now()->diffInYears($earliestStart) ?: 1;
    }
}
