<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of social links
     */
    public function index()
    {
        $socialLinks = SocialLink::orderBy('sort_order')
            ->orderBy('platform')
            ->get();

        return Inertia::render('Admin/SocialLinks/Index', [
            'socialLinks' => $socialLinks,
            'platformOptions' => $this->getPlatformOptions()
        ]);
    }

    /**
     * Show the form for creating a new social link
     */
    public function create()
    {
        return Inertia::render('Admin/SocialLinks/Create', [
            'platformOptions' => $this->getPlatformOptions(),
            'colorOptions' => $this->getColorOptions()
        ]);
    }

    /**
     * Store a newly created social link
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Set default values
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (SocialLink::max('sort_order') ?? 0) + 1;
        }

        if (!isset($validated['icon'])) {
            $validated['icon'] = $this->getDefaultIcon($validated['platform']);
        }

        if (!isset($validated['color'])) {
            $validated['color'] = $this->getDefaultColor($validated['platform']);
        }

        SocialLink::create($validated);

        // Clear cache
        $this->clearSocialLinksCache();

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link created successfully.');
    }

    /**
     * Show the form for editing the specified social link
     */
    public function edit(SocialLink $socialLink)
    {
        return Inertia::render('Admin/SocialLinks/Edit', [
            'socialLink' => $socialLink,
            'platformOptions' => $this->getPlatformOptions(),
            'colorOptions' => $this->getColorOptions()
        ]);
    }

    /**
     * Update the specified social link
     */
    public function update(Request $request, SocialLink $socialLink)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Set default icon if not provided
        if (empty($validated['icon'])) {
            $validated['icon'] = $this->getDefaultIcon($validated['platform']);
        }

        // Set default color if not provided
        if (empty($validated['color'])) {
            $validated['color'] = $this->getDefaultColor($validated['platform']);
        }

        $socialLink->update($validated);

        // Clear cache
        $this->clearSocialLinksCache();

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link updated successfully.');
    }

    /**
     * Remove the specified social link
     */
    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        // Clear cache
        $this->clearSocialLinksCache();

        return redirect()->route('admin.social-links.index')
            ->with('success', 'Social link deleted successfully.');
    }

    /**
     * Reorder social links
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'links' => 'required|array',
            'links.*.id' => 'required|exists:social_links,id',
            'links.*.sort_order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            foreach ($request->links as $linkData) {
                SocialLink::where('id', $linkData['id'])
                    ->update(['sort_order' => $linkData['sort_order']]);
            }

            // Clear cache
            $this->clearSocialLinksCache();

            return response()->json([
                'success' => true,
                'message' => 'Social links reordered successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder social links: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available platform options
     */
    private function getPlatformOptions()
    {
        return [
            'GitHub' => 'GitHub',
            'LinkedIn' => 'LinkedIn',
            'Twitter' => 'Twitter',
            'Instagram' => 'Instagram',
            'Facebook' => 'Facebook',
            'YouTube' => 'YouTube',
            'TikTok' => 'TikTok',
            'Discord' => 'Discord',
            'Telegram' => 'Telegram',
            'WhatsApp' => 'WhatsApp',
            'Email' => 'Email',
            'Website' => 'Personal Website',
            'Behance' => 'Behance',
            'Dribbble' => 'Dribbble',
            'Medium' => 'Medium',
            'Dev.to' => 'Dev.to',
            'Stack Overflow' => 'Stack Overflow',
            'CodePen' => 'CodePen',
            'Figma' => 'Figma',
            'Other' => 'Other'
        ];
    }

    /**
     * Get color options for platforms
     */
    private function getColorOptions()
    {
        return [
            '#1DA1F2' => 'Twitter Blue',
            '#0077B5' => 'LinkedIn Blue',
            '#333333' => 'GitHub Dark',
            '#E4405F' => 'Instagram Pink',
            '#1877F2' => 'Facebook Blue',
            '#FF0000' => 'YouTube Red',
            '#000000' => 'TikTok Black',
            '#5865F2' => 'Discord Purple',
            '#0088CC' => 'Telegram Blue',
            '#25D366' => 'WhatsApp Green',
            '#EA4335' => 'Gmail Red',
            '#1769FF' => 'Behance Blue',
            '#EA4C89' => 'Dribbble Pink',
            '#000000' => 'Medium Black',
            '#0A0A0A' => 'Dev.to Dark',
            '#F48024' => 'Stack Overflow Orange',
            '#000000' => 'CodePen Black',
            '#F24E1E' => 'Figma Orange',
            '#6366F1' => 'Custom Purple',
            '#8B5CF6' => 'Custom Violet',
            '#06B6D4' => 'Custom Cyan',
            '#10B981' => 'Custom Emerald'
        ];
    }

    /**
     * Get default icon for platform
     */
    private function getDefaultIcon($platform)
    {
        $icons = [
            'GitHub' => '🔗',
            'LinkedIn' => '💼',
            'Twitter' => '🐦',
            'Instagram' => '📷',
            'Facebook' => '👥',
            'YouTube' => '📺',
            'TikTok' => '🎵',
            'Discord' => '💬',
            'Telegram' => '📱',
            'WhatsApp' => '💬',
            'Email' => '✉️',
            'Website' => '🌐',
            'Behance' => '🎨',
            'Dribbble' => '🏀',
            'Medium' => '📝',
            'Dev.to' => '💻',
            'Stack Overflow' => '❓',
            'CodePen' => '🖊️',
            'Figma' => '🎨'
        ];

        return $icons[$platform] ?? '🔗';
    }

    /**
     * Get default color for platform
     */
    private function getDefaultColor($platform)
    {
        $colors = [
            'GitHub' => '#333333',
            'LinkedIn' => '#0077B5',
            'Twitter' => '#1DA1F2',
            'Instagram' => '#E4405F',
            'Facebook' => '#1877F2',
            'YouTube' => '#FF0000',
            'TikTok' => '#000000',
            'Discord' => '#5865F2',
            'Telegram' => '#0088CC',
            'WhatsApp' => '#25D366',
            'Email' => '#EA4335',
            'Website' => '#6366F1',
            'Behance' => '#1769FF',
            'Dribbble' => '#EA4C89',
            'Medium' => '#000000',
            'Dev.to' => '#0A0A0A',
            'Stack Overflow' => '#F48024',
            'CodePen' => '#000000',
            'Figma' => '#F24E1E'
        ];

        return $colors[$platform] ?? '#6366F1';
    }

    /**
     * Clear social links cache
     */
    private function clearSocialLinksCache()
    {
        $cacheKeys = [
            'social_links_data',
            'dynamic_portfolio_data_v2'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
