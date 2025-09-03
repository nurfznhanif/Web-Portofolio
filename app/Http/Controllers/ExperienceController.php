<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        return response()->json(Experience::orderBy('start_date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'company' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string',
            'is_current' => 'boolean'
        ]);

        return response()->json(Experience::create($request->all()), 201);
    }

    public function update(Request $request, Experience $experience)
    {
        $request->validate([
            'title' => 'required|string',
            'company' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'required|string',
            'is_current' => 'boolean'
        ]);

        $experience->update($request->all());
        return response()->json($experience);
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();
        return response()->json(null, 204);
    }
}
