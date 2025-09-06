<?php
// app/Http/Controllers/Admin/ProfileSettingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileSettingController extends Controller
{
    public function index()
    {
        $profile = ProfileSetting::first();

        if (!$profile) {
            $profile = (object) [
                'id' => null,
                'name' => auth()->user()->name ?? '',
                'title' => '',
                'summary' => '',
                'email' => auth()->user()->email ?? '',
                'phone' => '',
                'location' => 'Pekanbaru, Riau',
                'photo' => null,
                'thesis_title' => '',
                'education' => [],
                'availability' => [],
            ];
        }

        return Inertia::render('Admin/Profile/Settings', [
            'profile' => $profile,
            'status' => session('status'),
        ]);
    }

    public function update(Request $request)
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'summary' => 'required|string',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'location' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'thesis_title' => 'nullable|string',
            ]);

            // Mulai transaction
            DB::beginTransaction();

            // Cari atau buat profile
            $profile = ProfileSetting::first();

            if (!$profile) {
                $profile = new ProfileSetting();
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Hapus foto lama
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }

                // Upload foto baru
                $photoPath = $request->file('photo')->store('profile', 'public');
                $validated['photo'] = $photoPath;
            }

            // Update data
            $profile->fill($validated);
            $profile->save();

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'profile' => $profile
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Profile update error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
