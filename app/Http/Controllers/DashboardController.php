<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Portfolio;
use App\Models\Experience;
use App\Models\Skill;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'portfolios' => Portfolio::count(),
            'experiences' => Experience::count(),
            'skills' => Skill::count(),
            'recent_portfolios' => Portfolio::latest()->take(5)->get(),
        ];

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats
        ]);
    }
}
