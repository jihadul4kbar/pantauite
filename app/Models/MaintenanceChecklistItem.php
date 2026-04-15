<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'item_name',
        'description',
        'instructions',
        'order_index',
        'is_required',
        'requires_photo',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'requires_photo' => 'boolean',
        'order_index' => 'integer',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }
}
