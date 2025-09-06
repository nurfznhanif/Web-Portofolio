<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\PortfolioAnalytic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_projects' => Portfolio::count(),
            'total_experiences' => Experience::count(),
            'total_skills' => Skill::count(),
            'total_page_views' => PortfolioAnalytic::where('event_type', 'page_view')->count(),
        ];

        // Recent analytics data
        $recentViews = PortfolioAnalytic::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $projectViews = PortfolioAnalytic::where('event_type', 'project_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        $contactForms = PortfolioAnalytic::where('event_type', 'contact_form_submit')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        return Inertia::render('Admin/Dashboard/Index', [
            'stats' => $stats,
            'recentViews' => $recentViews,
            'projectViews' => $projectViews,
            'contactForms' => $contactForms,
        ]);
    }
}
