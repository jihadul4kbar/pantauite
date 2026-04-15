<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * RepairRequest model untuk permintaan perbaikan dari publik (tanpa login)
 *
 * @property int $id
 * @property string $request_number
 * @property string $requester_name
 * @property string $requester_email
 * @property string|null $requester_phone
 * @property string|null $requester_department
 * @property string $subject
 * @property string $description
 * @property string $priority
 * @property int|null $category_id
 * @property string|null $location
 * @property string|null $asset_name
 * @property string|null $asset_serial
 * @property string $status
 * @property string|null $rejection_reason
 * @property int|null $verified_by
 * @property Carbon|null $verified_at
 * @property int|null $ticket_id
 */
class RepairRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_number',
        'requester_name',
        'requester_email',
        'requester_phone',
        'requester_department',
        'subject',
        'description',
        'priority',
        'category_id',
        'location',
        'asset_name',
        'asset_serial',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'ticket_id',
    ];

    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
        'verified_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RepairRequestPhoto::class, 'repair_request_id');
    }

    // ==================== STATUS HELPERS ====================

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted';
    }

    // ==================== ACTIONS ====================

    public function submit(): void
    {
        $this->update([
            'status' => 'submitted',
        ]);
    }

    public function approve(int $userId): void
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    public function reject(string $reason, int $userId): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    public function markAsConverted(int $ticketId): void
    {
        $this->update([
            'status' => 'converted',
            'ticket_id' => $ticketId,
        ]);
    }

    // ==================== SCOPES ====================

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    public function scopePendingVerification($query)
    {
        return $query->whereIn('status', ['submitted', 'approved']);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForEmail($query, string $email)
    {
        return $query->where('requester_email', $email);
    }
}
