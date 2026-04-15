<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role model untuk role-based access control
 * 
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property array $permissions
 * @property bool $is_system_role
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_system_role',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system_role' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // ==================== PERMISSION HELPERS ====================

    public function hasPermission(string $permission): bool
    {
        return in_array('*', $this->permissions, true) 
            || in_array($permission, $this->permissions, true);
    }

    public function givePermission(string $permission): void
    {
        if (!$this->hasPermission($permission)) {
            $permissions = $this->permissions;
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
    }

    public function revokePermission(string $permission): void
    {
        if ($this->hasPermission($permission)) {
            $this->permissions = array_filter(
                $this->permissions,
                fn ($p) => $p !== $permission && $p !== '*'
            );
            $this->save();
        }
    }

    // ==================== STATIC HELPERS ====================

    public static function superAdmin(): ?self
    {
        return self::where('name', 'super_admin')->first();
    }

    public static function itManager(): ?self
    {
        return self::where('name', 'it_manager')->first();
    }

    public static function itStaff(): ?self
    {
        return self::where('name', 'it_staff')->first();
    }

    public static function endUser(): ?self
    {
        return self::where('name', 'end_user')->first();
    }
}
