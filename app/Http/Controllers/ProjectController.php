<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json($projects);
        }

        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Projects/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|array',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'boolean'
        ]);

        Project::create($request->all());

        return redirect()->route('admin.projects.index')
            ->with('message', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        if (request()->wantsJson()) {
            return response()->json($project);
        }

        return Inertia::render('Admin/Projects/Show', [
            'project' => $project
        ]);
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'technologies' => 'required|array',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'boolean'
        ]);

        $project->update($request->all());

        return redirect()->route('admin.projects.index')
            ->with('message', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('message', 'Project deleted successfully!');
    }
}
