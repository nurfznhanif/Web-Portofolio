<?php
// app/Http/Controllers/Admin/CertificationController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CertificationController extends Controller
{
    /**
     * Display a listing of certifications.
     */
    public function index()
    {
        $certifications = Certification::orderBy('issue_date', 'desc')->get();

        return Inertia::render('Admin/Certification/Index', [
            'certifications' => $certifications
        ]);
    }

    /**
     * Show the form for creating a new certification.
     */
    public function create()
    {
        return Inertia::render('Admin/Certification/Create');
    }

    /**
     * Store a newly created certification in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'credential_url' => 'nullable|url|max:500',
        ]);

        // Ensure skills is an array
        if (!isset($validated['skills'])) {
            $validated['skills'] = [];
        }

        Certification::create($validated);

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification created successfully.');
    }

    /**
     * Display the specified certification.
     */
    public function show(Certification $certification)
    {
        return Inertia::render('Admin/Certification/Show', [
            'certification' => $certification
        ]);
    }

    /**
     * Show the form for editing the specified certification.
     */
    public function edit(Certification $certification)
    {
        return Inertia::render('Admin/Certification/Edit', [
            'certification' => $certification
        ]);
    }

    /**
     * Update the specified certification in storage.
     */
    public function update(Request $request, Certification $certification)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'credential_url' => 'nullable|url|max:500',
        ]);

        // Ensure skills is an array
        if (!isset($validated['skills'])) {
            $validated['skills'] = [];
        }

        $certification->update($validated);

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification updated successfully.');
    }

    /**
     * Remove the specified certification from storage.
     */
    public function destroy(Certification $certification)
    {
        $certification->delete();

        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification deleted successfully.');
    }

    /**
     * Bulk delete certifications.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:certifications,id'
        ]);

        Certification::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.certifications.index')
            ->with('success', count($request->ids) . ' certification(s) deleted successfully.');
    }

    /**
     * Search certifications.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $certifications = Certification::where('name', 'LIKE', "%{$query}%")
            ->orWhere('issuer', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('issue_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json($certifications);
    }

    /**
     * Get certification statistics.
     */
    public function getStats()
    {
        $total = Certification::count();
        $valid = Certification::where(function ($query) {
            $query->whereNull('expiry_date')
                ->orWhere('expiry_date', '>', now());
        })->count();

        $expired = Certification::where('expiry_date', '<=', now())->count();

        $expiringSoon = Certification::where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->count();

        $recentlyAdded = Certification::where('created_at', '>=', now()->subDays(30))->count();

        return response()->json([
            'total' => $total,
            'valid' => $valid,
            'expired' => $expired,
            'expiring_soon' => $expiringSoon,
            'recently_added' => $recentlyAdded,
        ]);
    }

    /**
     * Export certifications to CSV.
     */
    public function export()
    {
        $certifications = Certification::orderBy('issue_date', 'desc')->get();

        $filename = 'certifications_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($certifications) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Issuer',
                'Description',
                'Issue Date',
                'Expiry Date',
                'Skills',
                'Credential URL',
                'Status',
                'Created At',
                'Updated At'
            ]);

            // Add data rows
            foreach ($certifications as $certification) {
                $status = 'Valid';
                if ($certification->expiry_date) {
                    if ($certification->expiry_date->isPast()) {
                        $status = 'Expired';
                    } elseif ($certification->expiry_date->lte(now()->addDays(30))) {
                        $status = 'Expiring Soon';
                    }
                } else {
                    $status = 'No Expiry';
                }

                fputcsv($file, [
                    $certification->id,
                    $certification->name,
                    $certification->issuer,
                    $certification->description,
                    $certification->issue_date ? $certification->issue_date->format('Y-m-d') : '',
                    $certification->expiry_date ? $certification->expiry_date->format('Y-m-d') : '',
                    is_array($certification->skills) ? implode(', ', $certification->skills) : '',
                    $certification->credential_url,
                    $status,
                    $certification->created_at->format('Y-m-d H:i:s'),
                    $certification->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import certifications from CSV.
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

                    // Parse skills if present
                    $skills = [];
                    if (!empty($data['Skills'])) {
                        $skills = array_map('trim', explode(',', $data['Skills']));
                    }

                    Certification::create([
                        'name' => $data['Name'] ?? '',
                        'issuer' => $data['Issuer'] ?? '',
                        'description' => $data['Description'] ?? null,
                        'issue_date' => !empty($data['Issue Date']) ? $data['Issue Date'] : null,
                        'expiry_date' => !empty($data['Expiry Date']) ? $data['Expiry Date'] : null,
                        'skills' => $skills,
                        'credential_url' => $data['Credential URL'] ?? null,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} certification(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " and " . (count($errors) - 3) . " more.";
                }
            }

            return redirect()->route('admin.certifications.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.certifications.index')
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
            'issuer' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_url' => 'nullable|url|max:500',
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
     * Toggle certification featured status.
     */
    public function toggleFeatured(Certification $certification)
    {
        // Note: This assumes you have a 'is_featured' column
        // If not, you can remove this method
        if (schema()->hasColumn('certifications', 'is_featured')) {
            $certification->update([
                'is_featured' => !$certification->is_featured
            ]);

            return redirect()->back()
                ->with('success', 'Certification featured status updated.');
        }

        return redirect()->back()
            ->with('error', 'Featured status not supported.');
    }

    /**
     * Get certifications expiring soon.
     */
    public function getExpiringSoon()
    {
        $expiringSoon = Certification::where('expiry_date', '>', now())
            ->where('expiry_date', '<=', now()->addDays(30))
            ->orderBy('expiry_date', 'asc')
            ->get();

        return response()->json($expiringSoon);
    }

    /**
     * Get certification renewal reminders.
     */
    public function getRenewalReminders()
    {
        $reminders = [
            'due_this_week' => Certification::where('expiry_date', '>', now())
                ->where('expiry_date', '<=', now()->addWeek())
                ->count(),
            'due_this_month' => Certification::where('expiry_date', '>', now())
                ->where('expiry_date', '<=', now()->addMonth())
                ->count(),
            'expired' => Certification::where('expiry_date', '<=', now())
                ->count(),
        ];

        return response()->json($reminders);
    }
}
