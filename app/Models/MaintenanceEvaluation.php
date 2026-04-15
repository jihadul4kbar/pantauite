<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'evaluated_by_user_id',
        'evaluation_date',
        'overall_rating',
        'asset_condition_before',
        'asset_condition_after',
        'issues_found',
        'recommendations',
        'follow_up_required',
        'follow_up_notes',
        'next_maintenance_recommendation',
        'asset_health_score',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'overall_rating' => 'integer',
        'follow_up_required' => 'boolean',
        'asset_health_score' => 'decimal:2',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTask::class);
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by_user_id');
    }
}
