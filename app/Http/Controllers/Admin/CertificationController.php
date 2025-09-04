<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class CertificationController extends Controller
{
    /**
     * Display a listing of certifications
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', 'all'); // all, valid, expired
        $perPage = $request->get('per_page', 10);

        $query = Certification::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('issuer', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status === 'valid') {
            $query->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            });
        } elseif ($status === 'expired') {
            $query->where('expiry_date', '<=', now());
        }

        $certifications = $query->active()
            ->ordered()
            ->paginate($perPage);

        return Inertia::render('Admin/Certifications/Index', [
            'certifications' => $certifications,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'per_page' => $perPage
            ]
        ]);
    }

    /**
     * Show the form for creating a new certification
     */
    public function create()
    {
        return Inertia::render('Admin/Certifications/Create', [
            'skillOptions' => $this->getSkillOptions()
        ]);
    }

    /**
     * Store a newly created certification
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date|before_or_equal:today',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
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
            $path = $image->storeAs('certifications', $filename, 'public');
            $validated['image'] = $path;
        }

        // Convert skills array to JSON
        if (isset($validated['skills'])) {
            $validated['skills'] = array_filter($validated['skills']); // Remove empty values
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = (Certification::max('sort_order') ?? 0) + 1;
        }

        Certification::create($validated);

        // Clear cache
        $this->clearCertificationCache();

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification created successfully.');
    }

    /**
     * Display the specified certification
     */
    public function show(Certification $certification)
    {
        return Inertia::render('Admin/Certifications/Show', [
            'certification' => $certification
        ]);
    }

    /**
     * Show the form for editing the specified certification
     */
    public function edit(Certification $certification)
    {
        return Inertia::render('Admin/Certifications/Edit', [
            'certification' => $certification,
            'skillOptions' => $this->getSkillOptions()
        ]);
    }

    /**
     * Update the specified certification
     */
    public function update(Request $request, Certification $certification)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date|before_or_equal:today',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url|max:500',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'remove_image' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle image removal
        if ($request->boolean('remove_image') && $certification->image) {
            Storage::disk('public')->delete($certification->image);
            $validated['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($certification->image) {
                Storage::disk('public')->delete($certification->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('certifications', $filename, 'public');
            $validated['image'] = $path;
        }

        // Convert skills array to JSON
        if (isset($validated['skills'])) {
            $validated['skills'] = array_filter($validated['skills']); // Remove empty values
        }

        // Remove the remove_image field as it's not in the model
        unset($validated['remove_image']);

        $certification->update($validated);

        // Clear cache
        $this->clearCertificationCache();

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification updated successfully.');
    }

    /**
     * Remove the specified certification
     */
    public function destroy(Certification $certification)
    {
        // Delete associated image if exists
        if ($certification->image) {
            Storage::disk('public')->delete($certification->image);
        }

        $certification->delete();

        // Clear cache
        $this->clearCertificationCache();

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification deleted successfully.');
    }

    /**
     * Get available skill options
     */
    private function getSkillOptions()
    {
        // Get skills from the skills table
        $skillsFromDB = \App\Models\Skill::pluck('name')->toArray();

        // Common certification skills
        $commonSkills = [
            'PHP',
            'JavaScript',
            'Python',
            'Java',
            'C++',
            'C#',
            'Laravel',
            'Vue.js',
            'React',
            'Angular',
            'Node.js',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'HTML',
            'CSS',
            'Tailwind CSS',
            'Bootstrap',
            'Git',
            'Docker',
            'Kubernetes',
            'AWS',
            'Azure',
            'GCP',
            'Linux',
            'Ubuntu',
            'CentOS',
            'Project Management',
            'Agile',
            'Scrum',
            'Machine Learning',
            'Data Analysis',
            'AI',
            'Cybersecurity',
            'Network Security',
            'UI/UX Design',
            'Figma',
            'Adobe Creative Suite',
            'Digital Marketing',
            'SEO',
            'Social Media Marketing',
            'Microsoft Office',
            'Excel',
            'PowerPoint',
            'Word',
            'English Proficiency',
            'Communication',
            'Leadership',
            'Quality Assurance',
            'Software Testing',
            'Automation Testing'
        ];

        // Merge and remove duplicates
        $allSkills = array_unique(array_merge($skillsFromDB, $commonSkills));
        sort($allSkills);

        return $allSkills;
    }

    /**
     * Clear certification cache
     */
    private function clearCertificationCache()
    {
        $cacheKeys = [
            'certifications_data',
            'dynamic_portfolio_data_v2'
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}
