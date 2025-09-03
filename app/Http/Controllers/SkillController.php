<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        if (request()->wantsJson()) {
            return response()->json(Skill::all());
        }

        return Inertia::render('Admin/Skills/Index', [
            'skills' => $skills,
            'skillsFlat' => Skill::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Skills/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'description' => 'required|string'
        ]);

        Skill::create($request->all());

        if (request()->wantsJson()) {
            return response()->json(Skill::latest()->first(), 201);
        }

        return redirect()->route('admin.skills.index')
            ->with('message', 'Skill created successfully!');
    }

    public function show(Skill $skill)
    {
        if (request()->wantsJson()) {
            return response()->json($skill);
        }

        return Inertia::render('Admin/Skills/Show', [
            'skill' => $skill
        ]);
    }

    public function edit(Skill $skill)
    {
        return Inertia::render('Admin/Skills/Edit', [
            'skill' => $skill
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'description' => 'required|string'
        ]);

        $skill->update($request->all());

        if (request()->wantsJson()) {
            return response()->json($skill);
        }

        return redirect()->route('admin.skills.index')
            ->with('message', 'Skill updated successfully!');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        if (request()->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('admin.skills.index')
            ->with('message', 'Skill deleted successfully!');
    }
}
