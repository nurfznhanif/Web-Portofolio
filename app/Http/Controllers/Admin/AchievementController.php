<?php
// app/Http/Controllers/Admin/AchievementController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index()
    {
        $achievements = Achievement::orderBy('date_achieved', 'desc')->get();

        return Inertia::render('Admin/Achievement/Index', [
            'achievements' => $achievements
        ]);
    }

    /**
     * Show the form for creating a new achievement.
     */
    public function create()
    {
        return Inertia::render('Admin/Achievement/Create');
    }

    /**
     * Store a newly created achievement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date_achieved' => 'required|date',
            'is_featured' => 'boolean',
        ]);

        // Set default value for is_featured if not provided
        if (!isset($validated['is_featured'])) {
            $validated['is_featured'] = false;
        }

        Achievement::create($validated);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    /**
     * Display the specified achievement.
     */
    public function show(Achievement $achievement)
    {
        return Inertia::render('Admin/Achievement/Show', [
            'achievement' => $achievement
        ]);
    }

    /**
     * Show the form for editing the specified achievement.
     */
    public function edit(Achievement $achievement)
    {
        return Inertia::render('Admin/Achievement/Edit', [
            'achievement' => $achievement
        ]);
    }

    /**
     * Update the specified achievement in storage.
     */
    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'date_achieved' => 'required|date',
            'is_featured' => 'boolean',
        ]);

        // Set default value for is_featured if not provided
        if (!isset($validated['is_featured'])) {
            $validated['is_featured'] = false;
        }

        $achievement->update($validated);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified achievement from storage.
     */
    public function destroy(Achievement $achievement)
    {
        $achievement->delete();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }

    /**
     * Toggle the featured status of an achievement.
     */
    public function toggleFeatured(Achievement $achievement)
    {
        $achievement->update([
            'is_featured' => !$achievement->is_featured
        ]);

        $status = $achievement->is_featured ? 'featured' : 'unfeatured';

        return redirect()->back()
            ->with('success', "Achievement {$status} successfully.");
    }

    /**
     * Search achievements (for API endpoints).
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $achievements = Achievement::where('title', 'ILIKE', "%{$query}%")
            ->orWhere('issuer', 'ILIKE', "%{$query}%")
            ->orWhere('description', 'ILIKE', "%{$query}%")
            ->orderBy('date_achieved', 'desc')
            ->limit(10)
            ->get();

        return response()->json($achievements);
    }

    /**
     * Get achievement statistics.
     */
    public function getStats()
    {
        $stats = [
            'total' => Achievement::count(),
            'featured' => Achievement::where('is_featured', true)->count(),
            'this_year' => Achievement::whereYear('date_achieved', now()->year)->count(),
            'recent' => Achievement::where('date_achieved', '>=', now()->subMonths(6))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk delete achievements.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:achievements,id'
        ]);

        $deletedCount = Achievement::whereIn('id', $validated['ids'])->delete();

        return redirect()->back()
            ->with('success', "{$deletedCount} achievements deleted successfully.");
    }

    /**
     * Export achievements data.
     */
    public function export()
    {
        $achievements = Achievement::orderBy('date_achieved', 'desc')->get();

        $csvData = "Title,Issuer,Description,Date Achieved,Featured\n";

        foreach ($achievements as $achievement) {
            $csvData .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                str_replace('"', '""', $achievement->title),
                str_replace('"', '""', $achievement->issuer),
                str_replace('"', '""', $achievement->description ?? ''),
                $achievement->date_achieved->format('Y-m-d'),
                $achievement->is_featured ? 'Yes' : 'No'
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="achievements.csv"');
    }

    /**
     * Import achievements from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $csvData = file_get_contents($file->getRealPath());
        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows);

        $importedCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            if (empty(array_filter($row))) continue; // Skip empty rows

            try {
                $data = array_combine($header, $row);

                Achievement::create([
                    'title' => $data['Title'] ?? '',
                    'issuer' => $data['Issuer'] ?? '',
                    'description' => $data['Description'] ?? null,
                    'date_achieved' => $data['Date Achieved'] ?? now(),
                    'is_featured' => isset($data['Featured']) ?
                        (strtolower($data['Featured']) === 'yes' || $data['Featured'] === '1') : false,
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        if ($importedCount > 0) {
            $message = "{$importedCount} achievements imported successfully.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " rows had errors.";
            }
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()
            ->with('error', 'No achievements were imported. Please check your file format.')
            ->withErrors($errors);
    }

    /**
     * Reorder achievements.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'achievements' => 'required|array',
            'achievements.*.id' => 'required|integer|exists:achievements,id',
            'achievements.*.order' => 'required|integer|min:0'
        ]);

        foreach ($validated['achievements'] as $achievementData) {
            Achievement::where('id', $achievementData['id'])
                ->update(['order' => $achievementData['order']]);
        }

        return response()->json(['message' => 'Achievements reordered successfully.']);
    }
}
