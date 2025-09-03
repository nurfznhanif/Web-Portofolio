<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = Profile::first();
        return response()->json($profile);
    }

    public function update(Request $request)
    {
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
    }
}
