<?php

use App\Models\Department;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    seedRoles();
    
    // Create users with different roles
    $this->superAdmin = User::factory()->create([
        'role_id' => 1, // super_admin
    ]);

    $this->itManager = User::factory()->create([
        'role_id' => 2, // it_manager
    ]);

    $this->itStaff = User::factory()->create([
        'role_id' => 3, // it_staff
    ]);

    $this->endUser = User::factory()->create([
        'role_id' => 4, // end_user
    ]);

    $this->department = Department::factory()->create();
    $this->category = TicketCategory::factory()->create();
});

/**
 * VIEW ANY TESTS
 */
test('super admin can view any tickets', function () {
    expect($this->superAdmin->can('viewAny', Ticket::class))->toBeTrue();
});

test('it manager can view any tickets', function () {
    expect($this->itManager->can('viewAny', Ticket::class))->toBeTrue();
});

test('it staff can view any tickets', function () {
    expect($this->itStaff->can('viewAny', Ticket::class))->toBeTrue();
});

test('end user can view tickets list', function () {
    expect($this->endUser->can('viewAny', Ticket::class))->toBeTrue();
});

/**
 * VIEW TESTS
 */
test('super admin can view any ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->superAdmin->can('view', $ticket))->toBeTrue();
});

test('it manager can view any ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itManager->can('view', $ticket))->toBeTrue();
});

test('user can view their own ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->endUser->can('view', $ticket))->toBeTrue();
});

test('user cannot view another users ticket', function () {
    $otherUser = User::factory()->create([
        'role_id' => 4, // end_user
    ]);

    $ticket = Ticket::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    expect($this->endUser->can('view', $ticket))->toBeFalse();
});

test('it staff can view assigned ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => $this->itStaff->id,
    ]);

    expect($this->itStaff->can('view', $ticket))->toBeTrue();
});

/**
 * CREATE TESTS
 */
test('super admin can create tickets', function () {
    expect($this->superAdmin->can('create', Ticket::class))->toBeTrue();
});

test('it manager can create tickets', function () {
    expect($this->itManager->can('create', Ticket::class))->toBeTrue();
});

test('it staff can create tickets', function () {
    expect($this->itStaff->can('create', Ticket::class))->toBeTrue();
});

test('end user can create tickets', function () {
    expect($this->endUser->can('create', Ticket::class))->toBeTrue();
});

/**
 * UPDATE TESTS
 */
test('ticket creator can update their open ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'status' => 'open',
    ]);

    expect($this->endUser->can('update', $ticket))->toBeTrue();
});

test('ticket creator cannot update resolved ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'status' => 'resolved',
    ]);

    expect($this->endUser->can('update', $ticket))->toBeFalse();
});

test('ticket creator cannot update closed ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'status' => 'closed',
    ]);

    expect($this->endUser->can('update', $ticket))->toBeFalse();
});

test('it manager can update any ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'status' => 'open',
    ]);

    expect($this->itManager->can('update', $ticket))->toBeTrue();
});

test('it staff cannot update unassigned ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => null,
    ]);

    expect($this->itStaff->can('update', $ticket))->toBeFalse();
});

test('it staff can update assigned ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => $this->itStaff->id,
    ]);

    expect($this->itStaff->can('update', $ticket))->toBeTrue();
});

/**
 * ASSIGN TESTS
 */
test('super admin can assign tickets', function () {
    $ticket = Ticket::factory()->create([
        'assignee_id' => null,
    ]);

    expect($this->superAdmin->can('assign', $ticket))->toBeTrue();
});

test('it manager can assign tickets', function () {
    $ticket = Ticket::factory()->create([
        'assignee_id' => null,
    ]);

    expect($this->itManager->can('assign', $ticket))->toBeTrue();
});

test('it staff cannot assign tickets', function () {
    $ticket = Ticket::factory()->create([
        'assignee_id' => null,
    ]);

    expect($this->itStaff->can('assign', $ticket))->toBeFalse();
});

