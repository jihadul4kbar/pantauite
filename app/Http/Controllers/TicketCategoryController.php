<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketCategoryController extends Controller
{
    /**
     * Display a listing of ticket categories.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', TicketCategory::class);

        $perPage = $request->input('per_page', 10);

        $categories = TicketCategory::with(['parent', 'children', 'tickets'])
            ->withCount('tickets')
            ->orderBy('name')
            ->paginate($perPage);

        return view('tickets.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new ticket category.
     */
    public function create()
    {
        $this->authorize('create', TicketCategory::class);

        $parentCategories = TicketCategory::active()->root()->orderBy('name')->get();

        return view('tickets.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created ticket category.
     */
    public function store(Request $request)
    {
        $this->authorize('create', TicketCategory::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:ticket_categories,id'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        TicketCategory::create($validated);

        return redirect()
            ->route('tickets.categories.index')
            ->with('success', 'Ticket category created successfully.');
    }

    /**
     * Show the form for editing the specified ticket category.
     */
    public function edit(TicketCategory $category)
    {
        $this->authorize('update', $category);

        $parentCategories = TicketCategory::active()
            ->root()
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('tickets.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified ticket category.
     */
    public function update(Request $request, TicketCategory $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:ticket_categories,id'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:7'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()
            ->route('tickets.categories.index')
            ->with('success', 'Ticket category updated successfully.');
    }

    /**
     * Remove the specified ticket category.
     */
    public function destroy(TicketCategory $category)
    {
        $this->authorize('delete', $category);

        if ($category->tickets()->count() > 0) {
            return redirect()
                ->route('tickets.categories.index')
                ->with('error', 'Cannot delete category with assigned tickets. Reassign tickets first.');
        }

        $category->delete();

        return redirect()
            ->route('tickets.categories.index')
            ->with('success', 'Ticket category deleted successfully.');
    }
}
