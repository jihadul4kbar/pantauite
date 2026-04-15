<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * User model untuk semua system users
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $role_id
 * @property int|null $department_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_login_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Relationships to always eager load
     */
    protected $with = ['role'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'telegram_chat_id',
        'password',
        'must_change_password',
        'phone',
        'role_id',
        'department_id',
        'employee_id',
        'avatar',
        'status',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createdTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assignee_id');
    }

    public function ticketComments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }

    public function kbArticles(): HasMany
    {
        return $this->hasMany(KbArticle::class, 'author_id');
    }

    public function reviewedArticles(): HasMany
    {
        return $this->hasMany(KbArticle::class, 'reviewed_by');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'assigned_to_user_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'performed_by');
    }

    // ==================== PERMISSION METHODS ====================

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === null) {
            return false;
        }

        // Super admin has all permissions
        if ($this->role->name === 'super_admin') {
            return true;
        }

        $permissions = $this->role->permissions;

        // Wildcard permission check
        if (in_array('*', $permissions, true)) {
            return true;
        }

        return in_array($permission, $permissions, true);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    // ==================== ROLE CHECKS ====================

    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isItManager(): bool
    {
        return $this->hasRole('it_manager');
    }

    public function isItStaff(): bool
    {
        return $this->hasRole('it_staff');
    }

    public function isEndUser(): bool
    {
        return $this->hasRole('end_user');
    }

    // ==================== STATUS HELPERS ====================

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    // ==================== QUERY SCOPES ====================

    /**
     * Scope to get only active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get users with specific permission
     */
    public function scopeWithPermission($query, string $permission)
    {
        return $query->whereHas('role', function ($q) use ($permission) {
            $q->whereJsonContains('permissions', $permission)
                ->orWhereJsonContains('permissions', '*');
        });
    }

    /**
     * Scope to get users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->whereHas('role', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope for multiple roles (OR condition)
     */
    public function scopeWhereRole($query, string ...$roles)
    {
        return $query->whereHas('role', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        });
    }

    /**
     * Scope to get users by department
     */
    public function scopeByDepartment($query, int $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }
}
