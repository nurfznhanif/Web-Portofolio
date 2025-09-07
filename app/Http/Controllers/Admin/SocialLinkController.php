<?php
// app/Http/Controllers/Admin/SocialLinkController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SocialLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $socialLinks = SocialLink::orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();

            return Inertia::render('Admin/SocialLink/Index', [
                'socialLinks' => $socialLinks,
                'stats' => [
                    'total' => $socialLinks->count(),
                    'active' => $socialLinks->where('is_active', true)->count(),
                    'inactive' => $socialLinks->where('is_active', false)->count(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching social links: ' . $e->getMessage());
            return back()->with('error', 'Failed to load social links.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the next order number
        $nextOrder = SocialLink::max('order') + 1;

        return Inertia::render('Admin/SocialLink/Create', [
            'nextOrder' => $nextOrder,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Set default values
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['order'] = $validated['order'] ?? (SocialLink::max('order') + 1);

            $socialLink = SocialLink::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', "Social link for {$socialLink->platform} created successfully!");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating social link: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to create social link. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialLink $socialLink)
    {
        return Inertia::render('Admin/SocialLink/Show', [
            'socialLink' => $socialLink,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialLink $socialLink)
    {
        return Inertia::render('Admin/SocialLink/Edit', [
            'socialLink' => $socialLink,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'color' => 'required|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Set default values
            $validated['is_active'] = $validated['is_active'] ?? false;

            $socialLink->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', "Social link for {$socialLink->platform} updated successfully!");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating social link: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update social link. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialLink $socialLink)
    {
        try {
            DB::beginTransaction();

            $platform = $socialLink->platform;
            $socialLink->delete();

            // Reorder remaining items
            $this->reorderAfterDeletion($socialLink->order);

            DB::commit();

            return redirect()
                ->route('admin.social-links.index')
                ->with('success', "Social link for {$platform} deleted successfully!");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting social link: ' . $e->getMessage());

            return back()->with('error', 'Failed to delete social link. Please try again.');
        }
    }

    /**
     * Toggle the active status of a social link.
     */
    public function toggleActive(SocialLink $socialLink)
    {
        try {
            $socialLink->update([
                'is_active' => !$socialLink->is_active
            ]);

            $status = $socialLink->is_active ? 'activated' : 'deactivated';

            return back()->with('success', "Social link for {$socialLink->platform} {$status} successfully!");
        } catch (\Exception $e) {
            Log::error('Error toggling social link status: ' . $e->getMessage());
            return back()->with('error', 'Failed to update social link status.');
        }
    }

    /**
     * Reorder social links.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:social_links,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['items'] as $item) {
                SocialLink::where('id', $item['id'])
                    ->update(['order' => $item['order']]);
            }

            DB::commit();

            return back()->with('success', 'Social links reordered successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error reordering social links: ' . $e->getMessage());
            return back()->with('error', 'Failed to reorder social links.');
        }
    }

    /**
     * Bulk delete social links.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:social_links,id',
        ]);

        try {
            DB::beginTransaction();

            $count = SocialLink::whereIn('id', $validated['ids'])->count();
            SocialLink::whereIn('id', $validated['ids'])->delete();

            // Reorder remaining items
            $this->reorderAll();

            DB::commit();

            return back()->with('success', "{$count} social link(s) deleted successfully!");
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error bulk deleting social links: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete social links.');
        }
    }

    /**
     * Get social links for API.
     */
    public function api()
    {
        try {
            $socialLinks = SocialLink::active()
                ->ordered()
                ->select(['id', 'platform', 'url', 'color', 'order'])
                ->get();

            return response()->json([
                'data' => $socialLinks,
                'count' => $socialLinks->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching social links API: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch social links'
            ], 500);
        }
    }

    /**
     * Reorder items after deletion.
     */
    private function reorderAfterDeletion($deletedOrder)
    {
        SocialLink::where('order', '>', $deletedOrder)
            ->decrement('order');
    }

    /**
     * Reorder all items sequentially.
     */
    private function reorderAll()
    {
        $socialLinks = SocialLink::orderBy('order')->get();

        foreach ($socialLinks as $index => $socialLink) {
            $socialLink->update(['order' => $index + 1]);
        }
    }

    /**
     * Export social links data.
     */
    public function export()
    {
        try {
            $socialLinks = SocialLink::orderBy('order')->get();

            $data = [
                'exported_at' => now()->format('Y-m-d H:i:s'),
                'total_links' => $socialLinks->count(),
                'social_links' => $socialLinks->map(function ($link) {
                    return [
                        'platform' => $link->platform,
                        'url' => $link->url,
                        'color' => $link->color,
                        'is_active' => $link->is_active,
                        'order' => $link->order,
                        'created_at' => $link->created_at->format('Y-m-d H:i:s'),
                    ];
                })
            ];

            return response()->json($data)
                ->header('Content-Disposition', 'attachment; filename="social-links-' . now()->format('Y-m-d') . '.json"');
        } catch (\Exception $e) {
            Log::error('Error exporting social links: ' . $e->getMessage());
            return back()->with('error', 'Failed to export social links.');
        }
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats()
    {
        try {
            $total = SocialLink::count();
            $active = SocialLink::where('is_active', true)->count();
            $platforms = SocialLink::distinct('platform')->count();
            $recentlyAdded = SocialLink::where('created_at', '>=', now()->subDays(7))->count();

            return response()->json([
                'total' => $total,
                'active' => $active,
                'inactive' => $total - $active,
                'platforms' => $platforms,
                'recently_added' => $recentlyAdded,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting social links stats: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get statistics'], 500);
        }
    }
}
