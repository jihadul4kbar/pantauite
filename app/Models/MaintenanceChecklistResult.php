<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceChecklistResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'item_name',
        'description',
        'status',
        'notes',
        'photo_path',
        'checked_at',
        'checked_by_user_id',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTask::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by_user_id');
    }
}
