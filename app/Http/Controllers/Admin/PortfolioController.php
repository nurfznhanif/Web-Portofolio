<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Dashboard/Portfolio/Index', [
            'portfolios' => $portfolios
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Portfolio/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'github_url' => 'nullable|url',
            'project_type' => 'required|string',
            'project_date' => 'required|date',
        ]);

        Portfolio::create($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio created successfully.');
    }

    public function show(Portfolio $portfolio)
    {
        return Inertia::render('Dashboard/Portfolio/Show', [
            'portfolio' => $portfolio
        ]);
    }

    public function edit(Portfolio $portfolio)
    {
        return Inertia::render('Dashboard/Portfolio/Edit', [
            'portfolio' => $portfolio
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'github_url' => 'nullable|url',
            'project_type' => 'required|string',
            'project_date' => 'required|date',
        ]);

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio deleted successfully.');
    }
}
