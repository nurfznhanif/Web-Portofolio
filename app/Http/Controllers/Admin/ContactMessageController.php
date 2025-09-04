<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 15);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query = ContactMessage::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status !== 'all') {
            $query->byStatus($status);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        $messages = $query->paginate($perPage);

        // Get status counts for tabs
        $statusCounts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::unread()->count(),
            'read' => ContactMessage::byStatus('read')->count(),
            'replied' => ContactMessage::byStatus('replied')->count(),
            'archived' => ContactMessage::byStatus('archived')->count(),
        ];

        return Inertia::render('Admin/ContactMessages/Index', [
            'messages' => $messages,
            'statusCounts' => $statusCounts,
            'filters' => [
                'status' => $status,
                'search' => $search,
                'per_page' => $perPage,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder
            ]
        ]);
    }

    /**
     * Display the specified contact message
     */
    public function show(ContactMessage $message)
    {
        // Mark as read if it's new
        if ($message->status === 'new') {
            $message->markAsRead();
        }

        return Inertia::render('Admin/ContactMessages/Show', [
            'message' => $message
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead(ContactMessage $message)
    {
        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read.',
            'status' => $message->status
        ]);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied(ContactMessage $message)
    {
        $message->markAsReplied();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as replied.',
            'status' => $message->status
        ]);
    }

    /**
     * Reply to a contact message
     */
    public function reply(Request $request, ContactMessage $message)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'reply_message' => 'required|string|max:5000',
            'mark_as_replied' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $validated = $validator->validated();

            // Here you would implement the actual email sending
            // For now, we'll simulate it
            $this->sendReplyEmail($message, $validated);

            // Update message status
            if ($request->boolean('mark_as_replied', true)) {
                $message->markAsReplied();
            }

            // Add admin notes
            $adminNotes = $message->admin_notes ?? '';
            $adminNotes .= "\n\n[" . now()->format('Y-m-d H:i:s') . "] Replied: " . $validated['subject'];
            $message->update(['admin_notes' => $adminNotes]);

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully.',
                'status' => $message->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reply: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified contact message
     */
    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact message deleted successfully.'
        ]);
    }

    /**
     * Bulk actions on contact messages
     */
    public function bulkActions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:mark_read,mark_replied,archive,delete',
            'message_ids' => 'required|array|min:1',
            'message_ids.*' => 'exists:contact_messages,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $action = $request->get('action');
            $messageIds = $request->get('message_ids');
            $count = 0;

            switch ($action) {
                case 'mark_read':
                    $count = ContactMessage::whereIn('id', $messageIds)->update([
                        'status' => 'read',
                        'read_at' => now()
                    ]);
                    break;

                case 'mark_replied':
                    $count = ContactMessage::whereIn('id', $messageIds)->update([
                        'status' => 'replied',
                        'replied_at' => now()
                    ]);
                    break;

                case 'archive':
                    $count = ContactMessage::whereIn('id', $messageIds)->update([
                        'status' => 'archived'
                    ]);
                    break;

                case 'delete':
                    $count = ContactMessage::whereIn('id', $messageIds)->delete();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$count} messages.",
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk action failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export contact messages
     */
    public function export(Request $request)
    {
        $status = $request->get('status', 'all');
        $format = $request->get('format', 'json'); // json, csv

        $query = ContactMessage::query();

        if ($status !== 'all') {
            $query->byStatus($status);
        }

        $messages = $query->orderBy('created_at', 'desc')->get();

        if ($format === 'csv') {
            return $this->exportAsCsv($messages);
        }

        // Default: JSON export
        $exportData = [
            'exported_at' => now()->toISOString(),
            'total_messages' => $messages->count(),
            'status_filter' => $status,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'name' => $message->name,
                    'email' => $message->email,
                    'subject' => $message->subject,
                    'message' => $message->message,
                    'status' => $message->status,
                    'ip_address' => $message->ip_address,
                    'country' => $message->country,
                    'created_at' => $message->created_at->toISOString(),
                    'read_at' => $message->read_at?->toISOString(),
                    'replied_at' => $message->replied_at?->toISOString(),
                ];
            })
        ];

        $filename = 'contact_messages_' . date('Y_m_d_H_i_s') . '.json';

        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export messages as CSV
     */
    private function exportAsCsv($messages)
    {
        $filename = 'contact_messages_' . date('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($messages) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Subject',
                'Message',
                'Status',
                'IP Address',
                'Country',
                'Created At',
                'Read At',
                'Replied At'
            ]);

            // CSV data
            foreach ($messages as $message) {
                fputcsv($file, [
                    $message->id,
                    $message->name,
                    $message->email,
                    $message->subject,
                    $message->message,
                    $message->status,
                    $message->ip_address,
                    $message->country,
                    $message->created_at->format('Y-m-d H:i:s'),
                    $message->read_at?->format('Y-m-d H:i:s'),
                    $message->replied_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Send reply email (implement based on your email service)
     */
    private function sendReplyEmail(ContactMessage $message, array $replyData)
    {
        // This is where you would implement actual email sending
        // You can use Laravel's Mail facade or any email service

        try {
            // Example using Laravel Mail (you need to configure mail settings)
            /*
            Mail::send('emails.contact-reply', [
                'originalMessage' => $message,
                'replyContent' => $replyData['reply_message']
            ], function($mail) use ($message, $replyData) {
                $mail->to($message->email, $message->name)
                     ->subject($replyData['subject'])
                     ->from(config('mail.from.address'), config('mail.from.name'));
            });
            */

            // For now, we'll just log it
            \Log::info('Contact reply sent', [
                'to' => $message->email,
                'subject' => $replyData['subject'],
                'original_message_id' => $message->id,
                'reply_content_length' => strlen($replyData['reply_message'])
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send contact reply', [
                'error' => $e->getMessage(),
                'message_id' => $message->id,
                'to' => $message->email
            ]);

            throw $e;
        }
    }
}
