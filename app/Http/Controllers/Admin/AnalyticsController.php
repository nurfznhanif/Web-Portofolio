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
    // ===========================================
    // MAIN CONTROLLER METHODS
    // ===========================================

    /**
     * Display analytics dashboard (index route)
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

        return Inertia::render('Admin/Analytics/Dashboard', [
            'overview' => $this->getOverviewStats($startDate, $endDate),
            'chartData' => $this->getDashboardChartData($startDate, $endDate),
            'topContent' => $this->getTopContent($startDate, $endDate),
            'recentActivity' => $this->getRecentActivity(),
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

        return Inertia::render('Admin/Analytics/Visitors', [
            'visitorStats' => $this->getVisitorStats($startDate, $endDate),
            'geographicData' => $this->getGeographicData($startDate, $endDate),
            'deviceData' => $this->getDeviceData($startDate, $endDate),
            'trafficSources' => $this->getTrafficSources($startDate, $endDate),
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

        return Inertia::render('Admin/Analytics/Projects', [
            'projectStats' => $this->getProjectStats($startDate, $endDate),
            'topProjects' => $this->getTopProjects($startDate, $endDate),
            'projectTrends' => $this->getProjectTrends($startDate, $endDate),
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

        return Inertia::render('Admin/Analytics/ContactForms', [
            'contactStats' => $this->getContactStats($startDate, $endDate),
            'responseStats' => $this->getResponseStats($startDate, $endDate),
            'contactTrends' => $this->getContactTrends($startDate, $endDate),
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

        return Inertia::render('Admin/Analytics/Downloads', [
            'downloadStats' => $this->getDownloadStats($startDate, $endDate),
            'downloadTrends' => $this->getDownloadTrends($startDate, $endDate),
            'downloadPatterns' => $this->getDownloadPatterns($startDate, $endDate),
            'dateRange' => [
                'days' => $days,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d')
            ]
        ]);
    }

    // ===========================================
    // API ENDPOINTS
    // ===========================================

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

        switch ($type) {
            case 'daily_views':
                return response()->json($this->getDailyViewsChart($startDate, $endDate));
            case 'hourly_pattern':
                return response()->json($this->getHourlyPatternChart($startDate, $endDate));
            case 'device_breakdown':
                return response()->json($this->getDeviceBreakdownChart($startDate, $endDate));
            case 'geographic_distribution':
                return response()->json($this->getGeographicChart($startDate, $endDate));
            case 'project_views':
                return response()->json($this->getProjectViewsChart($startDate, $endDate));
            case 'contact_submissions':
                return response()->json($this->getContactSubmissionsChart($startDate, $endDate));
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
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

    // ===========================================
    // DATA COLLECTION METHODS
    // ===========================================

    /**
     * Get overview statistics
     */
    private function getOverviewStats($startDate, $endDate)
    {
        // Current period stats
        $totalVisitors = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->distinct('ip_address')
            ->count();

        $totalPageViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->count();

        $totalContacts = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalDownloads = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_success')
            ->count();

        // Previous period for growth calculation
        $previousPeriod = $endDate->diffInDays($startDate);
        $previousStartDate = $startDate->copy()->subDays($previousPeriod);
        $previousEndDate = $startDate->copy()->subDay();

        $previousVisitors = PortfolioAnalytic::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('event_type', 'page_view')
            ->distinct('ip_address')
            ->count();

        $previousPageViews = PortfolioAnalytic::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('event_type', 'page_view')
            ->count();

        $previousContacts = ContactMessage::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->count();

        $previousDownloads = PortfolioAnalytic::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('event_type', 'cv_download_success')
            ->count();

        return [
            'total_visitors' => $totalVisitors,
            'total_page_views' => $totalPageViews,
            'total_contacts' => $totalContacts,
            'total_downloads' => $totalDownloads,
            'visitor_growth' => $this->calculateGrowthRate($totalVisitors, $previousVisitors),
            'page_view_growth' => $this->calculateGrowthRate($totalPageViews, $previousPageViews),
            'contact_growth' => $this->calculateGrowthRate($totalContacts, $previousContacts),
            'download_growth' => $this->calculateGrowthRate($totalDownloads, $previousDownloads),
            'avg_session_duration' => $this->calculateAvgSessionDuration($startDate, $endDate),
            'bounce_rate' => $this->calculateBounceRate($startDate, $endDate)
        ];
    }

    /**
     * Get visitor statistics
     */
    private function getVisitorStats($startDate, $endDate)
    {
        $totalSessions = PortfolioAnalytic::where('event_type', 'page_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('ip_address')
            ->count();

        $totalPageViews = PortfolioAnalytic::where('event_type', 'page_view')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'total_sessions' => $totalSessions,
            'total_page_views' => $totalPageViews,
            'pages_per_session' => $totalSessions > 0 ? round($totalPageViews / $totalSessions, 2) : 0,
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
        $totalProjectViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->count();

        $uniqueProjectViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->distinct(['ip_address', 'data->project_id'])
            ->count();

        return [
            'total_project_views' => $totalProjectViews,
            'unique_project_views' => $uniqueProjectViews,
            'avg_views_per_project' => $this->getAvgViewsPerProject($startDate, $endDate),
            'project_engagement_rate' => $this->calculateProjectEngagementRate($startDate, $endDate)
        ];
    }

    /**
     * Get top projects
     */
    private function getTopProjects($startDate, $endDate)
    {
        $topProjects = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->whereNotNull('data->project_id')
            ->selectRaw('JSON_UNQUOTE(JSON_EXTRACT(data, "$.project_id")) as project_id, COUNT(*) as views, COUNT(DISTINCT ip_address) as unique_views')
            ->groupBy('project_id')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        // Enrich with project details
        foreach ($topProjects as $project) {
            $projectDetails = Portfolio::find($project->project_id);
            $project->title = $projectDetails->title ?? 'Unknown Project';
            $project->project_type = $projectDetails->project_type ?? 'Unknown';
            $project->technologies = $projectDetails->technologies ?? '';
        }

        return $topProjects;
    }

    /**
     * Get project trends
     */
    private function getProjectTrends($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get contact statistics
     */
    private function getContactStats($startDate, $endDate)
    {
        $totalSubmissions = ContactMessage::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalReplied = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'replied')
            ->count();
        $totalUnread = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'new')
            ->count();

        return [
            'total_submissions' => $totalSubmissions,
            'total_replied' => $totalReplied,
            'total_unread' => $totalUnread,
            'reply_rate' => $totalSubmissions > 0 ? round(($totalReplied / $totalSubmissions) * 100, 2) : 0,
            'conversion_rate' => $this->calculateContactConversionRate($startDate, $endDate),
            'avg_response_time' => $this->calculateAvgResponseTime($startDate, $endDate)
        ];
    }

    /**
     * Get response statistics
     */
    private function getResponseStats($startDate, $endDate)
    {
        $messages = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('replied_at')
            ->get();

        $responseTimes = [];
        foreach ($messages as $message) {
            $responseTime = $message->created_at->diffInHours($message->replied_at);
            $responseTimes[] = $responseTime;
        }

        return [
            'avg_response_time_hours' => count($responseTimes) > 0 ? round(array_sum($responseTimes) / count($responseTimes), 2) : 0,
            'median_response_time_hours' => $this->calculateMedian($responseTimes),
            'fastest_response_hours' => count($responseTimes) > 0 ? min($responseTimes) : 0,
            'slowest_response_hours' => count($responseTimes) > 0 ? max($responseTimes) : 0
        ];
    }

    /**
     * Get contact trends
     */
    private function getContactTrends($startDate, $endDate)
    {
        return ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as submissions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get download statistics
     */
    private function getDownloadStats($startDate, $endDate)
    {
        $totalAttempts = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_attempt')
            ->count();

        $totalSuccessful = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_success')
            ->count();

        $totalErrors = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_error')
            ->count();

        $uniqueDownloads = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_success')
            ->distinct('ip_address')
            ->count();

        return [
            'total_attempts' => $totalAttempts,
            'total_successful' => $totalSuccessful,
            'total_errors' => $totalErrors,
            'unique_downloads' => $uniqueDownloads,
            'success_rate' => $totalAttempts > 0 ? round(($totalSuccessful / $totalAttempts) * 100, 2) : 0,
            'error_rate' => $totalAttempts > 0 ? round(($totalErrors / $totalAttempts) * 100, 2) : 0
        ];
    }

    /**
     * Get download trends
     */
    private function getDownloadTrends($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('event_type', ['cv_download_attempt', 'cv_download_success', 'cv_download_error'])
            ->selectRaw('DATE(created_at) as date, event_type, COUNT(*) as count')
            ->groupBy(['date', 'event_type'])
            ->orderBy('date')
            ->get();
    }

    /**
     * Get download patterns (time of day analysis)
     */
    private function getDownloadPatterns($startDate, $endDate)
    {
        return PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'cv_download_success')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as downloads')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    /**
     * Get top performing content
     */
    private function getTopContent($startDate, $endDate)
    {
        // Top pages
        $topPages = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->whereNotNull('page')
            ->selectRaw('page, COUNT(*) as views')
            ->groupBy('page')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        // Top projects
        $topProjects = $this->getTopProjects($startDate, $endDate);

        return [
            'top_pages' => $topPages,
            'top_projects' => $topProjects
        ];
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity()
    {
        return PortfolioAnalytic::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($activity) {
                return [
                    'event_type' => $activity->event_type,
                    'page' => $activity->page,
                    'ip_address' => $this->maskIpAddress($activity->ip_address),
                    'country' => $activity->country,
                    'device_type' => $activity->device_type,
                    'created_at' => $activity->created_at,
                    'data' => $activity->data
                ];
            });
    }

    /**
     * Get chart data for dashboard (private method)
     */
    private function getDashboardChartData($startDate, $endDate)
    {
        return [
            'daily_views' => $this->getDailyViewsChart($startDate, $endDate),
            'hourly_pattern' => $this->getHourlyPatternChart($startDate, $endDate),
            'device_breakdown' => $this->getDeviceBreakdownChart($startDate, $endDate),
            'geographic_distribution' => $this->getGeographicChart($startDate, $endDate)
        ];
    }

    // ===========================================
    // CHART DATA METHODS
    // ===========================================

    /**
     * Get daily views chart data
     */
    private function getDailyViewsChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT ip_address) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $data->pluck('views')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Unique Visitors',
                    'data' => $data->pluck('unique_visitors')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get hourly pattern chart data
     */
    private function getHourlyPatternChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as views')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return [
            'labels' => $data->pluck('hour')->map(function ($hour) {
                return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
            })->toArray(),
            'datasets' => [
                [
                    'label' => 'Hourly Views',
                    'data' => $data->pluck('views')->toArray(),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.5)',
                    'borderColor' => 'rgba(251, 191, 36, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get device breakdown chart data
     */
    private function getDeviceBreakdownChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('device_type')
            ->selectRaw('device_type, COUNT(*) as sessions')
            ->groupBy('device_type')
            ->get();

        return [
            'labels' => $data->pluck('device_type')->toArray(),
            'datasets' => [
                [
                    'label' => 'Device Usage',
                    'data' => $data->pluck('sessions')->toArray(),
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.7)',  // Desktop - Blue
                        'rgba(16, 185, 129, 0.7)',  // Mobile - Green
                        'rgba(251, 191, 36, 0.7)',  // Tablet - Yellow
                        'rgba(239, 68, 68, 0.7)',   // Other - Red
                    ],
                    'borderColor' => [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(251, 191, 36, 1)',
                        'rgba(239, 68, 68, 1)',
                    ],
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get geographic chart data
     */
    private function getGeographicChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(DISTINCT ip_address) as visitors')
            ->groupBy('country')
            ->orderBy('visitors', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $data->pluck('country')->toArray(),
            'datasets' => [
                [
                    'label' => 'Visitors by Country',
                    'data' => $data->pluck('visitors')->toArray(),
                    'backgroundColor' => 'rgba(139, 92, 246, 0.5)',
                    'borderColor' => 'rgba(139, 92, 246, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get project views chart data
     */
    private function getProjectViewsChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Project Views',
                    'data' => $data->pluck('views')->toArray(),
                    'backgroundColor' => 'rgba(236, 72, 153, 0.5)',
                    'borderColor' => 'rgba(236, 72, 153, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    /**
     * Get contact submissions chart data
     */
    private function getContactSubmissionsChart($startDate, $endDate)
    {
        $data = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as submissions')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Contact Submissions',
                    'data' => $data->pluck('submissions')->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    // ===========================================
    // UTILITY CALCULATION METHODS
    // ===========================================

    /**
     * Calculate growth rate percentage
     */
    private function calculateGrowthRate($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }

    /**
     * Calculate average session duration
     */
    private function calculateAvgSessionDuration($startDate, $endDate)
    {
        // Group by IP address and date to identify sessions
        $sessions = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->select('ip_address', 'created_at')
            ->orderBy('ip_address')
            ->orderBy('created_at')
            ->get()
            ->groupBy('ip_address');

        $durations = [];

        foreach ($sessions as $ipSessions) {
            if ($ipSessions->count() > 1) {
                $firstView = $ipSessions->first()->created_at;
                $lastView = $ipSessions->last()->created_at;
                $duration = $firstView->diffInMinutes($lastView);
                $durations[] = $duration;
            }
        }

        return count($durations) > 0 ? round(array_sum($durations) / count($durations), 2) : 0;
    }

    /**
     * Calculate bounce rate
     */
    private function calculateBounceRate($startDate, $endDate)
    {
        $totalSessions = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->distinct('ip_address')
            ->count();

        $singlePageSessions = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->select('ip_address')
            ->groupBy('ip_address')
            ->havingRaw('COUNT(*) = 1')
            ->get()
            ->count();

        return $totalSessions > 0 ? round(($singlePageSessions / $totalSessions) * 100, 2) : 0;
    }

    /**
     * Get new vs returning visitors
     */
    private function getNewVsReturning($startDate, $endDate)
    {
        // Get all unique visitors in the period
        $currentVisitors = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->pluck('ip_address')
            ->unique();

        // Get visitors who visited before this period
        $previousVisitors = PortfolioAnalytic::where('created_at', '<', $startDate)
            ->where('event_type', 'page_view')
            ->pluck('ip_address')
            ->unique();

        $returningVisitors = $currentVisitors->intersect($previousVisitors)->count();
        $newVisitors = $currentVisitors->count() - $returningVisitors;

        return [
            'new_visitors' => $newVisitors,
            'returning_visitors' => $returningVisitors,
            'new_percentage' => $currentVisitors->count() > 0 ? round(($newVisitors / $currentVisitors->count()) * 100, 2) : 0,
            'returning_percentage' => $currentVisitors->count() > 0 ? round(($returningVisitors / $currentVisitors->count()) * 100, 2) : 0
        ];
    }

    /**
     * Calculate project engagement rate
     */
    private function calculateProjectEngagementRate($startDate, $endDate)
    {
        $totalPageViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->count();

        $projectViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->count();

        return $totalPageViews > 0 ? round(($projectViews / $totalPageViews) * 100, 2) : 0;
    }

    /**
     * Get average views per project
     */
    private function getAvgViewsPerProject($startDate, $endDate)
    {
        $totalProjects = Portfolio::count();
        $totalProjectViews = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'project_view')
            ->count();

        return $totalProjects > 0 ? round($totalProjectViews / $totalProjects, 2) : 0;
    }

    /**
     * Calculate contact conversion rate
     */
    private function calculateContactConversionRate($startDate, $endDate)
    {
        $totalVisitors = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->distinct('ip_address')
            ->count();

        $totalContacts = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return $totalVisitors > 0 ? round(($totalContacts / $totalVisitors) * 100, 2) : 0;
    }

    /**
     * Calculate average response time
     */
    private function calculateAvgResponseTime($startDate, $endDate)
    {
        $responseTimes = ContactMessage::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('replied_at')
            ->get()
            ->map(function ($message) {
                return $message->created_at->diffInHours($message->replied_at);
            });

        return $responseTimes->count() > 0 ? round($responseTimes->avg(), 2) : 0;
    }

    /**
     * Calculate median value
     */
    private function calculateMedian($array)
    {
        if (empty($array)) return 0;

        sort($array);
        $count = count($array);
        $middle = floor($count / 2);

        if ($count % 2 == 0) {
            return ($array[$middle - 1] + $array[$middle]) / 2;
        } else {
            return $array[$middle];
        }
    }

    /**
     * Mask IP address for privacy
     */
    private function maskIpAddress($ipAddress)
    {
        if (!$ipAddress) return 'Unknown';

        $parts = explode('.', $ipAddress);
        if (count($parts) === 4) {
            return $parts[0] . '.' . $parts[1] . '.***.' . $parts[3];
        }

        return substr($ipAddress, 0, -4) . '****';
    }

    // ===========================================
    // EXPORT METHODS
    // ===========================================

    /**
     * Export data as CSV
     */
    private function exportAsCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Write CSV headers
            fputcsv($file, [
                'Metric',
                'Value',
                'Description',
                'Date Range'
            ]);

            // Overview statistics
            $overview = $data['overview'];
            $dateRange = $data['date_range']['start_date'] . ' to ' . $data['date_range']['end_date'];

            fputcsv($file, ['Total Visitors', $overview['total_visitors'], 'Unique visitors in date range', $dateRange]);
            fputcsv($file, ['Total Page Views', $overview['total_page_views'], 'Total page views in date range', $dateRange]);
            fputcsv($file, ['Total Contacts', $overview['total_contacts'], 'Contact form submissions in date range', $dateRange]);
            fputcsv($file, ['Total Downloads', $overview['total_downloads'], 'CV downloads in date range', $dateRange]);
            fputcsv($file, ['Visitor Growth Rate (%)', $overview['visitor_growth'], 'Growth compared to previous period', $dateRange]);
            fputcsv($file, ['Page View Growth Rate (%)', $overview['page_view_growth'], 'Growth compared to previous period', $dateRange]);
            fputcsv($file, ['Contact Growth Rate (%)', $overview['contact_growth'], 'Growth compared to previous period', $dateRange]);
            fputcsv($file, ['Download Growth Rate (%)', $overview['download_growth'], 'Growth compared to previous period', $dateRange]);
            fputcsv($file, ['Average Session Duration (min)', $overview['avg_session_duration'], 'Average time spent on site', $dateRange]);
            fputcsv($file, ['Bounce Rate (%)', $overview['bounce_rate'], 'Percentage of single-page sessions', $dateRange]);

            // Visitor statistics
            $visitors = $data['visitors'];
            fputcsv($file, ['Total Sessions', $visitors['total_sessions'], 'Total visitor sessions', $dateRange]);
            fputcsv($file, ['Pages Per Session', $visitors['pages_per_session'], 'Average pages viewed per session', $dateRange]);
            fputcsv($file, ['New Visitors', $visitors['new_vs_returning']['new_visitors'], 'First-time visitors', $dateRange]);
            fputcsv($file, ['Returning Visitors', $visitors['new_vs_returning']['returning_visitors'], 'Repeat visitors', $dateRange]);

            // Project statistics
            $projects = $data['projects'];
            fputcsv($file, ['Total Project Views', $projects['total_project_views'], 'Total project page views', $dateRange]);
            fputcsv($file, ['Unique Project Views', $projects['unique_project_views'], 'Unique visitors to projects', $dateRange]);
            fputcsv($file, ['Average Views Per Project', $projects['avg_views_per_project'], 'Average views across all projects', $dateRange]);
            fputcsv($file, ['Project Engagement Rate (%)', $projects['project_engagement_rate'], 'Percentage of visitors viewing projects', $dateRange]);

            // Contact statistics
            $contacts = $data['contacts'];
            fputcsv($file, ['Total Contact Submissions', $contacts['total_submissions'], 'Contact form submissions', $dateRange]);
            fputcsv($file, ['Total Replied', $contacts['total_replied'], 'Messages that have been replied to', $dateRange]);
            fputcsv($file, ['Total Unread', $contacts['total_unread'], 'Unread messages', $dateRange]);
            fputcsv($file, ['Reply Rate (%)', $contacts['reply_rate'], 'Percentage of messages replied to', $dateRange]);
            fputcsv($file, ['Contact Conversion Rate (%)', $contacts['conversion_rate'], 'Visitors who submitted contact form', $dateRange]);
            fputcsv($file, ['Average Response Time (hrs)', $contacts['avg_response_time'], 'Average time to respond to messages', $dateRange]);

            // Download statistics
            $downloads = $data['downloads'];
            fputcsv($file, ['Total Download Attempts', $downloads['total_attempts'], 'CV download attempts', $dateRange]);
            fputcsv($file, ['Successful Downloads', $downloads['total_successful'], 'Completed CV downloads', $dateRange]);
            fputcsv($file, ['Download Errors', $downloads['total_errors'], 'Failed download attempts', $dateRange]);
            fputcsv($file, ['Unique Downloads', $downloads['unique_downloads'], 'Unique visitors who downloaded CV', $dateRange]);
            fputcsv($file, ['Download Success Rate (%)', $downloads['success_rate'], 'Percentage of successful downloads', $dateRange]);
            fputcsv($file, ['Download Error Rate (%)', $downloads['error_rate'], 'Percentage of failed downloads', $dateRange]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
