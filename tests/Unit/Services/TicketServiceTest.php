<?php

use App\Models\Role;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    seedRoles();
    
    $this->ticketService = app(TicketService::class);
    
    // Create required dependencies
    $this->user = User::factory()->create();
    $this->department = Department::factory()->create();
    $this->category = TicketCategory::factory()->create();
    $this->slaPolicy = SlaPolicy::factory()->create([
        'priority' => 'medium',
        'response_time_minutes' => 240,
        'resolution_time_minutes' => 1440,
        'use_business_hours' => true,
        'is_active' => true,
    ]);
});

test('can create ticket with all required fields', function () {
    $ticket = $this->ticketService->createTicket(
        $this->user,
        [
            'subject' => 'Test Ticket Subject',
            'description' => 'Test ticket description',
            'priority' => 'medium',
            'category_id' => $this->category->id,
            'department_id' => $this->department->id,
        ]
    );

    expect($ticket)->toBeInstanceOf(Ticket::class)
        ->and($ticket->ticket_number)->toMatch('/TKT-\d{4}-\d{4}/')
        ->and($ticket->subject)->toBe('Test Ticket Subject')
        ->and($ticket->status)->toBe('open')
        ->and($ticket->user_id)->toBe($this->user->id)
        ->and($ticket->sla_deadline)->not()->toBeNull();
});

test('ticket auto-generates ticket number', function () {
    $ticket1 = $this->ticketService->createTicket(
        $this->user,
        [
            'subject' => 'First Ticket',
            'description' => 'Description',
            'priority' => 'low',
            'category_id' => $this->category->id,
        ]
    );

    $ticket2 = $this->ticketService->createTicket(
        $this->user,
        [
            'subject' => 'Second Ticket',
            'description' => 'Description',
            'priority' => 'low',
            'category_id' => $this->category->id,
        ]
    );

    $number1 = (int) substr($ticket1->ticket_number, -4);
    $number2 = (int) substr($ticket2->ticket_number, -4);

    expect($number2)->toBe($number1 + 1);
});

test('ticket initializes SLA policy based on priority', function () {
    $ticket = $this->ticketService->createTicket(
        $this->user,
        [
            'subject' => 'SLA Test Ticket',
            'description' => 'Testing SLA',
            'priority' => 'medium',
            'category_id' => $this->category->id,
        ]
    );

    expect($ticket->sla_policy_id)->toBe($this->slaPolicy->id)
        ->and($ticket->sla_deadline)->not()->toBeNull()
        ->and($ticket->sla_breached)->toBeFalse();
});

test('can update ticket status', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);

    $updated = $this->ticketService->updateStatus(
        $ticket,
        'in_progress',
        $this->user,
        'Starting work on this ticket'
    );

    expect($updated->status)->toBe('in_progress')
        ->and($updated->first_response_at)->not()->toBeNull();
});

test('can assign ticket to user', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'assignee_id' => null,
    ]);

    $assignee = User::factory()->create();

    $updated = $this->ticketService->assignTicket(
        $ticket,
        $assignee->id,
        $this->user
    );

    expect($updated->assignee_id)->toBe($assignee->id);
});

test('can add comment to ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $comment = $this->ticketService->addComment(
        $ticket,
        $this->user,
        'This is a test comment',
        false,
        false
    );

    expect($comment)->not()->toBeNull()
        ->and($comment->comment)->toBe('This is a test comment')
        ->and($comment->user_id)->toBe($this->user->id)
        ->and($comment->ticket_id)->toBe($ticket->id);
});

test('can add internal note to ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $comment = $this->ticketService->addComment(
        $ticket,
        $this->user,
        'Internal note content',
        true,
        false
    );

    expect($comment->is_internal)->toBeTrue();
});

test('can resolve ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'in_progress',
    ]);

    $updated = $this->ticketService->resolveTicket(
        $ticket,
        $this->user,
        'Issue has been resolved'
    );

    expect($updated->status)->toBe('resolved')
        ->and($updated->resolved_at)->not()->toBeNull();
});

