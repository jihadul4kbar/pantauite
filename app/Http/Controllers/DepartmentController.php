<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Department::class);

        $perPage = $request->input('per_page', 10);

        $departments = Department::with(['manager', 'parent', 'children', 'users'])
            ->withCount('users', 'tickets')
            ->orderBy('name')
            ->paginate($perPage);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $this->authorize('create', Department::class);

        $parentDepartments = Department::active()->root()->orderBy('name')->get();
        $managers = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('departments.create', compact('parentDepartments', 'managers'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Department::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code'],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'parent_id' => ['nullable', 'exists:departments,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        $this->authorize('update', $department);

        $parentDepartments = Department::active()
            ->root()
            ->where('id', '!=', $department->id)
            ->orderBy('name')
            ->get();

        $managers = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('departments.edit', compact('department', 'parentDepartments', 'managers'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, Department $department)
    {
        $this->authorize('update', $department);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['required', 'string', 'max:50', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'parent_id' => ['nullable', 'exists:departments,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department)
    {
        $this->authorize('delete', $department);

        if ($department->users()->count() > 0) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'Cannot delete department with assigned users. Reassign users first.');
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
