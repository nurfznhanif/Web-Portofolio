<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('category')->orderBy('name')->paginate(15);

        return Inertia::render('Dashboard/Skill/Index', [
            'skills' => $skills
        ]);
    }

    public function create()
    {
        return Inertia::render('Dashboard/Skill/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,mahir',
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skill.index')
            ->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        return Inertia::render('Dashboard/Skill/Edit', [
            'skill' => $skill
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,mahir',
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skill.index')
            ->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skill.index')
            ->with('success', 'Skill deleted successfully.');
    }
}
