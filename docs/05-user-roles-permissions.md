# User Roles & Permissions
## PantauITE - IT Service Management Platform

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-06  
**Last Updated:** 2026-04-06

---

# PART 1: ROLE OVERVIEW

## Role Hierarchy

```
┌─────────────────────────────────────────┐
│          SUPER ADMIN                    │
│  (Full system access, user management)  │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│            IT MANAGER                   │
│  (Manage operations, view reports,      │
│   configure SLA, manage team)           │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│            IT STAFF                     │
│  (Handle tickets, manage assets,        │
│   create KB articles)                   │
└────────────────┬────────────────────────┘
                 │
┌────────────────▼────────────────────────┐
│            END USER                     │
│  (Submit tickets, track status,         │
│   browse knowledge base)                │
└─────────────────────────────────────────┘
```

---

# PART 2: ROLES DEFINITIONS

## 1. SUPER ADMIN

**Description:** Full system access dengan ability untuk manage users, roles, dan system configuration.

**Count Expected:** 1-3 users  
**Department:** IT (typically)

### Capabilities
- ✅ All permissions across all modules
- ✅ Manage users (create, update, delete, activate/deactivate)
- ✅ Manage roles dan permissions
- ✅ System configuration (SLA policies, departments, categories)
- ✅ Access audit logs
- ✅ Data export dan backup
- ✅ System maintenance mode

### Cannot Do
- ❌ Nothing (full access)

### Typical Users
- System Administrator
- IT Director
- CTO

---

## 2. IT MANAGER

**Description:** Manage IT operations, monitor team performance, configure SLA policies, dan generate reports.

**Count Expected:** 2-5 users  
**Department:** IT (specifically)

### Capabilities
- ✅ View all tickets (all departments)
- ✅ Assign/reassign tickets ke IT staff
- ✅ Manage SLA policies (termasuk escalation)
- ✅ Manage departments
- ✅ Create/edit/delete KB articles
- ✅ Manage assets (full CRUD)
- ✅ View dan generate all reports
- ✅ View dashboard
- ✅ Manage ticket categories
- ✅ View audit logs (read-only)
- ✅ Manage vendors

### Cannot Do
- ❌ Manage users (cannot create/delete users)
- ❌ Manage roles dan permissions
- ❌ System configuration (general settings)
- ❌ Delete audit logs

### Typical Users
- IT Manager
- IT Supervisor
- Team Lead

---

## 3. IT STAFF

**Description:** Handle tickets, manage assigned assets, create dan maintain KB articles.

**Count Expected:** 10-50 users  
**Department:** IT (primarily), bisa dari department lain untuk support role

### Capabilities
- ✅ View tickets (all, tetapi primarily assigned to them)
- ✅ Update tickets they are assigned to
- ✅ Add comments ke tickets (dengan internal notes)
- ✅ Change ticket status (open → in_progress → resolved)
- ✅ Manage assets (assigned atau general inventory)
- ✅ Create/edit KB articles
- ✅ Log maintenance activities
- ✅ View reports (limited: ticket reports, asset reports)
- ✅ View dashboard
- ✅ View knowledge base
- ✅ Upload asset documents

### Cannot Do
- ❌ Manage SLA policies
- ❌ Manage departments
- ❌ Manage users
- ❌ Delete tickets atau assets
- ❌ Access audit logs
- ❌ Generate compliance reports

### Typical Users
- IT Technician
- Helpdesk Staff
- System Administrator
- Network Administrator
- Support Staff

---

## 4. END USER

**Description:** Regular employees yang menggunakan sistem untuk submit tickets dan access knowledge base.

**Count Expected:** 100-1000+ users  
**Department:** Any department

### Capabilities
- ✅ Create new tickets
- ✅ View their own tickets
- ✅ Add comments ke their tickets
- ✅ Browse knowledge base (published, non-internal articles)
- ✅ View ticket status
- ✅ Rate KB article helpfulness
- ✅ View own profile

### Cannot Do
- ❌ View other users' tickets
- ❌ Change ticket status
- ❌ Access asset management
- ❌ Create KB articles
- ❌ View reports
- ❌ Access admin features

### Typical Users
- All employees (non-IT)
- Contractors
- Interns

---

# PART 3: PERMISSION MATRIX

## Permission Categories

