<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryPart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'part_number',
        'name',
        'description',
        'category',
        'vendor_id',
        'supplier',
        'location',
        'quantity_in_stock',
        'reorder_point',
        'unit_cost',
        'unit',
        'manufacturer',
        'model_compatibility',
        'is_active',
        'last_restocked',
    ];

    protected $casts = [
        'quantity_in_stock' => 'decimal:2',
        'reorder_point' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'is_active' => 'boolean',
        'last_restocked' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'part_id');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(MaintenanceRequirement::class, 'part_id');
    }

    /**
     * Check if stock is below reorder point
     */
    public function needsReorder(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_point;
    }

    /**
     * Get stock status text
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->quantity_in_stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->needsReorder()) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    /**
     * Calculate total stock value
     */
    public function getTotalStockValueAttribute(): float
    {
        return $this->quantity_in_stock * $this->unit_cost;
    }
}
