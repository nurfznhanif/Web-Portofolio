<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProfileSettingController extends Controller
{
    /**
     * Display a listing of profile settings
     */
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 15);

        $query = ProfileSetting::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $settings = $query->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('key')
            ->paginate($perPage);

        // Get all categories for filter
        $categories = ProfileSetting::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
            'categories' => $categories,
            'filters' => [
                'category' => $category,
                'search' => $search,
                'per_page' => $perPage
            ]
        ]);
    }

    /**
     * Show the form for creating a new setting
     */
    public function create()
    {
        $categories = ProfileSetting::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Settings/Create', [
            'categories' => $categories,
            'types' => $this->getSettingTypes()
        ]);
    }

    /**
     * Store a newly created setting
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:profile_settings,key',
            'value' => 'nullable',
            'type' => 'required|string|in:text,number,boolean,json,image,file,date,email,url,textarea',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:255',
            'is_public' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle value based on type
        $validated['value'] = $this->processValueByType($validated['value'], $validated['type']);

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = ProfileSetting::where('category', $validated['category'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $setting = ProfileSetting::create($validated);

        // Clear relevant caches
        $this->clearSettingCaches();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    /**
     * Show the form for editing the specified setting
     */
    public function edit(ProfileSetting $setting)
    {
        $categories = ProfileSetting::distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return Inertia::render('Admin/Settings/Edit', [
            'setting' => $setting,
            'categories' => $categories,
            'types' => $this->getSettingTypes()
        ]);
    }

    /**
     * Update the specified setting
     */
    public function update(Request $request, ProfileSetting $setting)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:profile_settings,key,' . $setting->id,
            'value' => 'nullable',
            'type' => 'required|string|in:text,number,boolean,json,image,file,date,email,url,textarea',
            'description' => 'nullable|string|max:500',
            'category' => 'required|string|max:255',
            'is_public' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle value based on type
        $validated['value'] = $this->processValueByType($validated['value'], $validated['type']);

        $setting->update($validated);

        // Clear relevant caches
        $this->clearSettingCaches();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified setting
     */
    public function destroy(ProfileSetting $setting)
    {
        $setting->delete();

        // Clear relevant caches
        $this->clearSettingCaches();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.id' => 'required|exists:profile_settings,id',
            'settings.*.value' => 'nullable',
            'settings.*.is_public' => 'boolean',
            'settings.*.sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updated = 0;
        foreach ($request->settings as $settingData) {
            $setting = ProfileSetting::find($settingData['id']);
            if ($setting) {
                $updateData = [];

                if (isset($settingData['value'])) {
                    $updateData['value'] = $this->processValueByType(
                        $settingData['value'],
                        $setting->type
                    );
                }

                if (isset($settingData['is_public'])) {
                    $updateData['is_public'] = $settingData['is_public'];
                }

                if (isset($settingData['sort_order'])) {
                    $updateData['sort_order'] = $settingData['sort_order'];
                }

                if (!empty($updateData)) {
                    $setting->update($updateData);
                    $updated++;
                }
            }
        }

        // Clear relevant caches
        $this->clearSettingCaches();

        return response()->json([
            'success' => true,
            'message' => "Successfully updated {$updated} settings."
        ]);
    }

    /**
     * Export settings as JSON
     */
    public function export()
    {
        $settings = ProfileSetting::orderBy('category')
            ->orderBy('sort_order')
            ->get();

        $exportData = [
            'exported_at' => now()->toISOString(),
            'total_settings' => $settings->count(),
            'settings' => $settings->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'description' => $setting->description,
                    'category' => $setting->category,
                    'is_public' => $setting->is_public,
                    'sort_order' => $setting->sort_order
                ];
            })
        ];

        $filename = 'portfolio_settings_' . date('Y_m_d_H_i_s') . '.json';

        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import settings from JSON
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json',
            'overwrite_existing' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $file = $request->file('file');
            $content = file_get_contents($file->getRealPath());
            $data = json_decode($content, true);

            if (!$data || !isset($data['settings'])) {
                throw new \Exception('Invalid JSON format');
            }

            $imported = 0;
            $skipped = 0;
            $overwrite = $request->boolean('overwrite_existing');

            foreach ($data['settings'] as $settingData) {
                $exists = ProfileSetting::where('key', $settingData['key'])->first();

                if ($exists && !$overwrite) {
                    $skipped++;
                    continue;
                }

                ProfileSetting::updateOrCreate(
                    ['key' => $settingData['key']],
                    [
                        'value' => $this->processValueByType($settingData['value'], $settingData['type']),
                        'type' => $settingData['type'],
                        'description' => $settingData['description'] ?? null,
                        'category' => $settingData['category'],
                        'is_public' => $settingData['is_public'] ?? true,
                        'sort_order' => $settingData['sort_order'] ?? 0
                    ]
                );

                $imported++;
            }

            // Clear relevant caches
            $this->clearSettingCaches();

            return redirect()->route('admin.settings.index')
                ->with('success', "Successfully imported {$imported} settings. {$skipped} skipped.");
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Import failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Get available setting types
     */
    private function getSettingTypes()
    {
        return [
            'text' => 'Text',
            'textarea' => 'Long Text',
            'number' => 'Number',
            'boolean' => 'True/False',
            'json' => 'JSON Data',
            'image' => 'Image URL',
            'file' => 'File URL',
            'date' => 'Date',
            'email' => 'Email',
            'url' => 'URL'
        ];
    }

    /**
     * Process value based on type
     */
    private function processValueByType($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return is_array($value) ? json_encode($value) : $value;
            case 'date':
                try {
                    return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
                } catch (\Exception $e) {
                    return null;
                }
            default:
                return $value;
        }
    }

    /**
     * Clear setting-related caches
     */
    private function clearSettingCaches()
    {
        $cacheKeys = [
            'dynamic_portfolio_data_v2',
            'dynamic_profile_data',
            'profile_settings_*'
        ];

        foreach ($cacheKeys as $key) {
            if (str_contains($key, '*')) {
                Cache::forget(str_replace('*', '', $key));
            } else {
                Cache::forget($key);
            }
        }
    }
}
