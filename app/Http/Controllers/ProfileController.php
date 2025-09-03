<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Profile::first() ?: new Profile();

        return Inertia::render('Admin/Profile/Edit', [
            'profile' => $profile
        ]);
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
            Profile::create($request->all());
        } else {
            $profile->update($request->all());
        }

        return redirect()->route('admin.profile.edit')
            ->with('message', 'Profile updated successfully!');
    }
}
