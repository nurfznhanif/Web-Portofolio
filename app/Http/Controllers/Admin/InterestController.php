<?php
// app/Http/Controllers/Admin/InterestController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InterestController extends Controller
{
    /**
     * Display a listing of interests.
     */
    public function index()
    {
        $interests = Interest::orderBy('name', 'asc')->get();

        return Inertia::render('Admin/Interest/Index', [
            'interests' => $interests
        ]);
    }

    /**
     * Show the form for creating a new interest.
     */
    public function create()
    {
        return Inertia::render('Admin/Interest/Create');
    }

    /**
     * Store a newly created interest in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_featured' => 'boolean',
        ]);

        // Set default value for is_featured if not provided
        if (!isset($validated['is_featured'])) {
            $validated['is_featured'] = false;
        }

        Interest::create($validated);

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest created successfully.');
    }

    /**
     * Display the specified interest.
     */
    public function show(Interest $interest)
    {
        return Inertia::render('Admin/Interest/Show', [
            'interest' => $interest
        ]);
    }

    /**
     * Show the form for editing the specified interest.
     */
    public function edit(Interest $interest)
    {
        return Inertia::render('Admin/Interest/Edit', [
            'interest' => $interest
        ]);
    }

    /**
     * Update the specified interest in storage.
     */
    public function update(Request $request, Interest $interest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_featured' => 'boolean',
        ]);

        // Set default value for is_featured if not provided
        if (!isset($validated['is_featured'])) {
            $validated['is_featured'] = false;
        }

        $interest->update($validated);

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest updated successfully.');
    }

    /**
     * Remove the specified interest from storage.
     */
    public function destroy(Interest $interest)
    {
        $interest->delete();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest deleted successfully.');
    }

    /**
     * Bulk delete interests.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:interests,id'
        ]);

        Interest::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.interests.index')
            ->with('success', count($request->ids) . ' interest(s) deleted successfully.');
    }

    /**
     * Toggle featured status of an interest.
     */
    public function toggleFeatured(Interest $interest)
    {
        $interest->update([
            'is_featured' => !$interest->is_featured
        ]);

        $status = $interest->is_featured ? 'featured' : 'unfeatured';

        return redirect()->back()
            ->with('success', "Interest {$status} successfully.");
    }

    /**
     * Search interests.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $interests = Interest::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return response()->json($interests);
    }

    /**
     * Get interest statistics.
     */
    public function getStats()
    {
        $total = Interest::count();
        $featured = Interest::where('is_featured', true)->count();
        $regular = Interest::where('is_featured', false)->count();
        $withDescription = Interest::whereNotNull('description')
            ->where('description', '!=', '')
            ->count();

        $recentlyAdded = Interest::where('created_at', '>=', now()->subDays(30))->count();

        return response()->json([
            'total' => $total,
            'featured' => $featured,
            'regular' => $regular,
            'with_description' => $withDescription,
            'recently_added' => $recentlyAdded,
        ]);
    }

    /**
     * Get featured interests.
     */
    public function getFeatured()
    {
        $featuredInterests = Interest::where('is_featured', true)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($featuredInterests);
    }

    /**
     * Export interests to CSV.
     */
    public function export()
    {
        $interests = Interest::orderBy('name', 'asc')->get();

        $filename = 'interests_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($interests) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Description',
                'Icon',
                'Color',
                'Is Featured',
                'Created At',
                'Updated At'
            ]);

            // Add data rows
            foreach ($interests as $interest) {
                fputcsv($file, [
                    $interest->id,
                    $interest->name,
                    $interest->description,
                    $interest->icon,
                    $interest->color,
                    $interest->is_featured ? 'Yes' : 'No',
                    $interest->created_at->format('Y-m-d H:i:s'),
                    $interest->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import interests from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $csvData = file_get_contents($file);
            $rows = array_map('str_getcsv', explode("\n", $csvData));
            $header = array_shift($rows);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows

                try {
                    $data = array_combine($header, $row);

                    // Validate color format
                    $color = $data['Color'] ?? '#3b82f6';
                    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                        $color = '#3b82f6'; // Default color if invalid
                    }

                    Interest::create([
                        'name' => $data['Name'] ?? '',
                        'description' => $data['Description'] ?? null,
                        'icon' => $data['Icon'] ?? '🎯',
                        'color' => $color,
                        'is_featured' => ($data['Is Featured'] ?? 'No') === 'Yes',
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} interest(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " and " . (count($errors) - 3) . " more.";
                }
            }

            return redirect()->route('admin.interests.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.interests.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate a specific field.
     */
    public function validateField(Request $request)
    {
        $field = $request->get('field');
        $value = $request->get('value');

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:10',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ];

        if (!isset($rules[$field])) {
            return response()->json(['valid' => false, 'message' => 'Invalid field']);
        }

        $validator = validator([$field => $value], [$field => $rules[$field]]);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first($field)
            ]);
        }

        return response()->json(['valid' => true]);
    }

    /**
     * Get interests by category (grouping similar interests).
     */
    public function getByCategory()
    {
        $interests = Interest::orderBy('name', 'asc')->get();

        // Group interests by common categories based on keywords
        $categories = [
            'Technology' => $interests->filter(function ($interest) {
                return str_contains(strtolower($interest->name), 'tech') ||
                    str_contains(strtolower($interest->name), 'programming') ||
                    str_contains(strtolower($interest->name), 'coding') ||
                    str_contains(strtolower($interest->name), 'development') ||
                    str_contains(strtolower($interest->name), 'software');
            }),
            'Creative' => $interests->filter(function ($interest) {
                return str_contains(strtolower($interest->name), 'art') ||
                    str_contains(strtolower($interest->name), 'design') ||
                    str_contains(strtolower($interest->name), 'music') ||
                    str_contains(strtolower($interest->name), 'photography') ||
                    str_contains(strtolower($interest->name), 'creative');
            }),
            'Sports & Fitness' => $interests->filter(function ($interest) {
                return str_contains(strtolower($interest->name), 'sport') ||
                    str_contains(strtolower($interest->name), 'fitness') ||
                    str_contains(strtolower($interest->name), 'running') ||
                    str_contains(strtolower($interest->name), 'gym') ||
                    str_contains(strtolower($interest->name), 'exercise');
            }),
            'Learning' => $interests->filter(function ($interest) {
                return str_contains(strtolower($interest->name), 'learning') ||
                    str_contains(strtolower($interest->name), 'reading') ||
                    str_contains(strtolower($interest->name), 'study') ||
                    str_contains(strtolower($interest->name), 'education') ||
                    str_contains(strtolower($interest->name), 'research');
            }),
            'Other' => $interests->filter(function ($interest) {
                // Interests that don't fit in other categories
                $name = strtolower($interest->name);
                return !str_contains($name, 'tech') && !str_contains($name, 'programming') &&
                    !str_contains($name, 'art') && !str_contains($name, 'design') &&
                    !str_contains($name, 'sport') && !str_contains($name, 'fitness') &&
                    !str_contains($name, 'learning') && !str_contains($name, 'reading');
            })
        ];

        // Remove empty categories
        $categories = array_filter($categories, function ($category) {
            return $category->isNotEmpty();
        });

        return response()->json($categories);
    }

    /**
     * Get color distribution of interests.
     */
    public function getColorDistribution()
    {
        $interests = Interest::select('color')->get();
        $colorCounts = $interests->groupBy('color')->map->count();

        return response()->json($colorCounts);
    }

    /**
     * Get suggested colors based on existing interests.
     */
    public function getSuggestedColors()
    {
        $usedColors = Interest::distinct()->pluck('color')->toArray();

        $allColors = [
            '#3b82f6',
            '#10b981',
            '#f59e0b',
            '#ef4444',
            '#8b5cf6',
            '#06b6d4',
            '#f97316',
            '#84cc16',
            '#ec4899',
            '#6366f1',
            '#14b8a6',
            '#f43f5e',
            '#0ea5e9',
            '#22c55e',
            '#eab308',
            '#dc2626',
            '#a855f7',
            '#0891b2',
            '#ea580c',
            '#65a30d',
            '#db2777',
            '#4f46e5',
            '#059669',
            '#be123c'
        ];

        $availableColors = array_diff($allColors, $usedColors);

        return response()->json([
            'suggested' => array_values($availableColors),
            'used' => $usedColors,
            'total_available' => count($availableColors)
        ]);
    }

    /**
     * Bulk update featured status.
     */
    public function bulkUpdateFeatured(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:interests,id',
            'is_featured' => 'required|boolean'
        ]);

        Interest::whereIn('id', $request->ids)->update([
            'is_featured' => $request->is_featured
        ]);

        $action = $request->is_featured ? 'featured' : 'unfeatured';
        $count = count($request->ids);

        return redirect()->back()
            ->with('success', "{$count} interest(s) {$action} successfully.");
    }

    /**
     * Get interests for portfolio display.
     */
    public function getForPortfolio()
    {
        $featuredInterests = Interest::where('is_featured', true)
            ->orderBy('name', 'asc')
            ->get();

        $regularInterests = Interest::where('is_featured', false)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'featured' => $featuredInterests,
            'regular' => $regularInterests,
            'total_featured' => $featuredInterests->count(),
            'total_regular' => $regularInterests->count()
        ]);
    }

    /**
     * Get random interests for inspiration.
     */
    public function getRandomInterests($count = 6)
    {
        $randomInterests = Interest::inRandomOrder()
            ->limit($count)
            ->get();

        return response()->json($randomInterests);
    }

    /**
     * Duplicate an interest.
     */
    public function duplicate(Interest $interest)
    {
        $duplicatedInterest = $interest->replicate();
        $duplicatedInterest->name = $interest->name . ' (Copy)';
        $duplicatedInterest->is_featured = false; // Duplicated interests are not featured by default
        $duplicatedInterest->save();

        return redirect()->route('admin.interests.index')
            ->with('success', 'Interest duplicated successfully.');
    }

    /**
     * Get interest trends (most used colors, common keywords, etc.)
     */
    public function getTrends()
    {
        $interests = Interest::all();

        // Most used colors
        $colorUsage = $interests->groupBy('color')->map->count()->sortDesc();

        // Most common words in interest names
        $allWords = $interests->pluck('name')->flatMap(function ($name) {
            return explode(' ', strtolower($name));
        })->filter(function ($word) {
            return strlen($word) > 2; // Filter out short words
        });

        $wordFrequency = $allWords->countBy()->sortDesc()->take(10);

        // Featured vs regular distribution
        $featuredCount = $interests->where('is_featured', true)->count();
        $regularCount = $interests->where('is_featured', false)->count();

        // Monthly creation trend (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Interest::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyTrend[$date->format('M Y')] = $count;
        }

        return response()->json([
            'color_usage' => $colorUsage->take(5),
            'word_frequency' => $wordFrequency,
            'featured_distribution' => [
                'featured' => $featuredCount,
                'regular' => $regularCount
            ],
            'monthly_trend' => $monthlyTrend,
            'total_interests' => $interests->count(),
            'with_description' => $interests->whereNotNull('description')->where('description', '!=', '')->count()
        ]);
    }
}
