<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    seedRoles();

    // Create a super admin user
    $this->admin = User::factory()->create([
        'role_id' => 1, // super_admin
    ]);

    // Create IT Manager
    $this->manager = User::factory()->create([
        'role_id' => 2, // it_manager
    ]);

    // Create IT Staff
    $this->staff = User::factory()->create([
        'role_id' => 3, // it_staff
    ]);

    // Create End User
    $this->user = User::factory()->create([
        'role_id' => 4, // end_user
    ]);

    $this->department = Department::factory()->create();
    $this->category = TicketCategory::factory()->create();
    $this->slaPolicy = SlaPolicy::factory()->create([
        'priority' => 'medium',
        'is_active' => true,
    ]);
});

/**
 * INDEX TESTS
 */
test('admin can view all tickets', function () {
    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)
        ->get(route('tickets.index'));

    $response->assertOk()
        ->assertViewIs('tickets.index');
});

test('manager can view all tickets', function () {
    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($this->manager)
        ->get(route('tickets.index'));

    $response->assertOk();
});

test('staff can view all tickets', function () {
    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($this->staff)
        ->get(route('tickets.index'));

    $response->assertOk();
});

test('end user can view ticket index', function () {
    Ticket::factory()->count(3)->create();

    $response = $this->actingAs($this->user)
        ->get(route('tickets.index'));

    $response->assertOk();
});

/**
 * CREATE TESTS
 */
test('authenticated user can view create ticket form', function () {
    $response = $this->actingAs($this->user)
        ->get(route('tickets.create'));

    $response->assertOk()
        ->assertViewIs('tickets.create');
});

test('authenticated user can create ticket', function () {
    $ticketData = [
        'subject' => 'Test Ticket Subject',
        'description' => 'This is a test ticket description',
        'priority' => 'medium',
        'category_id' => $this->category->id,
        'department_id' => $this->department->id,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('tickets.store'), $ticketData);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tickets', [
        'subject' => 'Test Ticket Subject',
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);
});

test('ticket creation fails without required fields', function () {
    $response = $this->actingAs($this->user)
        ->post(route('tickets.store'), [
            'subject' => '',
            'description' => '',
        ]);

    $response->assertSessionHasErrors(['subject', 'description', 'priority', 'category_id']);
});

/**
 * SHOW TESTS
 */
test('user can view their own ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.show', $ticket));

    $response->assertOk()
        ->assertViewIs('tickets.show')
        ->assertViewHas('ticket', $ticket);
});

test('user cannot view another users ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => User::factory()->create()->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.show', $ticket));

    $response->assertForbidden();
});

test('admin can view any ticket', function () {
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('tickets.show', $ticket));

    $response->assertOk();
});

/**
 * EDIT TESTS
 */
test('ticket creator can edit their ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.edit', $ticket));

    $response->assertOk();
});

test('cannot edit resolved or closed ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'resolved',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.edit', $ticket));

    $response->assertForbidden();
});

/**
 * UPDATE TESTS
 */
test('can update ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'subject' => 'Original Subject',
    ]);

    $response = $this->actingAs($this->user)
        ->put(route('tickets.update', $ticket), [
            'subject' => 'Updated Subject',
            'description' => $ticket->description,
            'priority' => $ticket->priority,
            'category_id' => $ticket->category_id,
        ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'subject' => 'Updated Subject',
    ]);
});

/**
 * DELETE TESTS
 */
test('manager can delete ticket', function () {
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($this->manager)
        ->delete(route('tickets.destroy', $ticket));

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertSoftDeleted('tickets', ['id' => $ticket->id]);
});

test('end user cannot delete ticket', function () {
    $ticket = Ticket::factory()->create();

    $response = $this->actingAs($this->user)
        ->delete(route('tickets.destroy', $ticket));

    $response->assertForbidden();
});

/**
 * ASSIGN TESTS
 */
test('manager can assign ticket to staff', function () {
    $ticket = Ticket::factory()->create([
        'assignee_id' => null,
    ]);

    $response = $this->actingAs($this->manager)
        ->post(route('tickets.assign', $ticket), [
            'assignee_id' => $this->staff->id,
        ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'assignee_id' => $this->staff->id,
    ]);
});

