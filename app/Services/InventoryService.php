<?php

namespace App\Services;

use App\Models\InventoryPart;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Stock In operation
     */
    public function stockIn(int $partId, float $quantity, float $unitCost, User $user, string $notes = null, string $supplier = null): InventoryTransaction
    {
        $part = InventoryPart::findOrFail($partId);
        $quantityBefore = $part->quantity_in_stock;
        $quantityAfter = $quantityBefore + $quantity;

        $transaction = InventoryTransaction::create([
            'transaction_number' => $this->generateTransactionNumber(),
            'part_id' => $partId,
            'type' => 'in',
            'quantity' => $quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'unit_cost' => $unitCost,
            'total_cost' => $quantity * $unitCost,
            'user_id' => $user->id,
            'supplier' => $supplier ?? $part->supplier,
            'notes' => $notes,
            'transaction_date' => now(),
        ]);

        $part->update([
            'quantity_in_stock' => $quantityAfter,
            'unit_cost' => $unitCost, // Update latest cost
            'last_restocked' => now(),
        ]);

        Log::info("Stock IN: {$part->name} +{$quantity} (Total: {$quantityAfter})");

        return $transaction;
    }

    /**
     * Stock Out operation
     */
    public function stockOut(int $partId, float $quantity, int $referenceId = null, string $referenceType = null, User $user = null): InventoryTransaction
    {
        $part = InventoryPart::findOrFail($partId);
        $quantityBefore = $part->quantity_in_stock;

        if ($quantityBefore < $quantity) {
            throw new \Exception("Insufficient stock for {$part->name}. Available: {$quantityBefore}, Requested: {$quantity}");
        }

        $quantityAfter = $quantityBefore - $quantity;

        $transaction = InventoryTransaction::create([
            'transaction_number' => $this->generateTransactionNumber(),
            'part_id' => $partId,
            'type' => 'out',
            'quantity' => $quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'unit_cost' => $part->unit_cost,
            'total_cost' => $quantity * $part->unit_cost,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'user_id' => $user?->id,
            'transaction_date' => now(),
        ]);

        $part->update([
            'quantity_in_stock' => $quantityAfter,
        ]);

        Log::info("Stock OUT: {$part->name} -{$quantity} (Total: {$quantityAfter})");

        return $transaction;
    }

    /**
     * Adjust stock (manual correction)
     */
    public function adjustStock(int $partId, float $newQuantity, User $user, string $reason): InventoryTransaction
    {
        $part = InventoryPart::findOrFail($partId);
        $quantityBefore = $part->quantity_in_stock;
        $quantityDiff = $newQuantity - $quantityBefore;
        $type = $quantityDiff >= 0 ? 'in' : 'out';

        $transaction = InventoryTransaction::create([
            'transaction_number' => $this->generateTransactionNumber(),
            'part_id' => $partId,
            'type' => 'adjust',
            'quantity' => abs($quantityDiff),
            'quantity_before' => $quantityBefore,
            'quantity_after' => $newQuantity,
            'unit_cost' => $part->unit_cost,
            'total_cost' => abs($quantityDiff) * $part->unit_cost,
            'user_id' => $user->id,
            'notes' => "Adjustment: {$reason}",
            'transaction_date' => now(),
        ]);

        $part->update([
            'quantity_in_stock' => $newQuantity,
        ]);

        Log::info("Stock ADJ: {$part->name} {$quantityBefore} → {$newQuantity} (Reason: {$reason})");

        return $transaction;
    }

    /**
     * Get low stock parts
     */
    public function getLowStockParts(): \Illuminate\Database\Eloquent\Collection
    {
        return InventoryPart::where('is_active', true)
            ->whereColumn('quantity_in_stock', '<=', 'reorder_point')
            ->orderBy('quantity_in_stock', 'asc')
            ->get();
    }

    /**
     * Get total inventory value
     */
    public function getTotalInventoryValue(): float
    {
        return InventoryPart::where('is_active', true)
            ->get()
            ->sum(function ($part) {
                return $part->quantity_in_stock * $part->unit_cost;
            });
    }

    /**
     * Generate transaction number
     */
    protected function generateTransactionNumber(): string
    {
        $lastTx = InventoryTransaction::orderBy('id', 'desc')->first();
        $nextNumber = $lastTx ? (int) substr($lastTx->transaction_number, 4) + 1 : 1;
        return 'INV-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
