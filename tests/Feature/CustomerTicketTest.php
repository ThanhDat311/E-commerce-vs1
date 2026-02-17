<?php

/** @var \Tests\TestCase $this */

use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $this->otherCustomer = User::factory()->create(['role_id' => 3, 'is_active' => true]);
    $this->admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);
});

it('customer can view their own tickets', function () {
    $myTicket = SupportTicket::factory()->create(['user_id' => $this->customer->id]);
    $otherTicket = SupportTicket::factory()->create(['user_id' => $this->otherCustomer->id]);

    $this->actingAs($this->customer)
        ->get(route('tickets.index'))
        ->assertStatus(200)
        ->assertSee($myTicket->subject)
        ->assertDontSee($otherTicket->subject);
});

it('customer can create a new ticket', function () {
    $this->actingAs($this->customer)
        ->get(route('tickets.create'))
        ->assertStatus(200);

    $ticketData = [
        'subject' => 'Test Support Ticket',
        'category' => 'technical',
        'priority' => 'medium',
        'message' => 'This is a test message for the support ticket.',
    ];

    $this->actingAs($this->customer)
        ->post(route('tickets.store'), $ticketData)
        ->assertRedirect(route('tickets.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('support_tickets', [
        'user_id' => $this->customer->id,
        'subject' => 'Test Support Ticket',
        'category' => 'technical',
        'status' => 'open',
    ]);

    $ticket = SupportTicket::where('subject', 'Test Support Ticket')->first();
    $this->assertDatabaseHas('ticket_messages', [
        'ticket_id' => $ticket->id,
        'user_id' => $this->customer->id,
        'message' => 'This is a test message for the support ticket.',
    ]);
});

it('customer can create ticket with order reference', function () {
    $order = Order::factory()->create(['user_id' => $this->customer->id]);

    $ticketData = [
        'subject' => 'Order Issue',
        'category' => 'order_issue',
        'order_id' => $order->id,
        'priority' => 'high',
        'message' => 'I have an issue with my order.',
    ];

    $this->actingAs($this->customer)
        ->post(route('tickets.store'), $ticketData)
        ->assertRedirect(route('tickets.index'));

    $this->assertDatabaseHas('support_tickets', [
        'user_id' => $this->customer->id,
        'order_id' => $order->id,
        'category' => 'order_issue',
    ]);
});

it('validates required fields when creating ticket', function () {
    $this->actingAs($this->customer)
        ->post(route('tickets.store'), [])
        ->assertSessionHasErrors(['subject', 'category', 'message']);
});

it('validates category field', function () {
    $this->actingAs($this->customer)
        ->post(route('tickets.store'), [
            'subject' => 'Test',
            'category' => 'invalid_category',
            'message' => 'Test message',
        ])
        ->assertSessionHasErrors('category');
});

it('customer can view their ticket details', function () {
    $ticket = SupportTicket::factory()->create(['user_id' => $this->customer->id]);

    TicketMessage::create([
        'ticket_id' => $ticket->id,
        'user_id' => $this->customer->id,
        'message' => 'Initial message',
    ]);

    $this->actingAs($this->customer)
        ->get(route('tickets.show', $ticket))
        ->assertStatus(200)
        ->assertSee($ticket->subject)
        ->assertSee('Initial message');
});

it('customer cannot view other customers tickets', function () {
    $otherTicket = SupportTicket::factory()->create(['user_id' => $this->otherCustomer->id]);

    $this->actingAs($this->customer)
        ->get(route('tickets.show', $otherTicket))
        ->assertStatus(403);
});

it('customer can reply to their ticket', function () {
    $ticket = SupportTicket::factory()->create(['user_id' => $this->customer->id]);

    $this->actingAs($this->customer)
        ->post(route('tickets.messages.store', $ticket), [
            'message' => 'This is my reply',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ticket_messages', [
        'ticket_id' => $ticket->id,
        'user_id' => $this->customer->id,
        'message' => 'This is my reply',
    ]);
});