/**
 * STATUS CHANGE TESTS
 */
test('staff can change ticket status', function () {
    $ticket = Ticket::factory()->create([
        'status' => 'open',
        'assignee_id' => $this->staff->id,
    ]);

    $response = $this->actingAs($this->staff)
        ->post(route('tickets.status.change', $ticket), [
            'status' => 'in_progress',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => 'in_progress',
    ]);
});

/**
 * COMMENT TESTS
 */
test('can add comment to ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('tickets.comments.add', $ticket), [
            'comment' => 'This is a test comment',
        ]);

    $response->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('ticket_comments', [
        'ticket_id' => $ticket->id,
        'comment' => 'This is a test comment',
        'user_id' => $this->user->id,
    ]);
});

test('can add internal note as staff', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->actingAs($this->staff)
        ->post(route('tickets.comments.add', $ticket), [
            'comment' => 'Internal note',
            'is_internal' => true,
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('ticket_comments', [
        'ticket_id' => $ticket->id,
        'comment' => 'Internal note',
        'is_internal' => true,
    ]);
});

/**
 * ATTACHMENT TESTS
 */
test('can upload attachment with ticket', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

    $ticketData = [
        'subject' => 'Ticket with attachment',
        'description' => 'Description',
        'priority' => 'medium',
        'category_id' => $this->category->id,
        'attachments' => [$file],
    ];

    $response = $this->actingAs($this->user)
        ->post(route('tickets.store'), $ticketData);

    $response->assertRedirect()
        ->assertSessionHas('success');

    Storage::disk('public')->assertExists('tickets/' . $file->hashName());
});

test('attachment upload respects file size limit', function () {
    Storage::fake('public');

    // Create 6MB file (limit is 5MB)
    $file = UploadedFile::fake()->create('large.pdf', 6144, 'application/pdf');

    $ticketData = [
        'subject' => 'Ticket with large attachment',
        'description' => 'Description',
        'priority' => 'medium',
        'category_id' => $this->category->id,
        'attachments' => [$file],
    ];

    $response = $this->actingAs($this->user)
        ->post(route('tickets.store'), $ticketData);

    $response->assertSessionHasErrors('attachments.0');
});

/**
 * FILTER TESTS
 */
test('can filter tickets by status', function () {
    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'status' => 'open',
    ]);

    Ticket::factory()->count(2)->create([
        'user_id' => $this->user->id,
        'status' => 'closed',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.index', ['status' => 'open']));

    $response->assertOk();
});

test('can filter tickets by priority', function () {
    Ticket::factory()->count(4)->create([
        'user_id' => $this->user->id,
        'priority' => 'high',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.index', ['priority' => 'high']));

    $response->assertOk();
});

test('can filter tickets by category', function () {
    Ticket::factory()->count(3)->create([
        'user_id' => $this->user->id,
        'category_id' => $this->category->id,
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('tickets.index', ['category_id' => $this->category->id]));

    $response->assertOk();
});

/**
 * SLA PAUSE TESTS
 */
test('can pause SLA timer', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'sla_deadline' => now()->addHours(8),
        'paused_at' => null,
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('tickets.sla.pause', $ticket));

    $response->assertRedirect();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'paused_at' => now()->format('Y-m-d H:i:s'),
    ]);
});

test('can resume SLA timer', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->user->id,
        'sla_deadline' => now()->addHours(8),
        'paused_at' => now()->subMinutes(30),
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('tickets.sla.pause', $ticket));

    $response->assertRedirect();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'paused_at' => null,
    ]);
});

/**
 * UNAUTHORIZED ACCESS TESTS
 */
test('guest cannot access tickets', function () {
    $response = $this->get(route('tickets.index'));

    $response->assertRedirect(route('login'));
});

test('user cannot access tickets create if not authenticated', function () {
    $response = $this->get(route('tickets.create'));

    $response->assertRedirect(route('login'));
});
