<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioAnalytic;
use App\Models\ContactMessage;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        return $this->dashboard();
    }

    /**
     * Display main analytics dashboard
     */
    public function dashboard(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        // Get overview statistics
        $overview = $this->getOverviewStats($startDate, $endDate);

        // Get chart data for different metrics
        $chartData = $this->getChartData($startDate, $endDate);

        // Get top performing content
        $topContent = $this->getTopContent($startDate, $endDate);

        // Get recent activity
        $recentActivity = $this->getRecentActivity();

        return Inertia::render('Admin/Analytics/Dashboard', [
            'overview' => $overview,
            'chartData' => $chartData,
            'topContent' => $topContent,
            'recentActivity' => $recentActivity,
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Display visitor analytics
     */
    public function visitors(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        // Visitor statistics
        $visitorStats = $this->getVisitorStats($startDate, $endDate);

        // Geographic data
        $geographicData = $this->getGeographicData($startDate, $endDate);

        // Device and browser data
        $deviceData = $this->getDeviceData($startDate, $endDate);

        // Traffic sources
        $trafficSources = $this->getTrafficSources($startDate, $endDate);

        return Inertia::render('Admin/Analytics/Visitors', [
            'visitorStats' => $visitorStats,
            'geographicData' => $geographicData,
            'deviceData' => $deviceData,
            'trafficSources' => $trafficSources,
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Display project analytics
     */
    public function projects(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        // Project view statistics
        $projectStats = $this->getProjectStats($startDate, $endDate);

        // Most viewed projects
        $topProjects = $this->getTopProjects($startDate, $endDate);

        // Project performance trends
        $projectTrends = $this->getProjectTrends($startDate, $endDate);

        return Inertia::render('Admin/Analytics/Projects', [
            'projectStats' => $projectStats,
            'topProjects' => $topProjects,
            'projectTrends' => $projectTrends,
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Display contact form analytics
     */
    public function contactForms(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        // Contact form statistics
        $contactStats = $this->getContactStats($startDate, $endDate);

        // Response time analytics
        $responseStats = $this->getResponseStats($startDate, $endDate);

        // Contact trends
        $contactTrends = $this->getContactTrends($startDate, $endDate);

        return Inertia::render('Admin/Analytics/ContactForms', [
            'contactStats' => $contactStats,
            'responseStats' => $responseStats,
            'contactTrends' => $contactTrends,
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Display download analytics
     */
    public function downloads(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        // Download statistics
        $downloadStats = $this->getDownloadStats($startDate, $endDate);

        return Inertia::render('Admin/Analytics/Downloads', [
            'downloadStats' => $downloadStats,
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $days = $request->get('days', 30);
        $format = $request->get('format', 'json');
        $endDate = now();
        $startDate = now()->subDays($days);

        $exportData = [
            'exported_at' => now()->toISOString(),
            'date_range' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'days' => $days
            ],
            'overview' => $this->getOverviewStats($startDate, $endDate),
            'visitors' => $this->getVisitorStats($startDate, $endDate),
            'projects' => $this->getProjectStats($startDate, $endDate),
            'contacts' => $this->getContactStats($startDate, $endDate),
            'downloads' => $this->getDownloadStats($startDate, $endDate)
        ];

        $filename = 'analytics_export_' . date('Y_m_d_H_i_s');

        if ($format === 'csv') {
            return $this->exportAsCsv($exportData, $filename . '.csv');
        }

        return response()->json($exportData)
                        ->header('Content-Disposition', 'attachment; filename="' . $filename . '.json"');
    }

    /**
     * Get real-time stats API
     */
    public function getStats(Request $request)
    {
        $metric = $request->get('metric', 'overview');
        $days = $request->get('days', 7);
        $endDate = now();
        $startDate = now()->subDays($days);

        switch ($metric) {
            case 'visitors':
                return response()->json($this->getVisitorStats($startDate, $endDate));
            case 'projects':
                return response()->json($this->getProjectStats($startDate, $endDate));
            case 'contacts':
                return response()->json($this->getContactStats($startDate, $endDate));
            case 'downloads':
                return response()->json($this->getDownloadStats($startDate, $endDate));
            default:
                return response()->json($this->getOverviewStats($startDate, $endDate));
        }
    }

    /**
     * Get chart data API
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'daily_views');
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        return response()->json($this->getChartData($startDate, $endDate, $type));
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    /**
     * Get overview statistics
     */
    private function getOverviewStats($startDate, $endDate)
    {
        $totalViews = PortfolioAnalytic::where('event_type', 'page_view')
                                      ->whereBetween('created_at', [$startDate, $endDate])
                                      ->count();

        $uniqueVisitors = PortfolioAnalytic::where('event_type', 'page_view')
                                          ->whereBetween('created_at', [$startDate, $endDate])
                                          ->distinct('ip_address')
                                          ->count();

        $projectViews = PortfolioAnalytic::where('event_type', 'project_view')
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->count();

        $contactForms = PortfolioAnalytic::where('event_type', 'contact_form_submission')
                                        ->whereBetween('created_at', [$startDate, $endDate])
                                        ->count();

        $cvDownloads = PortfolioAnalytic::where('event_type', 'cv_download')
                                       ->whereBetween('created_at', [$startDate, $endDate])
                                       ->count();

        // Calculate previous period for comparison
        $previousStart = $startDate->copy()->subDays($endDate->diffInDays($startDate));
        $previousEnd = $startDate->copy();

        $previousViews = PortfolioAnalytic::where('event_type', 'page_view')
                                         ->whereBetween('created_at', [$previousStart, $previousEnd])
                                         ->count();

        $viewsChange = $previousViews > 0 ? (($totalViews - $previousViews) / $previousViews) * 100 : 0;

        return [
            'total_views' => $totalViews,
            'unique_visitors' => $uniqueVisitors,
            'project_views' => $projectViews,
            'contact_forms' => $contactForms,
            'cv_downloads' => $cvDownloads,
            'views_change_percent' => round($viewsChange, 1),
            'bounce_rate' => $this->calculateBounceRate($startDate, $endDate),
            'avg_session_duration' => $this->calculateAvgSessionDuration($startDate, $endDate)
        ];
    }

    /**
     * Get chart data
     */
    private function getChartData($startDate, $endDate, $type = null)
    {
        $dailyViews = PortfolioAnalytic::where('event_type', 'page_view')
                                      ->whereBetween('created_at', [$startDate, $endDate])
                                      ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
                                      ->groupBy('date')
                                      ->orderBy('date')
                                      ->get();

        $dailyVisitors = PortfolioAnalytic::where('event_type', 'page_view')
                                         ->whereBetween('created_at', [$startDate, $endDate])
                                         ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT ip_address) as visitors')
                                         ->groupBy('date')
                                         ->orderBy('date')
                                         ->get();

        return [
            'daily_views' => $dailyViews,
            'daily_visitors' => $dailyVisitors
        ];
    }

    /**
     * Get top performing content
     */
    private function getTopContent($startDate, $endDate)
    {
        $topProjects = PortfolioAnalytic::where('event_type', 'project_view')
                                       ->whereBetween('created_at', [$startDate, $endDate])
                                       ->selectRaw('JSON_EXTRACT(data, "$.project_id") as project_id, COUNT(*) as views')
                                       ->groupBy('project_id')
                                       ->orderBy('views', 'desc')
                                       ->limit(5)
                                       ->get();

        // Get project details
        foreach ($topProjects as $project) {
            $projectDetails = Portfolio::find($project->project_id);
            $project->project_title = $projectDetails->title ?? 'Unknown Project';
            $project->project_type = $projectDetails->project_type ?? 'Unknown';
        }

        return [
            'top_projects' => $topProjects
        ];
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity()
    {
        return PortfolioAnalytic::orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get()
                               ->map(function ($activity) {
                                   return [
                                       'event_type' => $activity->event_type,
                                       'page' => $activity->page,
                                       'ip_address' => $this->maskIpAddress($activity->ip_address),
                                       'country' => $activity->country,
                                       'created_at' => $activity->created_at,
                                       'data' => $activity->data
                                   ];
                               });
    }

    /**
     * Get visitor statistics
     */
    private function getVisitorStats($startDate, $endDate)
    {
        return [
            'total_sessions' => PortfolioAnalytic::where('event_type', 'page_view')
                                                ->whereBetween('created_at', [$startDate, $endDate])
                                                ->distinct('ip_address')
                                                ->count(),
            'avg_session_duration' => $this->calculateAvgSessionDuration($startDate, $endDate),
            'bounce_rate' => $this->calculateBounceRate($startDate, $endDate),
            'new_vs_returning' => $this->getNewVsReturning($startDate, $endDate)
        ];
    }

    /**
     * Get geographic data
     */
    private function getGeographicData($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                               ->whereNotNull('country')
                               ->selectRaw('country, COUNT(DISTINCT ip_address) as visitors')
                               ->groupBy('country')
                               ->orderBy('visitors', 'desc')
                               ->limit(10)
                               ->get();
    }

    /**
     * Get device data
     */
    private function getDeviceData($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                               ->whereNotNull('device_type')
                               ->selectRaw('device_type, COUNT(*) as sessions')
                               ->groupBy('device_type')
                               ->get();
    }

    /**
     * Get traffic sources
     */
    private function getTrafficSources($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                               ->whereNotNull('referrer')
                               ->selectRaw('referrer, COUNT(*) as visits')
                               ->groupBy('referrer')
                               ->orderBy('visits', 'desc')
                               ->limit(10)
                               ->get();
    }

    /**
     * Get project statistics
     */
    private function getProjectStats($startDate, $endDate)
    {
        return [
            'total_project_views' => PortfolioAnalytic::where('event_type', 'project_view')
                                                     ->whereBetween('created_at', [$startDate, $endDate])
                                                     ->count(),
            'unique_project_viewers' => PortfolioAnalytic::where('event_type', 'project_view')
                                                         ->whereBetween('created_at', [$startDate, $endDate])
                                                         ->distinct('ip_address')
                                                         ->count(),
            'avg_time_on_project' => $this->calculateAvgTimeOnProject($startDate, $endDate)
        ];
    }

    /**
     * Get top projects
     */
    private function getTopProjects($startDate, $endDate)
    {
        return PortfolioAnalytic::where('event_type', 'project_view')
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->selectRaw('JSON_EXTRACT(data, "$.project_id") as project_id, JSON_EXTRACT(data, "$.project_title") as project_title, COUNT(*) as views')
                               ->groupBy('project_id', 'project_title')
                               ->orderBy('views', 'desc')
                               ->limit(10)
                               ->get();
    }

    /**
     * Get project trends
     */
    private function getProjectTrends($startDate, $endDate)
    {
        return PortfolioAnalytic::where('event_type', 'project_view
