<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('start_date', 'desc')->paginate(10);

        return Inertia::render('Dashboard/Experience/Index', [
            'experiences' => $experiences
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Experience/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        if ($validated['is_current'] ?? false) {
            $validated['end_date'] = null;
        }

        Experience::create($validated);

        return redirect()->route('admin.experience.index')
            ->with('success', 'Experience created successfully.');
    }

    public function edit(Experience $experience)
    {
        return Inertia::render('Dashboard/Experience/Edit', [
            'experience' => $experience
        ]);
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
        ]);

        if ($validated['is_current'] ?? false) {
            $validated['end_date'] = null;
        }

        $experience->update($validated);

        return redirect()->route('admin.experience.index')
            ->with('success', 'Experience updated successfully.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.experience.index')
            ->with('success', 'Experience deleted successfully.');
    }
}
