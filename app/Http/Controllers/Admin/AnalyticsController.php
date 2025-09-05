<?php
// app/Http/Controllers/Admin/AnalyticsController.php - FIXED VERSION

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
     * Display analytics dashboard (index route) - FIXED
     */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    /**
     * Display main analytics dashboard
     */
    public function dashboard(Request $request)
    {
        $days = $request->get('days', 30);
        $endDate = now();
        $startDate = now()->subDays($days);

        try {
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
        } catch (\Exception $e) {
            // Fallback dengan data mock jika ada error
            return Inertia::render('Admin/Analytics/Dashboard', [
                'overview' => $this->getMockOverviewStats(),
                'chartData' => $this->getMockChartData(),
                'topContent' => $this->getMockTopContent(),
                'recentActivity' => $this->getMockRecentActivity(),
                'dateRange' => [
                    'days' => $days,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ],
                'error' => 'Some analytics data may be unavailable.'
            ]);
        }
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

    // ===========================================
    // DATA COLLECTION METHODS
    // ===========================================

    /**
     * Get overview statistics
     */
    private function getOverviewStats($startDate, $endDate)
    {
        try {
            return [
                'total_visitors' => PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                    ->where('event_type', 'page_view')
                    ->distinct('ip_address')
                    ->count(),
                'total_page_views' => PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                    ->where('event_type', 'page_view')
                    ->count(),
                'total_contacts' => ContactMessage::whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
                'total_downloads' => PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                    ->where('event_type', 'cv_download')
                    ->count(),
            ];
        } catch (\Exception $e) {
            return $this->getMockOverviewStats();
        }
    }

    /**
     * Get chart data for dashboard
     */
    private function getDashboardChartData($startDate, $endDate)
    {
        try {
            return [
                'daily_views' => $this->getDailyViewsChart($startDate, $endDate),
                'hourly_pattern' => $this->getHourlyPatternChart($startDate, $endDate),
                'device_breakdown' => $this->getDeviceBreakdownChart($startDate, $endDate),
                'geographic_distribution' => $this->getGeographicChart($startDate, $endDate)
            ];
        } catch (\Exception $e) {
            return $this->getMockChartData();
        }
    }

    /**
     * Get top content
     */
    private function getTopContent($startDate, $endDate)
    {
        try {
            $topPages = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                ->where('event_type', 'page_view')
                ->selectRaw('page, COUNT(*) as views')
                ->groupBy('page')
                ->orderBy('views', 'desc')
                ->limit(10)
                ->get();

            $topProjects = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
                ->where('event_type', 'project_view')
                ->selectRaw('data->>"$.project_id" as project_id, COUNT(*) as views')
                ->groupBy('project_id')
                ->orderBy('views', 'desc')
                ->limit(10)
                ->get();

            return [
                'top_pages' => $topPages,
                'top_projects' => $topProjects
            ];
        } catch (\Exception $e) {
            return $this->getMockTopContent();
        }
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity()
    {
        try {
            return PortfolioAnalytic::orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($activity) {
                    return [
                        'event_type' => $activity->event_type,
                        'page' => $activity->page,
                        'ip_address' => $this->maskIpAddress($activity->ip_address ?? ''),
                        'country' => $activity->country ?? 'Unknown',
                        'device_type' => $activity->device_type ?? 'Unknown',
                        'created_at' => $activity->created_at,
                        'data' => $activity->data
                    ];
                });
        } catch (\Exception $e) {
            return $this->getMockRecentActivity();
        }
    }

    // ===========================================
    // CHART DATA METHODS
    // ===========================================

    private function getDailyViewsChart($startDate, $endDate)
    {
        $data = PortfolioAnalytic::whereBetween('created_at', [$startDate, $endDate])
            ->where('event_type', 'page_view')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Daily Views',
                    'data' => $data->pluck('views')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

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
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(251, 191, 36, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                    ],
                ]
            ]
        ];
    }

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

    // ===========================================
    // HELPER METHODS
    // ===========================================

    private function maskIpAddress($ip)
    {
        if (empty($ip)) return 'Unknown';

        $parts = explode('.', $ip);
        if (count($parts) === 4) {
            return $parts[0] . '.' . $parts[1] . '.***.' . $parts[3];
        }
        return 'Unknown';
    }

    // ===========================================
    // MOCK DATA METHODS (FALLBACK)
    // ===========================================

    private function getMockOverviewStats()
    {
        return [
            'total_visitors' => 150,
            'total_page_views' => 500,
            'total_contacts' => 25,
            'total_downloads' => 75,
        ];
    }

    private function getMockChartData()
    {
        return [
            'daily_views' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'datasets' => [
                    [
                        'label' => 'Daily Views',
                        'data' => [12, 19, 15, 25, 22, 18, 28],
                        'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                        'borderColor' => 'rgba(59, 130, 246, 1)',
                        'borderWidth' => 2
                    ]
                ]
            ],
            'device_breakdown' => [
                'labels' => ['Desktop', 'Mobile', 'Tablet'],
                'datasets' => [
                    [
                        'label' => 'Device Usage',
                        'data' => [60, 30, 10],
                        'backgroundColor' => [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(251, 191, 36, 0.7)',
                        ],
                    ]
                ]
            ]
        ];
    }

    private function getMockTopContent()
    {
        return [
            'top_pages' => collect([
                (object)['page' => '/', 'views' => 150],
                (object)['page' => '/about', 'views' => 89],
                (object)['page' => '/contact', 'views' => 67],
            ]),
            'top_projects' => collect([
                (object)['project_id' => '1', 'views' => 45],
                (object)['project_id' => '2', 'views' => 32],
                (object)['project_id' => '3', 'views' => 28],
            ])
        ];
    }

    private function getMockRecentActivity()
    {
        return collect([
            [
                'event_type' => 'page_view',
                'page' => '/',
                'ip_address' => '192.168.***.1',
                'country' => 'Indonesia',
                'device_type' => 'Desktop',
                'created_at' => now()->subMinutes(5),
                'data' => null
            ],
            [
                'event_type' => 'contact_form',
                'page' => '/contact',
                'ip_address' => '192.168.***.2',
                'country' => 'Indonesia',
                'device_type' => 'Mobile',
                'created_at' => now()->subMinutes(15),
                'data' => null
            ]
        ]);
    }

    // Placeholder methods untuk method yang dipanggil di atas
    private function getVisitorStats($startDate, $endDate)
    {
        return [];
    }
    private function getGeographicData($startDate, $endDate)
    {
        return [];
    }
    private function getDeviceData($startDate, $endDate)
    {
        return [];
    }
    private function getProjectStats($startDate, $endDate)
    {
        return [];
    }
    private function getTopProjects($startDate, $endDate)
    {
        return [];
    }
    private function getProjectTrends($startDate, $endDate)
    {
        return [];
    }
}
