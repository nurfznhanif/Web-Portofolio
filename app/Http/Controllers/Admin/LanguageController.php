<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages
     */
    public function index()
    {
        $languages = Language::active()
            ->ordered()
            ->get();

        return Inertia::render('Admin/Languages/Index', [
            'languages' => $languages,
            'levelOptions' => $this->getLevelOptions()
        ]);
    }

    /**
     * Show the form for creating a new language
     */
    public function create()
    {
        return Inertia::render('Admin/Languages/Create', [
            'levelOptions' => $this->getLevelOptions(),
            'languageOptions' => $this->getLanguageOptions()
        ]);
    }

    /**
     * Store a newly created language
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:languages,name',
            'level' => 'required|string|in:native,fluent,intermediate,basic',
            'proficiency' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'certificate' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Auto-set proficiency based on level if not provided
        if (!$request->has('proficiency') || $validated['proficiency'] === 0) {
            $validated['proficiency'] = $this->getDefaultProficiency($validated['level']);
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (Language::max('sort_order') ?? 0) + 1;
        }

        Language::create($validated);

        // Clear cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language created successfully.');
    }

    /**
     * Show the form for editing the specified language
     */
    public function edit(Language $language)
    {
        return Inertia::render('Admin/Languages/Edit', [
            'language' => $language,
            'levelOptions' => $this->getLevelOptions(),
            'languageOptions' => $this->getLanguageOptions()
        ]);
    }

    /**
     * Update the specified language
     */
    public function update(Request $request, Language $language)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:languages,name,' . $language->id,
            'level' => 'required|string|in:native,fluent,intermediate,basic',
            'proficiency' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'certificate' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $language->update($validated);

        // Clear cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified language
     */
    public function destroy(Language $language)
    {
        $language->delete();

        // Clear cache
        $this->clearLanguageCache();

        return redirect()->route('admin.languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    /**
     * Get available level options
     */
    private function getLevelOptions()
    {
        return [
            'native' => [
                'label' => 'Native',
                'description' => 'First language or native-level fluency',
                'default_proficiency' => 100
            ],
            'fluent' => [
                'label' => 'Fluent',
                'description' => 'Near-native fluency, comfortable in all situations',
                'default_proficiency' => 90
            ],
            'intermediate' => [
                'label' => 'Intermediate',
                'description' => 'Good conversational ability, some limitations',
                'default_proficiency' => 70
            ],
            'basic' => [
                'label' => 'Basic',
                'description' => 'Basic conversational ability, limited vocabulary',
                'default_proficiency' => 40
            ]
        ];
    }

    /**
     * Get common language options
     */
    private function getLanguageOptions()
    {
        return [
            'Bahasa Indonesia' => 'Bahasa Indonesia',
            'English' => 'English',
            'Arabic' => 'Arabic',
            'Mandarin Chinese' => 'Mandarin Chinese',
            'Spanish' => 'Spanish',
            'French' => 'French',
            'German' => 'German',
            'Japanese' => 'Japanese',
            'Korean' => 'Korean',
            'Portuguese' => 'Portuguese',
            'Russian' => 'Russian',
            'Italian' => 'Italian',
            'Dutch' => 'Dutch',
            'Thai' => 'Thai',
            'Vietnamese' => 'Vietnamese',
            'Hindi' => 'Hindi',
            'Tamil' => 'Tamil',
            'Tagalog' => 'Tagalog',
            'Malay' => 'Malay',
            'Other' => 'Other'
        ];
    }

    /**
     * Get default proficiency for level
     */
    private function getDefaultProficiency($level)
    {
        $defaults = [
            'native' => 100,
            'fluent' => 90,
            'intermediate' => 70,
            'basic' => 40
        ];

        return $defaults[$level] ?? 50;
    }

    /**
     * Clear language cache
     */
    private function clearLanguageCache()
    {
        $cacheKeys = [
            'languages_data',
            'dynamic_portfolio_data_v2'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