test('end user cannot assign tickets', function () {
    $ticket = Ticket::factory()->create([
        'assignee_id' => null,
    ]);

    expect($this->endUser->can('assign', $ticket))->toBeFalse();
});

/**
 * CHANGE STATUS TESTS
 */
test('it manager can change ticket status', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itManager->can('changeStatus', $ticket))->toBeTrue();
});

test('it staff can change status of assigned ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => $this->itStaff->id,
    ]);

    expect($this->itStaff->can('changeStatus', $ticket))->toBeTrue();
});

test('it staff cannot change status of unassigned ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => null,
    ]);

    expect($this->itStaff->can('changeStatus', $ticket))->toBeFalse();
});

/**
 * CLOSE TESTS
 */
test('it manager can close tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itManager->can('close', $ticket))->toBeTrue();
});

test('assignee can close their ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => $this->itStaff->id,
    ]);

    expect($this->itStaff->can('close', $ticket))->toBeTrue();
});

test('end user cannot close tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->endUser->can('close', $ticket))->toBeFalse();
});

/**
 * DELETE TESTS
 */
test('super admin can delete tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->superAdmin->can('delete', $ticket))->toBeTrue();
});

test('it manager can delete tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itManager->can('delete', $ticket))->toBeTrue();
});

test('it staff cannot delete tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itStaff->can('delete', $ticket))->toBeFalse();
});

test('end user cannot delete tickets', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->endUser->can('delete', $ticket))->toBeFalse();
});

/**
 * COMMENT TESTS
 */
test('ticket creator can comment on their ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->endUser->can('comment', $ticket))->toBeTrue();
});

test('it staff can comment on any ticket', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itStaff->can('comment', $ticket))->toBeTrue();
});

test('it staff can add internal notes', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->itStaff->can('addInternalNote', $ticket))->toBeTrue();
});

test('end user cannot add internal notes', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->endUser->can('addInternalNote', $ticket))->toBeFalse();
});

/**
 * VIEW REPORTS TESTS
 */
test('super admin can view reports', function () {
    expect($this->superAdmin->can('viewReports', Ticket::class))->toBeTrue();
});

test('it manager can view reports', function () {
    expect($this->itManager->can('viewReports', Ticket::class))->toBeTrue();
});

test('it staff can view reports', function () {
    expect($this->itStaff->can('viewReports', Ticket::class))->toBeTrue();
});

test('end user cannot view reports', function () {
    expect($this->endUser->can('viewReports', Ticket::class))->toBeFalse();
});

/**
 * PERMISSION INHERITANCE TESTS
 */
test('super admin has all permissions', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
    ]);

    expect($this->superAdmin->can('viewAny', Ticket::class))->toBeTrue()
        ->and($this->superAdmin->can('view', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('create', Ticket::class))->toBeTrue()
        ->and($this->superAdmin->can('update', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('assign', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('changeStatus', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('delete', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('comment', $ticket))->toBeTrue()
        ->and($this->superAdmin->can('viewReports', Ticket::class))->toBeTrue();
});

test('role based access control works correctly', function () {
    $ticket = Ticket::factory()->create([
        'user_id' => $this->endUser->id,
        'assignee_id' => $this->itStaff->id,
    ]);

    // IT Manager has broader access than IT Staff
    expect($this->itManager->can('delete', $ticket))->toBeTrue()
        ->and($this->itStaff->can('delete', $ticket))->toBeFalse();

    // IT Staff has access to assigned tickets
    expect($this->itStaff->can('view', $ticket))->toBeTrue()
        ->and($this->itStaff->can('update', $ticket))->toBeTrue();

    // End user only has access to their own tickets
    $otherTicket = Ticket::factory()->create([
        'user_id' => User::factory()->create(['role_id' => 4])->id,
    ]);

    expect($this->endUser->can('view', $ticket))->toBeFalse()
        ->and($this->endUser->can('view', $otherTicket))->toBeFalse();
});
