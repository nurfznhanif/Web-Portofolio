<?php

namespace App\Http\Controllers;

use App\Models\{Project, Experience, Skill};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $skills = Skill::all();

        // Calculate statistics
        $stats = [
            'projects' => $projects->count(),
            'experiences' => $experiences->count(),
            'skills' => $skills->count(),
            'featured_projects' => $projects->where('featured', true)->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'projects' => $projects->take(5), // Show only latest 5 for performance
            'experiences' => $experiences->take(5),
            'skills' => $skills->take(10),
            'stats' => $stats,
        ]);
    }
}