```
manage-users         : Create, update, delete users
manage-roles         : Create, update, delete roles
manage-departments   : Create, update, delete departments
manage-tickets       : Full ticket management (assign, update, close)
manage-assets        : Full asset lifecycle management
manage-kb            : Create, edit, delete KB articles
manage-sla           : Configure SLA policies
manage-categories    : Manage ticket categories
manage-vendors       : Manage vendor information
manage-reports       : Create, save, generate reports
manage-system        : System configuration, maintenance

view-all-tickets     : View semua tickets (bukan hanya assigned)
view-own-tickets     : View hanya own tickets
view-reports         : View generated reports
view-dashboard       : View dashboard
view-audit-logs      : View audit trail logs
view-kb              : View knowledge base articles
view-assets          : View asset information

create-tickets       : Submit new tickets
update-own-tickets   : Update tickets they created
comment-tickets      : Add comments ke tickets
assign-tickets       : Assign tickets ke staff

export-reports       : Export reports (PDF, Excel)
```

---

## Complete Permission Matrix

| Permission | Super Admin | IT Manager | IT Staff | End User |
|-----------|:-----------:|:----------:|:--------:|:--------:|
| **User Management** |
| manage-users | ✅ | ❌ | ❌ | ❌ |
| manage-roles | ✅ | ❌ | ❌ | ❌ |
| **Department Management** |
| manage-departments | ✅ | ✅ | ❌ | ❌ |
| **Ticket Management** |
| manage-tickets | ✅ | ✅ | ⚠️ | ❌ |
| view-all-tickets | ✅ | ✅ | ❌ | ❌ |
| view-own-tickets | ✅ | ✅ | ✅ | ✅ |
| create-tickets | ✅ | ✅ | ✅ | ✅ |
| update-own-tickets | ✅ | ✅ | ✅ | ✅ |
| comment-tickets | ✅ | ✅ | ✅ | ✅ |
| assign-tickets | ✅ | ✅ | ❌ | ❌ |
| **Asset Management** |
| manage-assets | ✅ | ✅ | ✅ | ❌ |
| view-assets | ✅ | ✅ | ✅ | ❌ |
| **Knowledge Base** |
| manage-kb | ✅ | ✅ | ✅ | ❌ |
| view-kb | ✅ | ✅ | ✅ | ✅ |
| **SLA Management** |
| manage-sla | ✅ | ✅ | ❌ | ❌ |
| **Categories** |
| manage-categories | ✅ | ✅ | ❌ | ❌ |
| **Vendor Management** |
| manage-vendors | ✅ | ✅ | ❌ | ❌ |
| **Reports** |
| manage-reports | ✅ | ✅ | ⚠️ | ❌ |
| view-reports | ✅ | ✅ | ⚠️ | ❌ |
| export-reports | ✅ | ✅ | ⚠️ | ❌ |
| **Dashboard & Analytics** |
| view-dashboard | ✅ | ✅ | ✅ | ❌ |
| view-audit-logs | ✅ | ⚠️ | ❌ | ❌ |
| **System** |
| manage-system | ✅ | ❌ | ❌ | ❌ |

**Legend:**
- ✅ = Full access
- ⚠️ = Limited access (see notes below)
- ❌ = No access

**Notes:**
- ⚠️ IT Staff manage-tickets: Hanya untuk tickets assigned ke mereka
- ⚠️ IT Staff manage-reports: Hanya ticket dan asset reports
- ⚠️ IT Staff view-audit-logs: Hanya ticket audit logs

---

# PART 4: PERMISSION IMPLEMENTATION

## 4.1 Database Structure

### Roles Table (Recap)
```sql
roles (
    id,
    name,              -- 'super_admin', 'it_manager', 'it_staff', 'end_user'
    display_name,      -- 'Super Admin', 'IT Manager', etc
    description,
    permissions,       -- JSON array
    is_system_role     -- Cannot be deleted
)
```

### Default Role Permissions

```json
// super_admin
{
  "permissions": ["*"]
}

// it_manager
{
  "permissions": [
    "manage-departments",
    "manage-tickets",
    "manage-assets",
    "manage-kb",
    "manage-sla",
    "manage-categories",
    "manage-vendors",
    "manage-reports",
    "view-all-tickets",
    "view-reports",
    "view-dashboard",
    "view-audit-logs",
    "view-kb",
    "create-tickets",
    "update-own-tickets",
    "comment-tickets",
    "assign-tickets",
    "export-reports"
  ]
}

// it_staff
{
  "permissions": [
    "manage-assets",
    "view-assets",
    "manage-kb",
    "view-kb",
    "view-own-tickets",
    "create-tickets",
    "update-own-tickets",
    "comment-tickets",
    "view-reports",
    "view-dashboard",
    "export-reports"
  ]
}

// end_user
{
  "permissions": [
    "view-kb",
    "create-tickets",
    "view-own-tickets",
    "update-own-tickets",
    "comment-tickets"
  ]
}
```

---

