<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Models\InventoryPart;
use App\Models\InventoryTransaction;
use App\Models\Vendor;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryPartController extends Controller
{
    public function __construct(
        private InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', InventoryPart::class);

        $query = InventoryPart::with(['vendor'])->orderBy('name');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity_in_stock', '<=', 'reorder_point');
        }

        $parts = $query->paginate(15);

        return view('maintenance.inventory.index', compact('parts'));
    }

    public function create()
    {
        $this->authorize('create', InventoryPart::class);

        $vendors = Vendor::orderBy('name')->get();

        return view('maintenance.inventory.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', InventoryPart::class);

        $validated = $request->validate([
            'part_number' => ['required', 'string', 'unique:inventory_parts,part_number'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'supplier' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'reorder_point' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['required', 'string'],
            'manufacturer' => ['nullable', 'string'],
            'model_compatibility' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $part = InventoryPart::create($validated);

        return redirect()
            ->route('maintenance.inventory.index')
            ->with('success', 'Inventory part created successfully.');
    }

    public function show(InventoryPart $inventory)
    {
        $this->authorize('view', $inventory);

        $inventory->load(['vendor', 'transactions' => function($q) {
            $q->latest()->limit(20);
        }]);

        return view('maintenance.inventory.show', compact('inventory'));
    }

    public function edit(InventoryPart $inventory)
    {
        $this->authorize('update', $inventory);

        $vendors = Vendor::orderBy('name')->get();

        return view('maintenance.inventory.edit', compact('inventory', 'vendors'));
    }

    public function update(Request $request, InventoryPart $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'part_number' => ['required', 'string', 'unique:inventory_parts,part_number,' . $inventory->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'supplier' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'quantity_in_stock' => ['required', 'numeric', 'min:0'],
            'reorder_point' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['required', 'string'],
            'manufacturer' => ['nullable', 'string'],
            'model_compatibility' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $inventory->update($validated);

        return redirect()
            ->route('maintenance.inventory.index')
            ->with('success', 'Inventory part updated successfully.');
    }

    public function destroy(InventoryPart $inventory)
    {
        $this->authorize('delete', $inventory);

        $inventory->delete();

        return redirect()
            ->route('maintenance.inventory.index')
            ->with('success', 'Inventory part deleted successfully.');
    }

    /**
     * Stock adjustment
     */
    public function adjustStock(Request $request, InventoryPart $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'new_quantity' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'string'],
        ]);

        $this->inventoryService->adjustStock(
            $inventory->id,
            $validated['new_quantity'],
            auth()->user(),
            $validated['reason']
        );

        return redirect()
            ->route('maintenance.inventory.show', $inventory)
            ->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Stock In form
     */
    public function stockIn(InventoryPart $inventory)
    {
        $this->authorize('update', $inventory);

        return view('maintenance.inventory.stock-in', compact('inventory'));
    }

    /**
     * Process Stock In
     */
    public function processStockIn(Request $request, InventoryPart $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'supplier' => ['nullable', 'string'],
        ]);

        $unitCost = $validated['unit_cost'] ?? $inventory->unit_cost;

        $this->inventoryService->stockIn(
            $inventory->id,
            $validated['quantity'],
            $unitCost,
            auth()->user(),
            $validated['notes'],
            $validated['supplier']
        );

        return redirect()
            ->route('maintenance.inventory.show', $inventory)
            ->with('success', 'Stock added successfully.');
    }
}
