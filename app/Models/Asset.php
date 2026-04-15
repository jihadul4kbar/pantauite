<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Asset model untuk IT asset inventory
 */
class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_type',
        'name',
        'brand',
        'model',
        'serial_number',
        'part_number',
        'specs',
        'status',
        'condition',
        'assigned_to_user_id',
        'assigned_to_department_id',
        'assigned_at',
        'assigned_notes',
        'location',
        'vendor_id',
        'vendor_name',
        'purchase_order_number',
        'purchase_date',
        'price',
        'currency',
        'warranty_start',
        'warranty_end',
        'warranty_provider',
        'warranty_notes',
        'depreciation_method',
        'useful_life_years',
        'depreciated_value',
        'depreciation_start_date',
        'disposal_date',
        'disposal_reason',
        'disposal_value',
        'notes',
        'images',
    ];

    protected $casts = [
        'specs' => 'array',
        'images' => 'array',
        'assigned_at' => 'datetime',
        'purchase_date' => 'date',
        'warranty_start' => 'date',
        'warranty_end' => 'date',
        'depreciated_value' => 'decimal:2',
        'depreciation_start_date' => 'date',
        'disposal_date' => 'date',
        'disposal_value' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'assigned_to_department_id');
    }

    public function lifecycleLogs(): HasMany
    {
        return $this->hasMany(AssetLifecycleLog::class);
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssetDocument::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'asset_id');
    }

    // ==================== STATUS HELPERS ====================

    public function isProcurement(): bool
    {
        return $this->status === 'procurement';
    }

    public function isInventory(): bool
    {
        return $this->status === 'inventory';
    }

    public function isDeployed(): bool
    {
        return $this->status === 'deployed';
    }

    public function isMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    public function isRetired(): bool
    {
        return $this->status === 'retired';
    }

    public function isDisposed(): bool
    {
        return $this->status === 'disposed';
    }

    // ==================== WARRANTY HELPERS ====================

    public function isUnderWarranty(): bool
    {
        return $this->warranty_end && now()->lte(Carbon::parse($this->warranty_end));
    }

    public function warrantyExpiringInDays(int $days): bool
    {
        if (!$this->warranty_end) {
            return false;
        }

        $expiryDate = Carbon::parse($this->warranty_end);
        return now()->diffInDays($expiryDate, false) <= $days;
    }

    public function warrantyDaysRemaining(): int
    {
        if (!$this->warranty_end) {
            return 0;
        }

        $expiryDate = Carbon::parse($this->warranty_end);
        return max(0, now()->diffInDays($expiryDate, false));
    }

    // ==================== DEPRECIATION HELPER ====================

    /**
     * Calculate current depreciated value using straight-line method
     */
    public function calculateDepreciation(): float
    {
        if (!$this->price || !$this->useful_life_years || !$this->depreciation_start_date) {
            return 0;
        }

        if ($this->depreciation_method === 'none') {
            return $this->price;
        }

        $yearsElapsed = now()->diffInYears(Carbon::parse($this->depreciation_start_date), false);
        
        if ($yearsElapsed >= $this->useful_life_years) {
            return 0;
        }

        $annualDepreciation = $this->price / $this->useful_life_years;
        return max(0, $this->price - ($annualDepreciation * $yearsElapsed));
    }

    // ==================== SCOPES ====================

    public function scopeByType($query, string $type)
    {
        return $query->where('asset_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDeployed($query)
    {
        return $query->where('status', 'deployed');
    }

    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['inventory', 'procurement']);
    }

    public function scopeWarrantyExpiring($query, int $days = 30)
    {
        return $query->whereNotNull('warranty_end')
            ->where('warranty_end', '<=', now()->addDays($days))
            ->where('warranty_end', '>', now());
    }

    public function scopeAssignedToUser($query, int $userId)
    {
        return $query->where('assigned_to_user_id', $userId);
    }

    public function scopeAssignedToDepartment($query, int $departmentId)
    {
        return $query->where('assigned_to_department_id', $departmentId);
    }
}
