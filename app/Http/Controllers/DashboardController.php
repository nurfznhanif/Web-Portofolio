<?php

namespace App\Http\Controllers;

use App\Models\{Project, Experience, Skill};
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Admin/Dashboard', [
            'projects' => Project::orderBy('created_at', 'desc')->get(),
            'experiences' => Experience::orderBy('start_date', 'desc')->get(),
            'skills' => Skill::all(),
        ]);
    }
}
