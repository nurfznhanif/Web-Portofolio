<?php
// app/Http/Controllers/Admin/SkillController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::ordered()->get();
        $skillsByCategory = $skills->groupBy('category');

        return Inertia::render('Admin/Skills/Index', [
            'skills' => $skills,
            'skillsByCategory' => $skillsByCategory
        ]);
    }

    public function create()
    {
        $categories = Skill::distinct()->pluck('category')->toArray();

        return Inertia::render('Admin/Skills/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,mahir',
            'description' => 'nullable|string',
            'proficiency' => 'required|integer|min:0|max:100',
            'order' => 'integer|min:0',
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        $categories = Skill::distinct()->pluck('category')->toArray();

        return Inertia::render('Admin/Skills/Edit', [
            'skill' => $skill,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'level' => 'required|in:pemula,menengah,mahir',
            'description' => 'nullable|string',
            'proficiency' => 'required|integer|min:0|max:100',
            'order' => 'integer|min:0',
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill deleted successfully.');
    }
}