test('can close ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'resolved',
        'resolved_at' => now(),
    ]);

    $updated = $this->ticketService->closeTicket(
        $ticket,
        $this->user
    );

    expect($updated->status)->toBe('closed')
        ->and($updated->closed_at)->not()->toBeNull();
});

test('can reopen ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'closed',
        'closed_at' => now(),
    ]);

    $updated = $this->ticketService->reopenTicket(
        $ticket,
        $this->user,
        'Issue persists, reopening'
    );

    expect($updated->status)->toBe('reopened');
});

test('can delete ticket (soft delete)', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $result = $this->ticketService->deleteTicket($ticket, $this->user);

    expect($result)->toBeTrue()
        ->and(Ticket::withTrashed()->find($ticket->id)->deleted_at)->not()->toBeNull();
});

test('can get paginated tickets with filters', function () {
    // Create multiple tickets
    Ticket::factory()->count(5)->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $tickets = $this->ticketService->getPaginatedTickets(
        $this->user,
        ['per_page' => 10]
    );

    expect($tickets)->toHaveCount(5);
});

test('can get tickets by status', function () {
    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);

    Ticket::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'status' => 'closed',
    ]);

    $openTickets = $this->ticketService->getPaginatedTickets(
        $this->user,
        ['status' => 'open', 'per_page' => 10]
    );

    expect($openTickets)->toHaveCount(3);
});

test('can get tickets by priority', function () {
    Ticket::factory()->count(4)->create([
        'user_id' => $this->user->id,
        'priority' => 'high',
    ]);

    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'priority' => 'low',
    ]);

    $highPriority = $this->ticketService->getPaginatedTickets(
        $this->user,
        ['priority' => 'high', 'per_page' => 10]
    );

    expect($highPriority)->toHaveCount(4);
});

test('can get tickets by category', function () {
    $category1 = TicketCategory::factory()->create();
    $category2 = TicketCategory::factory()->create();

    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'category_id' => $category1->id,
    ]);

    Ticket::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'category_id' => $category2->id,
    ]);

    $categoryTickets = $this->ticketService->getPaginatedTickets(
        $this->user,
        ['category_id' => $category1->id, 'per_page' => 10]
    );

    expect($categoryTickets)->toHaveCount(3);
});

test('end user can only view own tickets', function () {
    $otherUser = User::factory()->create();

    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
    ]);

    Ticket::factory()->count(5)->create([
        'user_id' => $otherUser->id,
    ]);

    $tickets = $this->ticketService->getPaginatedTickets(
        $this->user,
        ['per_page' => 20]
    );

    expect($tickets)->toHaveCount(3);
});

test('can get ticket statistics', function () {
    Ticket::factory()->count(5)->create([
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);

    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'status' => 'in_progress',
    ]);

    Ticket::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'status' => 'resolved',
    ]);

    $stats = $this->ticketService->getStatistics($this->user);

    expect($stats)->toHaveKey('total', 10)
        ->and($stats['by_status'])->toHaveKey('open', 5)
        ->and($stats['by_status'])->toHaveKey('in_progress', 3)
        ->and($stats['by_status'])->toHaveKey('resolved', 2);
});

test('can search tickets by subject', function () {
    Ticket::factory()->create([
        'user_id' => $this->user->id,
        'subject' => 'Login issue with email',
    ]);

    Ticket::factory()->create([
        'user_id' => $this->user->id,
        'subject' => 'Printer not working',
    ]);

    $results = $this->ticketService->searchTickets(
        $this->user,
        'Login issue'
    );

    expect($results)->toHaveCount(1)
        ->and($results->first()->subject)->toContain('Login issue');
});

test('ticket validation fails without required fields', function () {
    $this->ticketService->createTicket(
        $this->user,
        [
            'subject' => '',
            'description' => '',
            'priority' => '',
            'category_id' => null,
        ]
    );
})->throws(\Illuminate\Validation\ValidationException::class);
