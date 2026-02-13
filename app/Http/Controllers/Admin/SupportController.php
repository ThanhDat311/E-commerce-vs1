<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        // Stats
        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'pending' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            // Avg processing time for resolved tickets (hours)
            // Avg processing time for resolved tickets (hours)
            'avg_response' => SupportTicket::where('status', 'resolved')
                ->get()
                ->avg(fn($ticket) => $ticket->updated_at->floatDiffInHours($ticket->created_at)) ?? 0,
        ];

        // Query
        $query = SupportTicket::with(['user', 'assignedTo'])
            ->withCount('messages');

        if ($search = $request->input('search')) {
            $query->where('subject', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        if ($assignee = $request->input('assignee')) {
            if ($assignee === 'me') {
                $query->where('assigned_to', Auth::id());
            } elseif ($assignee === 'unassigned') {
                $query->whereNull('assigned_to');
            }
        }

        $tickets = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('pages.admin.support.index', compact('tickets', 'stats'));
    }

    /**
     * Show the chat interface for a simplified ticket.
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'assignedTo', 'order', 'messages.user']);

        // Mark as seen/in_progress if opened by assigned staff and was open
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        if ($ticket->status === 'open' && (Auth::id() === $ticket->assigned_to || $currentUser->isAdmin())) {
            $ticket->update(['status' => 'in_progress']);
        }

        $staffMembers = User::whereIn('role_id', [1, 2])->where('is_active', true)->get(); // Admin & Staff

        return view('pages.admin.support.show', compact('ticket', 'staffMembers'));
    }

    /**
     * Reply to a ticket.
     */
    public function storeMessage(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048', // 2MB
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support-attachments', 'public');
        }

        DB::transaction(function () use ($request, $ticket, $attachmentPath) {
            $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $request->message,
                'attachment' => $attachmentPath,
            ]);

            // Auto-update status if ticket was 'resolved' or 'closed' to 'in_progress'
            // OR if replying, usually it means we are working on it.
            if (in_array($ticket->status, ['open', 'resolved'])) {
                $ticket->update(['status' => 'in_progress']);
            }

            $ticket->touch(); // Update updated_at
        });

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Update ticket properties (status, priority, assignee).
     */
    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'assigned_to' => 'sometimes|exists:users,id|nullable',
        ]);

        $ticket->update($validated);

        return back()->with('success', 'Ticket updated successfully.');
    }
}
