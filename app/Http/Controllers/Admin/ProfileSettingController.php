<?php
// app/Http/Controllers/Admin/ProfileSettingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{
    public function index()
    {
        $profile = ProfileSetting::first();

        return Inertia::render('Admin/Profile/Settings', [
            'profile' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'education' => 'nullable|array',
            'thesis_title' => 'nullable|string',
            'availability' => 'nullable|array',
        ]);

        $profile = ProfileSetting::first();

        if (!$profile) {
            $profile = new ProfileSetting();
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
            }

            $validated['photo'] = $request->file('photo')->store('profile', 'public');
        }

        $profile->fill($validated);
        $profile->save();

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }
}
