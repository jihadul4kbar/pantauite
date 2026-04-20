<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $departmentFilter = $request->input('department');
        $statusFilter = $request->input('status');

        $query = User::with(['role', 'department']);

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($roleFilter) {
            $query->where('role_id', $roleFilter);
        }

        // Department filter
        if ($departmentFilter) {
            $query->where('department_id', $departmentFilter);
        }

        // Status filter
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $users = $query->orderBy('name')->paginate($perPage);

        $roles = Role::orderBy('name')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view(
            'users.index',
            compact(
                'users',
                'roles',
                'departments',
                'search',
                'roleFilter',
                'departmentFilter',
                'statusFilter',
            ),
        );
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::orderBy('name')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view('users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,employee_id',
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
            'must_change_password' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['must_change_password'] = $request->has(
            'must_change_password',
        );

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::orderBy('name')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,'.$user->id,
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:50'],
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,employee_id,'.$user->id,
            ],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'telegram_chat_id' => ['nullable', 'string', 'max:100'],
            'must_change_password' => ['sometimes', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Only update password if provided
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['must_change_password'] = $request->has(
            'must_change_password',
        );

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Check if user has assigned tickets
        if (
            $user->createdTickets()->count() > 0 ||
            $user->assignedTickets()->count() > 0
        ) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Cannot delete user with associated tickets. Reassign tickets first.',
                );
        }

        // Check if user is a department manager
        if (Department::where('manager_id', $user->id)->exists()) {
            return redirect()
                ->route('users.index')
                ->with(
                    'error',
                    'Cannot delete user who is a department manager. Reassign departments first.',
                );
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
