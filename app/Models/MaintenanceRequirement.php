<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'schedule_id',
        'part_id',
        'part_name',
        'part_number',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'vendor_id',
        'supplier',
        'is_consumable',
        'stock_used',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'is_consumable' => 'boolean',
        'stock_used' => 'integer',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTask::class, 'task_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(InventoryPart::class, 'part_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
