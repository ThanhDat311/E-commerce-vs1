<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Ticket\StoreTicketMessageRequest;
use App\Http\Requests\Customer\Ticket\StoreTicketRequest;
use App\Models\Order;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the user's tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['assignedTo', 'order'])
            ->withCount('messages')
            ->forCustomer(Auth::id());

        // Search
        if ($search = $request->input('search')) {
            $query->where('subject', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        $tickets = $query->latest('updated_at')->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'open' => SupportTicket::forCustomer(Auth::id())->where('status', 'open')->count(),
            'in_progress' => SupportTicket::forCustomer(Auth::id())->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::forCustomer(Auth::id())->where('status', 'resolved')->count(),
            'total' => SupportTicket::forCustomer(Auth::id())->count(),
        ];

        return view('pages.customer.tickets.index', compact('tickets', 'stats'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        // Get user's orders for optional linking
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->limit(20)
            ->get();

        return view('pages.customer.tickets.create', compact('orders'));
    }

    /**
     * Store a newly created ticket.
     */
    public function store(StoreTicketRequest $request)
    {
        DB::transaction(function () use ($request) {
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'subject' => $request->subject,
                'category' => $request->category,
                'order_id' => $request->order_id,
                'priority' => $request->priority ?? 'medium',
                'status' => 'open',
            ]);

            // Create initial message
            $ticket->messages()->create([
                'user_id' => Auth::id(),
                'message' => $request->message,
            ]);
        });

        return redirect()->route('tickets.index')
            ->with('success', 'Support ticket created successfully. Our team will respond soon.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(SupportTicket $ticket)
    {
        // Authorization: ensure user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['user', 'assignedTo', 'order', 'messages.user']);

        return view('pages.customer.tickets.show', compact('ticket'));
    }

    /**
     * Store a new message for the ticket.
     */
    public function storeMessage(StoreTicketMessageRequest $request, SupportTicket $ticket)
    {
        // Authorization: ensure user owns this ticket
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this ticket.');
        }

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

            // If ticket was resolved/closed, reopen it when customer replies
            if (in_array($ticket->status, ['resolved', 'closed'])) {
                $ticket->update(['status' => 'open']);
            }

            $ticket->touch(); // Update updated_at
        });

        return back()->with('success', 'Message sent successfully.');
    }
}
