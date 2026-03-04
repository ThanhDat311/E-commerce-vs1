<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    /**
     * Display a listing of tickets available to staff.
     */
    public function index(Request $request)
    {
        // Stats for staff
        $stats = [
            // All open/pending tickets in system that could be assigned or are assigned to this staff
            'open_total' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'assigned_to_me' => SupportTicket::where('assigned_to', Auth::id())->whereIn('status', ['open', 'in_progress'])->count(),
            'resolved_by_me' => SupportTicket::where('assigned_to', Auth::id())->where('status', 'resolved')->count(),
        ];

        // Query setup
        $query = SupportTicket::with(['user', 'assignedTo'])
            ->withCount('messages');

        // Staff specific logic: Optionally show all, or default to their own + unassigned
        $filter = $request->input('filter', 'my_tickets'); // Default focus

        if ($filter === 'my_tickets') {
            $query->where('assigned_to', Auth::id());
        } elseif ($filter === 'unassigned') {
            $query->whereNull('assigned_to');
        } // 'all_tickets' displays everything

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        $tickets = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('pages.staff.support.index', compact('tickets', 'stats', 'filter'));
    }

    /**
     * Show ticket interface.
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['user', 'assignedTo', 'order', 'messages.user']);

        // Mark as seen/in_progress if opened by assigned staff and was open
        if ($ticket->status === 'open' && Auth::id() === $ticket->assigned_to) {
            $ticket->update(['status' => 'in_progress']);
        }

        return view('pages.staff.support.show', compact('ticket'));
    }

    /**
     * Reply to ticket.
     */
    public function storeMessage(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
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

            // If replying, mark as in progress
            if (in_array($ticket->status, ['open', 'resolved'])) {
                $ticket->update(['status' => 'in_progress']);
            }

            // Auto-assign to me if unassigned and I reply
            if (is_null($ticket->assigned_to)) {
                $ticket->update(['assigned_to' => Auth::id()]);
            }

            $ticket->touch();
        });

        return back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Update ticket properties (status, assign to me).
     */
    public function update(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'action' => 'sometimes|in:claim', // staff can claim an unassigned ticket
        ]);

        if (isset($validated['action']) && $validated['action'] === 'claim') {
            $ticket->update([
                'assigned_to' => Auth::id(),
                'status' => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
            ]);

            return back()->with('success', 'Ticket claimed successfully.');
        }

        if (isset($validated['status'])) {
            $ticket->update(['status' => $validated['status']]);
        }

        return back()->with('success', 'Ticket updated successfully.');
    }
}