## 4.2 User Model Implementation

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'department_id',
        'employee_id',
        'avatar',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    // Permission Methods
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
        if (in_array('*', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    // Role Checks
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

    // Scope Queries
    public function scopeWithPermission($query, string $permission)
    {
        return $query->whereHas('role', function ($q) use ($permission) {
            $q->whereJsonContains('permissions', $permission)
              ->orWhereJsonContains('permissions', '*');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Status Helpers
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }
}
```

---

## 4.3 Role Model Implementation

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // Relationships
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Permission Helpers
    public function hasPermission(string $permission): bool
    {
        return in_array('*', $this->permissions) 
            || in_array($permission, $this->permissions);
    }

    public function givePermission(string $permission): void
    {
        if (! $this->hasPermission($permission)) {
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

    // Static Helpers
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
```

---

## 4.4 Policy Implementation

### Ticket Policy

```php
<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine if user can view tickets list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-all-tickets') 
            || $user->hasPermission('view-own-tickets');
    }

    /**
     * Determine if user can view a specific ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Super admin dan IT Manager can view all
        if ($user->hasPermission('view-all-tickets')) {
            return true;
        }

        // Users can view their own tickets
        return $ticket->user_id === $user->id 
            && $user->hasPermission('view-own-tickets');
    }

    /**
     * Determine if user can create tickets.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-tickets');
    }

    /**
     * Determine if user can update a ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Full ticket management permission
        if ($user->hasPermission('manage-tickets')) {
            return true;
        }

        // Users can update their own tickets (limited fields)
        if ($ticket->user_id === $user->id 
            && $user->hasPermission('update-own-tickets')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can assign tickets.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->hasPermission('assign-tickets');
    }

    /**
     * Determine if user can change ticket status.
     */
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        // IT Manager dan IT Staff (if assigned)
        if ($user->hasPermission('manage-tickets')) {
            return true;
        }

        return $ticket->assignee_id === $user->id;
    }

    /**
     * Determine if user can close tickets.
     */
    public function close(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('it_manager')
            || $ticket->assignee_id === $user->id;
    }

    /**
     * Determine if user can delete tickets.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('super_admin') 
            || $user->hasRole('it_manager');
    }

    /**
     * Determine if user can add comments.
     */
    public function comment(User $user, Ticket $ticket): bool
    {
        if ($ticket->user_id === $user->id) {
            return $user->hasPermission('comment-tickets');
        }

        return $user->hasPermission('comment-tickets');
    }

    /**
     * Determine if user can add internal notes.
     */
    public function addInternalNote(User $user, Ticket $ticket): bool
    {
        return $user->hasPermission('manage-tickets')
            || $user->hasRole('it_manager');
    }
}
```

### Asset Policy

```php
<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-assets')
            || $user->hasPermission('manage-assets');
    }

    public function view(User $user, Asset $asset): bool
    {
        return $user->hasPermission('view-assets')
            || $user->hasPermission('manage-assets');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-assets');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }

    public function delete(User $user, Asset $asset): bool
    {
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    public function assign(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }

    public function logMaintenance(User $user, Asset $asset): bool
    {
        return $user->hasPermission('manage-assets');
    }
}
```

### Knowledge Base Policy

```php
<?php

namespace App\Policies;

use App\Models\KbArticle;
use App\Models\User;

class KbArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-kb');
    }

    public function view(User $user, KbArticle $article): bool
    {
        if ($article->status === 'published' 
            && $user->hasPermission('view-kb')) {
            if ($article->is_internal) {
                return $user->hasPermission('manage-kb');
            }
            return true;
        }

        if ($article->author_id === $user->id) {
            return true;
        }

        return $user->hasPermission('manage-kb');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-kb');
    }

    public function update(User $user, KbArticle $article): bool
    {
        if ($article->author_id === $user->id) {
            return $user->hasPermission('manage-kb');
        }

        return $user->hasPermission('manage-kb');
    }

    public function delete(User $user, KbArticle $article): bool
    {
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    public function publish(User $user, KbArticle $article): bool
    {
        return $user->hasRole('it_manager')
            || $user->hasRole('super_admin');
    }

    public function vote(User $user, KbArticle $article): bool
    {
        return $user->hasPermission('view-kb');
    }
}
```

---

## 4.5 Middleware Implementation

### CheckPermission Middleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user() || ! $request->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

### Usage in Routes

```php
// routes/web.php

// Super Admin only
Route::middleware(['auth', 'permission:manage-users'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
});

// IT Manager
Route::middleware(['auth', 'permission:manage-sla'])->group(function () {
    Route::get('/sla-policies', [SlaPolicyController::class, 'index']);
    Route::post('/sla-policies', [SlaPolicyController::class, 'store']);
});

// IT Staff
Route::middleware(['auth', 'permission:manage-assets'])->group(function () {
    Route::resource('assets', AssetController::class);
});
```

---

# PART 5: USER LIFECYCLE

## 5.1 User Creation Flow (Invite-Only)

```
Admin creates user
         │
         ▼
Generate temporary password
         │
         ▼
Create user (status: active)
         │
         ▼
Display credentials to admin
(admin shares with user manually)
         │
         ▼
User logs in dengan temporary password
         │
         ▼
Force password change on first login
         │
         ▼
User dapat access sesuai role
```

## 5.2 User Status Flow

```
┌──────────┐    activate    ┌──────────┐
│ Inactive │ ──────────────►│  Active  │
│          │ ◄──────────────│          │
└──────────┘    deactivate  └──────────┘
                                  │
                                  │ delete
                                  ▼
                          ┌──────────────┐
                          │ Soft Deleted │
                          │ (recoverable)│
                          └──────────────┘
```

---

# PART 6: SECURITY CONSIDERATIONS

## 6.1 Password Policy

```php
// Password requirements
'min_length' => 8,
'require_uppercase' => true,
'require_lowercase' => true,
'require_number' => true,
'require_special_char' => true,
```

## 6.2 Session Security

```php
// config/session.php
'lifetime' => 120,        // 2 hours
'expire_on_close' => true,
'secure' => true,         // HTTPS only
'http_only' => true,
'same_site' => 'lax',
```

## 6.3 Brute Force Protection

```php
// Rate limiting untuk login
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinutes(5, 10)->by($request->email);
});
```

## 6.4 Audit Logging

Critical actions to log:
- User login/logout
- Permission changes
- Role modifications
- User creation/deletion
- Ticket status changes
- Asset assignment changes
- SLA policy changes
- Report exports

```php
// Audit logging helper
function auditLog(string $action, array $data = []): void
{
    AuditLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'data' => $data,
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
}
```

---

# PART 7: UI ACCESS CONTROL

## 7.1 Navigation Menu (Role-Based)

```blade
{{-- resources/views/layouts/navigation.blade.php --}}

<nav>
    @can('view-dashboard')
        <a href="{{ route('dashboard') }}">Dashboard</a>
    @endcan

    @can('view-own-tickets')
        <a href="{{ route('tickets.index') }}">Tickets</a>
    @endcan

    @can('view-assets')
        <a href="{{ route('assets.index') }}">Assets</a>
    @endcan

    @can('view-kb')
        <a href="{{ route('kb.index') }}">Knowledge Base</a>
    @endcan

    @can('view-reports')
        <a href="{{ route('reports.index') }}">Reports</a>
    @endcan

    @can('manage-users')
        <a href="{{ route('users.index') }}">Users</a>
    @endcan

    @can('manage-sla')
        <a href="{{ route('sla-policies.index') }}">SLA Policies</a>
    @endcan
</nav>
```

---

# SUMMARY

## Role Quick Reference

| Role | Primary Focus | Key Limitation |
|------|--------------|----------------|
| **Super Admin** | System administration | None (full access) |
| **IT Manager** | Operations management | Cannot manage users/roles |
| **IT Staff** | Ticket handling, assets | Only assigned tickets |
| **End User** | Submit & track tickets | Own tickets only |

## Permission Categories Summary

| Category | Permissions Count | Description |
|----------|------------------|-------------|
| User Management | 2 | manage-users, manage-roles |
| Department | 1 | manage-departments |
| Tickets | 6 | manage, view, create, update, comment, assign |
| Assets | 2 | manage, view |
| Knowledge Base | 2 | manage, view |
| SLA | 1 | manage-sla |
| Reports | 3 | manage, view, export |
| System | 2 | manage-system, view-audit-logs |
| **Total** | **19** | |

---

# APPENDIX

## A. Permission Naming Convention

All permissions use `kebab-case` dengan verb-noun pattern:

```
{action}-{resource}

Examples:
manage-users
view-tickets
create-tickets
export-reports
```

**Actions:**
- `manage` - Full CRUD + additional operations
- `view` - Read-only access
- `create` - Create new records
- `update` - Update existing records
- `delete` - Delete records
- `assign` - Assign/allocate resources
- `export` - Export data

## B. Authorization Best Practices

1. ✅ Always check authorization BEFORE processing
2. ✅ Use policies untuk resource-level checks
3. ✅ Use gates untuk simple yes/no checks
4. ✅ Use middleware untuk route-level protection
5. ✅ Use Blade directives untuk UI elements
6. ✅ Log authorization failures untuk audit
7. ✅ Fail securely (default deny)
