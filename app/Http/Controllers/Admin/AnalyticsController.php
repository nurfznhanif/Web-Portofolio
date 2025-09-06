<?php
// app/Http/Controllers/Admin/AnalyticsController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioAnalytic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_views' => PortfolioAnalytic::where('event_type', 'page_view')->count(),
            'total_project_views' => PortfolioAnalytic::where('event_type', 'project_view')->count(),
            'total_contacts' => PortfolioAnalytic::where('event_type', 'contact_form_submit')->count(),
            'total_cv_downloads' => PortfolioAnalytic::where('event_type', 'cv_download')->count(),
        ];

        // Daily views for the last 30 days
        $dailyViews = PortfolioAnalytic::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Analytics/Dashboard', [
            'stats' => $stats,
            'dailyViews' => $dailyViews,
        ]);
    }

    public function visitors()
    {
        $visitors = PortfolioAnalytic::where('event_type', 'page_view')
            ->selectRaw('ip_address, COUNT(*) as visits, MAX(created_at) as last_visit')
            ->groupBy('ip_address')
            ->orderBy('last_visit', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Analytics/Visitors', [
            'visitors' => $visitors
        ]);
    }

    public function projects()
    {
        $projectViews = PortfolioAnalytic::where('event_type', 'project_view')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.project_title")) as project_title, COUNT(*) as views')
            ->groupBy('project_title')
            ->orderBy('views', 'desc')
            ->get();

        return Inertia::render('Admin/Analytics/Projects', [
            'projectViews' => $projectViews
        ]);
    }

    public function contactForms()
    {
        $contacts = PortfolioAnalytic::where('event_type', 'contact_form_submit')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Admin/Analytics/ContactForms', [
            'contacts' => $contacts
        ]);
    }
}
