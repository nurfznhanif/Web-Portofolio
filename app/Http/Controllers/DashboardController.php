<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\ContactMessage;
use App\Models\PortfolioAnalytic;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'portfolios' => Portfolio::count(),
            'experiences' => Experience::count(),
            'skills' => Skill::count(),
            'recent_portfolios' => Portfolio::latest()->take(5)->get(['id', 'title', 'project_type', 'created_at']),
        ];

        // Analytics data (optional - untuk preview analytics)
        $analytics = [
            'total_views' => PortfolioAnalytic::where('event_type', 'page_view')->count(),
            'unique_visitors' => PortfolioAnalytic::where('event_type', 'page_view')->distinct('ip_address')->count(),
            'cv_downloads' => PortfolioAnalytic::where('event_type', 'cv_download')->count(),
            'contact_forms' => ContactMessage::count(),
        ];

        // Recent activity (mock data - bisa diganti dengan real activity log)
        $recentActivity = [
            [
                'id' => 1,
                'type' => 'portfolio_created',
                'title' => 'New project added',
                'description' => 'Portfolio website project created',
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 2,
                'type' => 'experience_added',
                'title' => 'Experience updated',
                'description' => 'Work experience at Company X added',
                'created_at' => now()->subDays(1),
            ],
            [
                'id' => 3,
                'type' => 'skill_added',
                'title' => 'New skill added',
                'description' => 'Laravel framework skill added',
                'created_at' => now()->subDays(2),
            ],
        ];

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'analytics' => $analytics,
            'recentActivity' => $recentActivity,
        ]);
    }
}