it('customer can attach file to ticket message', function () {
    Storage::fake('public');
    $ticket = SupportTicket::factory()->create(['user_id' => $this->customer->id]);
    $file = UploadedFile::fake()->create('document.pdf', 1024);

    $this->actingAs($this->customer)
        ->post(route('tickets.messages.store', $ticket), [
            'message' => 'Message with attachment',
            'attachment' => $file,
        ])
        ->assertRedirect();

    $message = TicketMessage::where('ticket_id', $ticket->id)
        ->where('message', 'Message with attachment')
        ->first();

    expect($message->attachment)->not->toBeNull();
    Storage::disk('public')->assertExists($message->attachment);
});

it('validates attachment size limit', function () {
    Storage::fake('public');
    $ticket = SupportTicket::factory()->create(['user_id' => $this->customer->id]);
    $file = UploadedFile::fake()->create('large-file.pdf', 3000); // 3MB

    $this->actingAs($this->customer)
        ->post(route('tickets.messages.store', $ticket), [
            'message' => 'Message with large attachment',
            'attachment' => $file,
        ])
        ->assertSessionHasErrors('attachment');
});

it('customer cannot reply to other customers tickets', function () {
    $otherTicket = SupportTicket::factory()->create(['user_id' => $this->otherCustomer->id]);

    $this->actingAs($this->customer)
        ->post(route('tickets.messages.store', $otherTicket), [
            'message' => 'Unauthorized reply',
        ])
        ->assertStatus(403);
});

it('reopens resolved ticket when customer replies', function () {
    $ticket = SupportTicket::factory()->resolved()->create(['user_id' => $this->customer->id]);

    $this->actingAs($this->customer)
        ->post(route('tickets.messages.store', $ticket), [
            'message' => 'I still need help',
        ])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->status)->toBe('open');
});

it('can filter tickets by status', function () {
    $openTicket = SupportTicket::factory()->open()->create(['user_id' => $this->customer->id]);
    $resolvedTicket = SupportTicket::factory()->resolved()->create(['user_id' => $this->customer->id]);

    $this->actingAs($this->customer)
        ->get(route('tickets.index', ['status' => 'open']))
        ->assertStatus(200)
        ->assertSee($openTicket->subject)
        ->assertDontSee($resolvedTicket->subject);
});

it('can filter tickets by category', function () {
    $technicalTicket = SupportTicket::factory()->create([
        'user_id' => $this->customer->id,
        'category' => 'technical',
    ]);
    $billingTicket = SupportTicket::factory()->create([
        'user_id' => $this->customer->id,
        'category' => 'billing',
    ]);

    $this->actingAs($this->customer)
        ->get(route('tickets.index', ['category' => 'technical']))
        ->assertStatus(200)
        ->assertSee($technicalTicket->subject)
        ->assertDontSee($billingTicket->subject);
});

it('can search tickets by subject', function () {
    $ticket1 = SupportTicket::factory()->create([
        'user_id' => $this->customer->id,
        'subject' => 'Unique Search Term',
    ]);
    $ticket2 = SupportTicket::factory()->create([
        'user_id' => $this->customer->id,
        'subject' => 'Different Subject',
    ]);

    $this->actingAs($this->customer)
        ->get(route('tickets.index', ['search' => 'Unique']))
        ->assertStatus(200)
        ->assertSee($ticket1->subject)
        ->assertDontSee($ticket2->subject);
});

it('displays ticket statistics correctly', function () {
    SupportTicket::factory()->open()->create(['user_id' => $this->customer->id]);
    SupportTicket::factory()->open()->create(['user_id' => $this->customer->id]);
    SupportTicket::factory()->inProgress()->create(['user_id' => $this->customer->id]);
    SupportTicket::factory()->resolved()->create(['user_id' => $this->customer->id]);

    $response = $this->actingAs($this->customer)
        ->get(route('tickets.index'))
        ->assertStatus(200);

    $stats = $response->viewData('stats');
    expect($stats['open'])->toBe(2);
    expect($stats['in_progress'])->toBe(1);
    expect($stats['resolved'])->toBe(1);
    expect($stats['total'])->toBe(4);
});

it('requires authentication to access tickets', function () {
    $this->get(route('tickets.index'))
        ->assertRedirect(route('login'));

    $this->get(route('tickets.create'))
        ->assertRedirect(route('login'));
});
