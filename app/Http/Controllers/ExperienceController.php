<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ExperienceController extends Controller
{
    public function index()
    {
        try {
            $experiences = Experience::orderBy('start_date', 'desc')->get();
            return response()->json($experiences);
        } catch (Exception $e) {
            Log::error('Experience index error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch experiences',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'company' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'description' => 'required|string',
                'is_current' => 'boolean'
            ]);

            $experience = Experience::create($request->all());
            return response()->json($experience, 201);
        } catch (Exception $e) {
            Log::error('Experience store error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create experience',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Experience $experience)
    {
        try {
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
        } catch (Exception $e) {
            Log::error('Experience update error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update experience',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Experience $experience)
    {
        try {
            $experience->delete();
            return response()->json(null, 204);
        } catch (Exception $e) {
            Log::error('Experience delete error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to delete experience',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
