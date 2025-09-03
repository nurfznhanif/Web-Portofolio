<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            $profile = Profile::first();

            if (!$profile) {
                // Return empty profile structure if no profile exists
                return response()->json([
                    'id' => null,
                    'name' => '',
                    'phone' => '',
                    'email' => '',
                    'location' => '',
                    'bio' => '',
                    'education_degree' => '',
                    'education_gpa' => 0,
                    'education_thesis' => '',
                    'github_url' => '',
                    'profile_image' => null
                ]);
            }

            return response()->json($profile);
        } catch (Exception $e) {
            Log::error('Profile show error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'location' => 'required|string|max:255',
                'bio' => 'required|string',
                'education_degree' => 'required|string|max:255',
                'education_gpa' => 'required|numeric|between:0,4',
                'education_thesis' => 'required|string',
                'github_url' => 'nullable|url',
            ]);

            $profile = Profile::first();
            if (!$profile) {
                $profile = Profile::create($request->all());
            } else {
                $profile->update($request->all());
            }

            return response()->json($profile);
        } catch (Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
