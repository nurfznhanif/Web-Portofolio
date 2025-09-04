<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $featured = $request->get('featured', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Achievement::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('issuer', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category !== 'all') {
            $query->byCategory($category);
        }

        // Apply featured filter
        if ($featured === 'featured') {
            $query->featured();
        } elseif ($featured === 'not_featured') {
            $query->where('is_featured', false);
        }

        $achievements = $query->active()
            ->ordered()
            ->paginate($perPage);

        // Get categories for filter
        $categories = Achievement::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Achievements/Index', [
            'achievements' => $achievements,
            'categories' => $categories,
            'filters' => [
                'category' => $category,
                'featured' => $featured,
                'search' => $search,
                'per_page' => $perPage
            ]
        ]);
    }

    /**
     * Show the form for creating a new achievement
     */
    public function create()
    {
        $categories = Achievement::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Achievements/Create', [
            'categories' => $categories,
            'categoryOptions' => $this->getCategoryOptions()
        ]);
    }

    /**
     * Store a newly created achievement
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'date_achieved' => 'required|date|before_or_equal:today',
            'category' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('achievements', $filename, 'public');
            $validated['image'] = $path;
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = Achievement::where('category', $validated['category'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $achievement = Achievement::create($validated);

        // Clear cache
        $this->clearAchievementCache();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    /**
     * Display the specified achievement
     */
    public function show(Achievement $achievement)
    {
        return Inertia::render('Admin/Achievements/Show', [
            'achievement' => $achievement
        ]);
    }

    /**
     * Show the form for editing the specified achievement
     */
    public function edit(Achievement $achievement)
    {
        $categories = Achievement::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Achievements/Edit', [
            'achievement' => $achievement,
            'categories' => $categories,
            'categoryOptions' => $this->getCategoryOptions()
        ]);
    }

    /**
     * Update the specified achievement
     */
    public function update(Request $request, Achievement $achievement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'date_achieved' => 'required|date|before_or_equal:today',
            'category' => 'required|string|max:255',
            'issuer' => 'nullable|string|max:255',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'remove_image' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle image removal
        if ($request->boolean('remove_image') && $achievement->image) {
            Storage::disk('public')->delete($achievement->image);
            $validated['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($achievement->image) {
                Storage::disk('public')->delete($achievement->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('achievements', $filename, 'public');
            $validated['image'] = $path;
        }

        // Remove the remove_image field as it's not in the model
        unset($validated['remove_image']);

        $achievement->update($validated);

        // Clear cache
        $this->clearAchievementCache();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified achievement
     */
    public function destroy(Achievement $achievement)
    {
        // Delete associated image if exists
        if ($achievement->image) {
            Storage::disk('public')->delete($achievement->image);
        }

        $achievement->delete();

        // Clear cache
        $this->clearAchievementCache();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }

    /**
     * Toggle featured status of achievement
     */
    public function toggleFeatured(Achievement $achievement)
    {
        $achievement->update([
            'is_featured' => !$achievement->is_featured
        ]);

        // Clear cache
        $this->clearAchievementCache();

        $status = $achievement->is_featured ? 'featured' : 'unfeatured';

        return response()->json([
            'success' => true,
            'message' => "Achievement {$status} successfully.",
            'is_featured' => $achievement->is_featured
        ]);
    }

    /**
     * Get available category options
     */
    private function getCategoryOptions()
    {
        return [
            'academic' => 'Academic',
            'professional' => 'Professional',
            'research' => 'Research',
            'certification' => 'Certification',
            'competition' => 'Competition',
            'leadership' => 'Leadership',
            'community' => 'Community Service',
            'technical' => 'Technical',
            'personal' => 'Personal',
            'other' => 'Other'
        ];
    }

    /**
     * Clear achievement cache
     */
    private function clearAchievementCache()
    {
        $cacheKeys = [
            'achievements_data',
            'dynamic_portfolio_data_v2'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
