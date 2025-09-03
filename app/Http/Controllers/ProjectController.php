<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(Project::orderBy('featured', 'desc')->get());
    }

    public function show(Project $project)
    {
        return response()->json($project);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'technologies' => 'required|array',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'boolean'
        ]);

        return response()->json(Project::create($request->all()), 201);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'technologies' => 'required|array',
            'github_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'featured' => 'boolean'
        ]);

        $project->update($request->all());
        return response()->json($project);
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(null, 204);
    }
}
