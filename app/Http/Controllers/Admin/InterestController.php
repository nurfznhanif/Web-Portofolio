<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class InterestController extends Controller
{
    /**
     * Display a listing of interests
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $featured = $request->get('featured', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 15);

        $query = Interest::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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

        $interests = $query->active()
            ->ordered()
            ->paginate($perPage);

        // Get categories for filter
        $categories = Interest::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Interests/Index', [
            'interests' => $interests,
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
     * Show the form for creating a new interest
     */
    public function create()
    {
        $categories = Interest::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Interests/Create', [
            'categories' => $categories,
            'categoryOptions' => $this->getCategoryOptions(),
            'iconOptions' => $this->getIconOptions(),
            'colorOptions' => $this->getColorOptions()
        ]);
    }

    /**
     * Store a newly created interest
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Set default icon if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = $this->getDefaultIcon($validated['category']);
        }

        // Set default color if not provided
        if (empty($validated['color'])) {
            $validated['color'] = $this->getDefaultColor($validated['category']);
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = Interest::where('category', $validated['category'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        Interest::create($validated);

        // Clear cache
        $this->clearInterestCache();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest created successfully.');
    }

    /**
     * Show the form for editing the specified interest
     */
    public function edit(Interest $interest)
    {
        $categories = Interest::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Interests/Edit', [
            'interest' => $interest,
            'categories' => $categories,
            'categoryOptions' => $this->getCategoryOptions(),
            'iconOptions' => $this->getIconOptions(),
            'colorOptions' => $this->getColorOptions()
        ]);
    }

    /**
     * Update the specified interest
     */
    public function update(Request $request, Interest $interest)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Set default icon if empty
        if (empty($validated['icon'])) {
            $validated['icon'] = $this->getDefaultIcon($validated['category']);
        }

        // Set default color if empty
        if (empty($validated['color'])) {
            $validated['color'] = $this->getDefaultColor($validated['category']);
        }

        $interest->update($validated);

        // Clear cache
        $this->clearInterestCache();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest updated successfully.');
    }

    /**
     * Remove the specified interest
     */
    public function destroy(Interest $interest)
    {
        $interest->delete();

        // Clear cache
        $this->clearInterestCache();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest deleted successfully.');
    }

    /**
     * Toggle featured status of interest
     */
    public function toggleFeatured(Interest $interest)
    {
        $interest->update([
            'is_featured' => !$interest->is_featured
        ]);

        // Clear cache
        $this->clearInterestCache();

        $status = $interest->is_featured ? 'featured' : 'unfeatured';

        return response()->json([
            'success' => true,
            'message' => "Interest {$status} successfully.",
            'is_featured' => $interest->is_featured
        ]);
    }

    /**
     * Get available category options
     */
    private function getCategoryOptions()
    {
        return [
            'technical' => 'Technical',
            'personal' => 'Personal',
            'academic' => 'Academic',
            'creative' => 'Creative',
            'business' => 'Business',
            'health' => 'Health & Fitness',
            'travel' => 'Travel',
            'sports' => 'Sports',
            'music' => 'Music',
            'reading' => 'Reading',
            'gaming' => 'Gaming',
            'cooking' => 'Cooking',
            'photography' => 'Photography',
            'volunteer' => 'Volunteer Work',
            'other' => 'Other'
        ];
    }

    /**
     * Get available icon options
     */
    private function getIconOptions()
    {
        return [
            '💻' => 'Computer',
            '🤖' => 'Robot/AI',
            '📊' => 'Charts/Data',
            '📱' => 'Mobile',
            '🌐' => 'Web/Internet',
            '🎓' => 'Education',
            '🔬' => 'Science',
            '🎨' => 'Art',
            '✍️' => 'Writing',
            '📝' => 'Notes',
            '🎵' => 'Music',
            '📷' => 'Photography',
            '🎮' => 'Gaming',
            '⚽' => 'Sports',
            '🏃' => 'Running',
            '🧘' => 'Meditation',
            '🍳' => 'Cooking',
            '✈️' => 'Travel',
            '📚' => 'Reading',
            '🌱' => 'Growth',
            '💡' => 'Ideas',
            '🔧' => 'Tools',
            '🎯' => 'Goals',
            '💼' => 'Business',
            '🌍' => 'Global',
            '❤️' => 'Love/Passion',
            '⭐' => 'Star',
            '🚀' => 'Rocket/Growth',
            '🏆' => 'Achievement',
            '🔥' => 'Fire/Passion'
        ];
    }

    /**
     * Get available color options
     */
    private function getColorOptions()
    {
        return [
            '#3B82F6' => 'Blue',
            '#9333EA' => 'Purple',
            '#EC4899' => 'Pink',
            '#059669' => 'Green',
            '#DC2626' => 'Red',
            '#D97706' => 'Orange',
            '#7C3AED' => 'Violet',
            '#0891B2' => 'Cyan',
            '#65A30D' => 'Lime',
            '#DC2626' => 'Rose',
            '#7C2D12' => 'Orange Dark',
            '#1F2937' => 'Gray',
            '#B91C1C' => 'Red Dark',
            '#1D4ED8' => 'Blue Dark',
            '#7C3AED' => 'Purple Dark'
        ];
    }

    /**
     * Get default icon for category
     */
    private function getDefaultIcon($category)
    {
        $icons = [
            'technical' => '💻',
            'personal' => '❤️',
            'academic' => '🎓',
            'creative' => '🎨',
            'business' => '💼',
            'health' => '🏃',
            'travel' => '✈️',
            'sports' => '⚽',
            'music' => '🎵',
            'reading' => '📚',
            'gaming' => '🎮',
            'cooking' => '🍳',
            'photography' => '📷',
            'volunteer' => '🌍',
            'other' => '⭐'
        ];

        return $icons[$category] ?? '⭐';
    }

    /**
     * Get default color for category
     */
    private function getDefaultColor($category)
    {
        $colors = [
            'technical' => '#3B82F6',
            'personal' => '#EC4899',
            'academic' => '#9333EA',
            'creative' => '#D97706',
            'business' => '#1F2937',
            'health' => '#059669',
            'travel' => '#0891B2',
            'sports' => '#DC2626',
            'music' => '#7C3AED',
            'reading' => '#65A30D',
            'gaming' => '#B91C1C',
            'cooking' => '#D97706',
            'photography' => '#1D4ED8',
            'volunteer' => '#059669',
            'other' => '#6366F1'
        ];

        return $colors[$category] ?? '#6366F1';
    }

    /**
     * Clear interest cache
     */
    private function clearInterestCache()
    {
        $cacheKeys = [
            'interests_data',
            'dynamic_portfolio_data_v2'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
