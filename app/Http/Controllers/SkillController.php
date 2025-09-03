<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        return response()->json(Skill::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'description' => 'required|string'
        ]);

        return response()->json(Skill::create($request->all()), 201);
    }

    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
            'description' => 'required|string'
        ]);

        $skill->update($request->all());
        return response()->json($skill);
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return response()->json(null, 204);
    }
}
