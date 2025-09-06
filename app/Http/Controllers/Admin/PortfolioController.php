<?php
// app/Http/Controllers/Admin/PortfolioController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::ordered()->get();

        return Inertia::render('Admin/Portfolio/Index', [
            'portfolios' => $portfolios
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Portfolio/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'required|string|max:100',
            'project_date' => 'required|date',
            'technologies' => 'required|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        Portfolio::create($validated);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'Portfolio created successfully.');
    }

    public function show(Portfolio $portfolio)
    {
        return Inertia::render('Admin/Portfolio/Show', [
            'portfolio' => $portfolio
        ]);
    }

    public function edit(Portfolio $portfolio)
    {
        return Inertia::render('Admin/Portfolio/Edit', [
            'portfolio' => $portfolio
        ]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_type' => 'required|string|max:100',
            'project_date' => 'required|date',
            'technologies' => 'required|string',
            'challenges' => 'nullable|string',
            'solutions' => 'nullable|string',
            'github_url' => 'nullable|url',
            'live_url' => 'nullable|url',
            'is_featured' => 'boolean',
            'order' => 'integer|min:0',
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
