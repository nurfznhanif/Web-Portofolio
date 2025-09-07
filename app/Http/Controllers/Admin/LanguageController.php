<?php
// app/Http/Controllers/Admin/LanguageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages.
     */
    public function index()
    {
        $languages = Language::orderBy('name', 'asc')->get();

        return Inertia::render('Admin/Language/Index', [
            'languages' => $languages
        ]);
    }

    /**
     * Show the form for creating a new language.
     */
    public function create()
    {
        return Inertia::render('Admin/Language/Create');
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:languages,name',
            'level' => 'required|string|in:A1,A2,B1,B2,C1,C2,native',
            'proficiency' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        Language::create($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language added successfully.');
    }

    /**
     * Display the specified language.
     */
    public function show(Language $language)
    {
        return Inertia::render('Admin/Language/Show', [
            'language' => $language
        ]);
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language)
    {
        return Inertia::render('Admin/Language/Edit', [
            'language' => $language
        ]);
    }

    /**
     * Update the specified language in storage.
     */
    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:languages,name,' . $language->id,
            'level' => 'required|string|in:A1,A2,B1,B2,C1,C2,native',
            'proficiency' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        $language->update($validated);

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language)
    {
        $language->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    /**
     * Bulk delete languages.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:languages,id'
        ]);

        Language::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.languages.index')
            ->with('success', count($request->ids) . ' language(s) deleted successfully.');
    }

    /**
     * Search languages.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $languages = Language::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return response()->json($languages);
    }

    /**
     * Get language statistics.
     */
    public function getStats()
    {
        $total = Language::count();

        $nativeCount = Language::where('level', 'native')->count();
        $advancedCount = Language::whereIn('level', ['C1', 'C2'])->count();
        $intermediateCount = Language::whereIn('level', ['B1', 'B2'])->count();
        $beginnerCount = Language::whereIn('level', ['A1', 'A2'])->count();

        $averageProficiency = Language::avg('proficiency') ?? 0;

        $levelDistribution = Language::selectRaw('level, COUNT(*) as count')
            ->groupBy('level')
            ->pluck('count', 'level')
            ->toArray();

        $recentlyAdded = Language::where('created_at', '>=', now()->subDays(30))->count();

        return response()->json([
            'total' => $total,
            'native' => $nativeCount,
            'advanced' => $advancedCount,
            'intermediate' => $intermediateCount,
            'beginner' => $beginnerCount,
            'average_proficiency' => round($averageProficiency, 1),
            'level_distribution' => $levelDistribution,
            'recently_added' => $recentlyAdded,
        ]);
    }

    /**
     * Export languages to CSV.
     */
    public function export()
    {
        $languages = Language::orderBy('name', 'asc')->get();

        $filename = 'languages_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($languages) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Level',
                'Proficiency (%)',
                'Description',
                'Created At',
                'Updated At'
            ]);

            // Add data rows
            foreach ($languages as $language) {
                fputcsv($file, [
                    $language->id,
                    $language->name,
                    $language->level,
                    $language->proficiency,
                    $language->description,
                    $language->created_at->format('Y-m-d H:i:s'),
                    $language->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import languages from CSV.
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
            $validLevels = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'native'];

            foreach ($rows as $index => $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows

                try {
                    $data = array_combine($header, $row);

                    // Validate level
                    $level = $data['Level'] ?? '';
                    if (!in_array($level, $validLevels)) {
                        $errors[] = "Row " . ($index + 2) . ": Invalid level '{$level}'. Must be one of: " . implode(', ', $validLevels);
                        continue;
                    }

                    // Validate proficiency
                    $proficiency = intval($data['Proficiency (%)'] ?? 0);
                    if ($proficiency < 0 || $proficiency > 100) {
                        $errors[] = "Row " . ($index + 2) . ": Proficiency must be between 0 and 100";
                        continue;
                    }

                    // Check for duplicate names
                    if (Language::where('name', $data['Name'])->exists()) {
                        $errors[] = "Row " . ($index + 2) . ": Language '{$data['Name']}' already exists";
                        continue;
                    }

                    Language::create([
                        'name' => $data['Name'] ?? '',
                        'level' => $level,
                        'proficiency' => $proficiency,
                        'description' => $data['Description'] ?? null,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} language(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " and " . (count($errors) - 3) . " more.";
                }
            }

            return redirect()->route('admin.languages.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.languages.index')
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
        $languageId = $request->get('language_id'); // For update validation

        $rules = [
            'name' => 'required|string|max:255|unique:languages,name' . ($languageId ? ",$languageId" : ''),
            'level' => 'required|string|in:A1,A2,B1,B2,C1,C2,native',
            'proficiency' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:1000',
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
     * Get languages by proficiency level.
     */
    public function getByProficiencyLevel()
    {
        $languagesByLevel = [
            'native' => Language::where('level', 'native')->orderBy('name')->get(),
            'advanced' => Language::whereIn('level', ['C1', 'C2'])->orderBy('level', 'desc')->orderBy('name')->get(),
            'intermediate' => Language::whereIn('level', ['B1', 'B2'])->orderBy('level', 'desc')->orderBy('name')->get(),
            'beginner' => Language::whereIn('level', ['A1', 'A2'])->orderBy('level', 'desc')->orderBy('name')->get(),
        ];

        return response()->json($languagesByLevel);
    }

    /**
     * Get top languages by proficiency.
     */
    public function getTopLanguages($limit = 5)
    {
        $topLanguages = Language::orderBy('proficiency', 'desc')
            ->orderBy('name')
            ->limit($limit)
            ->get();

        return response()->json($topLanguages);
    }

    /**
     * Update proficiency for a language.
     */
    public function updateProficiency(Request $request, Language $language)
    {
        $request->validate([
            'proficiency' => 'required|integer|min:0|max:100'
        ]);

        $language->update([
            'proficiency' => $request->proficiency
        ]);

        return redirect()->back()
            ->with('success', 'Proficiency updated successfully.');
    }

    /**
     * Get language learning recommendations.
     */
    public function getRecommendations()
    {
        $currentLanguages = Language::pluck('name')->toArray();

        // Common languages that might be good to learn
        $recommendedLanguages = [
            'Spanish' => 'Most spoken language in the world by number of native speakers',
            'French' => 'Official language in 29 countries',
            'German' => 'Most widely spoken native language in Europe',
            'Italian' => 'Language of art, music, and culture',
            'Portuguese' => 'Official language in 9 countries',
            'Russian' => 'Most widely spoken Slavic language',
            'Chinese (Mandarin)' => 'Most spoken language in the world',
            'Japanese' => 'Important for technology and business',
            'Korean' => 'Growing importance in technology and culture',
            'Arabic' => 'Official language in 22 countries',
            'Hindi' => 'Widely spoken in India',
            'Dutch' => 'Useful for business in Europe',
        ];

        // Filter out languages already in the database
        $suggestions = collect($recommendedLanguages)
            ->filter(function ($description, $language) use ($currentLanguages) {
                return !in_array($language, $currentLanguages);
            })
            ->take(6);

        return response()->json($suggestions);
    }

    /**
     * Get language proficiency distribution.
     */
    public function getProficiencyDistribution()
    {
        $distribution = [
            'Native (95-100%)' => Language::whereBetween('proficiency', [95, 100])->count(),
            'Near Native (85-94%)' => Language::whereBetween('proficiency', [85, 94])->count(),
            'Advanced (70-84%)' => Language::whereBetween('proficiency', [70, 84])->count(),
            'Intermediate (50-69%)' => Language::whereBetween('proficiency', [50, 69])->count(),
            'Elementary (30-49%)' => Language::whereBetween('proficiency', [30, 49])->count(),
            'Beginner (0-29%)' => Language::whereBetween('proficiency', [0, 29])->count(),
        ];

        return response()->json($distribution);
    }

    /**
     * Bulk update proficiency levels.
     */
    public function bulkUpdateProficiency(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:languages,id',
            'updates.*.proficiency' => 'required|integer|min:0|max:100'
        ]);

        $updated = 0;
        foreach ($request->updates as $update) {
            Language::where('id', $update['id'])->update([
                'proficiency' => $update['proficiency']
            ]);
            $updated++;
        }

        return redirect()->back()
            ->with('success', "Updated proficiency for {$updated} language(s).");
    }

    /**
     * Get suggested CEFR level based on proficiency.
     */
    public function getSuggestedLevel($proficiency)
    {
        if ($proficiency >= 95) return 'native';
        if ($proficiency >= 85) return 'C2';
        if ($proficiency >= 75) return 'C1';
        if ($proficiency >= 65) return 'B2';
        if ($proficiency >= 55) return 'B1';
        if ($proficiency >= 35) return 'A2';
        return 'A1';
    }

    /**
     * API endpoint to get suggested level.
     */
    public function getSuggestedLevelApi(Request $request)
    {
        $request->validate([
            'proficiency' => 'required|integer|min:0|max:100'
        ]);

        $suggestedLevel = $this->getSuggestedLevel($request->proficiency);

        return response()->json([
            'suggested_level' => $suggestedLevel,
            'proficiency' => $request->proficiency
        ]);
    }
}
