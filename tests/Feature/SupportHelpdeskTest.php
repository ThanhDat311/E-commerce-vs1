<?php

/** @var \Tests\TestCase $this */

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    $this->admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);
    $this->customer = User::factory()->create(['role_id' => 3, 'is_active' => true]);
});

it('shows support ticket listing for admin', function () {
    SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Help needed',
        'status' => 'open',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.support.index'))
        ->assertStatus(200)
        ->assertSee('Help needed')
        ->assertSee($this->customer->name);
});

it('can filter tickets by status', function () {
    SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Issue Alpha', // Open
        'status' => 'open',
    ]);
    SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Issue Beta', // Closed
        'status' => 'closed',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.support.index', ['status' => 'closed']))
        ->assertStatus(200)
        ->assertSee('Issue Beta')
        ->assertDontSee('Issue Alpha');
});

it('shows ticket detail chat interface', function () {
    $ticket = SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Detail View Test',
    ]);

    $this->actingAs($this->admin)
        ->get(route('admin.support.show', $ticket))
        ->assertStatus(200)
        ->assertSee('Detail View Test')
        ->assertSee('Ticket Details');
});

it('admin can reply to ticket', function () {
    $ticket = SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Reply Test',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.support.reply', $ticket), [
            'message' => 'Admin response',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ticket_messages', [
        'ticket_id' => $ticket->id,
        'user_id' => $this->admin->id,
        'message' => 'Admin response',
    ]);
});

it('admin can update ticket status and priority', function () {
    $ticket = SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Update Test',
        'status' => 'open',
        'priority' => 'low',
    ]);

    $this->actingAs($this->admin)
        ->patch(route('admin.support.update', $ticket), [
            'status' => 'resolved',
            'priority' => 'high',
        ])
        ->assertRedirect();

    $ticket->refresh();
    expect($ticket->status)->toBe('resolved');
    expect($ticket->priority)->toBe('high');
});

it('validates reply message', function () {
    $ticket = SupportTicket::create([
        'user_id' => $this->customer->id,
        'subject' => 'Validation Test',
    ]);

    $this->actingAs($this->admin)
        ->post(route('admin.support.reply', $ticket), ['message' => ''])
        ->assertSessionHasErrors('message');
});
