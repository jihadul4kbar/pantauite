# Context & Constitution
## PantauITE - IT Service Management Platform

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-06  
**Last Updated:** 2026-04-06

---

# PART 1: PROJECT CONTEXT

## 1.1 Project Overview

**Project Name:** PantauITE  
**Type:** IT Service Management (ITSM) Platform  
**Purpose:** Platform terintegrasi untuk mengelola IT service operations termasuk ticketing, knowledge base, asset management, SLA tracking, dan vendor management.

## 1.2 Business Context

### Problem Domain
Departemen IT di organisasi modern menghadapi tantangan:
1. **Fragmented Tools**: Menggunakan multiple tools terpisah untuk ticketing, asset tracking, documentation
2. **Lack of Visibility**: Sulit mendapat gambaran real-time tentang IT operations
3. **SLA Compliance**: Sulit track dan ensure SLA compliance tanpa sistem yang tepat
4. **Knowledge Silos**: Knowledge tersebar dan sulit diakses
5. **Asset Chaos**: Tidak ada single source of truth untuk IT assets
6. **Vendor Management**: Sulit track vendor performance dan warranty claims

### Solution
PantauITE menyediakan **single platform** yang mengintegrasikan:
- Ticket management dengan SLA tracking dan escalation
- Centralized knowledge base dengan versioning dan feedback
- Comprehensive asset management dengan lifecycle tracking
- Vendor management untuk procurement dan maintenance
- Reporting dan analytics

## 1.3 Target Users

### Primary Users
1. **IT Manager** (5-10 users)
   - Needs: Dashboard overview, reports, SLA monitoring, team management
   - Technical level: Medium
   
2. **IT Staff/Technician** (20-50 users)
   - Needs: Ticket handling, asset management, KB creation
   - Technical level: High
   
3. **End Users/Employees** (100-1000+ users)
   - Needs: Submit tickets, track status, browse KB
   - Technical level: Low to Medium

### User Environment
- **Access Pattern**: Primarily desktop/laptop, occasional mobile
- **Usage Frequency**: Daily untuk IT staff, occasional untuk end users
- **Network**: Internal network (intranet) atau internet
- **Browser**: Modern browsers (Chrome, Firefox, Edge, Safari)

## 1.4 Technical Context

### Current State
- Fresh Laravel 13 installation
- No existing codebase atau legacy system
- Greenfield development
- MySQL database
- Single-server deployment (initially)

### Constraints
- No multi-tenant support required
- No external API integration (v1.0)
- No real-time updates (polling only)
- Invite-only user registration
- Email notifications not required (v1.0)

### Assumptions
- Server environment: Linux (Ubuntu/CentOS)
- PHP 8.3+ available
- MySQL 8.0+ available
- Basic DevOps knowledge untuk deployment
- Single timezone untuk seluruh users

## 1.5 Success Criteria

### Functional Success
- [ ] All modules (ticketing, KB, assets, SLA, reporting) working end-to-end
- [ ] All user roles dapat access sesuai permission
- [ ] SLA tracking accurate dengan escalation
- [ ] Reports generate correctly dalam format PDF & Excel
- [ ] Data integrity maintained (no orphaned records, valid relationships)

### Performance Success
- [ ] Page load time < 3 seconds (95th percentile)
- [ ] Support 100+ concurrent users
- [ ] Handle 10,000+ tickets tanpa degradation
- [ ] Handle 5,000+ assets tanpa performance issues
- [ ] Database queries < 100ms untuk common operations

### User Success
- [ ] User dapat submit ticket dalam < 2 menit
- [ ] IT staff dapat resolve dan close ticket dengan proper workflow
- [ ] User dapat find knowledge base article dalam < 3 clicks
- [ ] Admin dapat generate report dan export dalam < 30 seconds
- [ ] Asset inventory accurate > 95%

---

# PART 2: CONSTITUTION

## 2.1 Core Principles

### Principle 1: Simplicity First
> "Make it work, make it right, make it fast - in that order"

- Prefer simple solutions over complex ones
- YAGNI (You Aren't Gonna Need It) - don't over-engineer
- Readable code > clever code
- Default to Laravel conventions daripada custom patterns

**Examples:**
- ✅ Use Eloquent relationships daripada raw queries
- ✅ Use Laravel validation rules daripada custom validation
- ❌ Don't create abstract factory patterns untuk simple CRUD
- ❌ Don't optimize prematurely (profile first, optimize later)

### Principle 2: Consistency
> "Code should look like it was written by one person"

- Follow PSR-12 coding standards
- Consistent naming conventions
- Consistent file structure
- Consistent error handling
- Consistent response formats

**Naming Conventions:**
```
Variables:     $ticketStatus, $assetCode (camelCase)
Methods:       createTicket(), resolveIssue() (camelCase)
Classes:       TicketService, AssetRepository (PascalCase)
Constants:     STATUS_OPEN, PRIORITY_HIGH (UPPER_SNAKE_CASE)
Database:      ticket_statuses, asset_types (snake_case)
Routes:        tickets.create, assets.store (kebab-case.dot.notation)
```

### Principle 3: Single Responsibility
> "A class should have only one reason to change"

- Controllers: Handle HTTP only
- Services: Business logic only
- Repositories: Data access only
- Models: Entity definition only
- Policies: Authorization only

### Principle 4: Testable Architecture
> "If it's hard to test, it's hard to maintain"

- Dependency injection over facades (where practical)
- Service layer untuk business logic (testable)
- Repository pattern untuk data access (mockable)
- Form Request validation (testable in isolation)

### Principle 5: Secure by Default
> "Trust no input, authorize every action"

- Validate all input (Form Requests)
- Authorize all actions (Policies & Gates)
- Escape all output (Blade auto-escaping)
- CSRF protection (Laravel default)
- SQL injection prevention (Eloquent/Query Builder)
- XSS prevention (Blade {{ }} syntax)

## 2.2 Architecture Standards

### Directory Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── TicketController.php
│   │   ├── KnowledgeBaseController.php
│   │   ├── AssetController.php
│   │   ├── ReportController.php
│   │   └── DashboardController.php
│   ├── Requests/
│   │   ├── StoreTicketRequest.php
│   │   ├── UpdateTicketRequest.php
│   │   ├── StoreAssetRequest.php
│   │   └── ...
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Ticket.php
│   ├── TicketComment.php
│   ├── Asset.php
│   ├── AssetLifecycle.php
│   ├── KbCategory.php
│   ├── KbArticle.php
│   ├── SlaPolicy.php
│   ├── Department.php
│   └── ...
├── Services/
│   ├── TicketService.php
│   ├── SlaService.php
│   ├── AssetService.php
│   ├── ReportService.php
│   └── ...
├── Repositories/
│   ├── TicketRepository.php
│   ├── AssetRepository.php
│   ├── KbRepository.php
│   └── ...
├── Policies/
│   ├── TicketPolicy.php
│   ├── AssetPolicy.php
│   ├── KbArticlePolicy.php
│   └── ...
├── Enums/
│   ├── TicketStatus.php
│   ├── TicketPriority.php
│   ├── AssetType.php
│   ├── AssetStatus.php
│   └── ...
├── DTOs/
│   ├── TicketData.php
│   ├── AssetData.php
│   ├── ReportData.php
│   └── ...
├── View/
│   └── Components/
│       ├── Alert.php
│       ├── Badge.php
│       ├── Card.php
│       └── ...
```

### Code Organization Rules

#### Rule 1: Controller Standards
```php
// ✅ DO: Keep controllers thin
public function store(StoreTicketRequest $request)
{
    $ticket = $this->ticketService->createTicket($request->validated());
    
    return redirect()
        ->route('tickets.show', $ticket)
        ->with('success', 'Ticket created successfully.');
}

// ❌ DON'T: Put business logic in controllers
public function store(Request $request)
{
    // 50 lines of business logic...
    // Database queries...
    // Calculations...
    return view(...);
}
```

#### Rule 2: Service Standards
```php
// ✅ DO: Services handle business logic
class TicketService
{
    public function __construct(
        private TicketRepository $tickets,
        private SlaService $sla,
    ) {}
    
    public function createTicket(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $ticket = $this->tickets->create($data);
            $this->sla->initialize($ticket);
            
            return $ticket;
        });
    }
}
```

#### Rule 3: Repository Standards
```php
// ✅ DO: Repositories handle data access
class TicketRepository
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }
    
    public function findByStatus(TicketStatus $status): Collection
    {
        return Ticket::where('status', $status)
            ->with(['assignee', 'department'])
            ->latest()
            ->get();
    }
}
```

#### Rule 4: Model Standards
```php
// ✅ DO: Models define entity structure
class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'status',
        'priority',
        'user_id',
        'assignee_id',
        'department_id',
        'category_id',
    ];
    
    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
    
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessors & Mutators
    public function getIsOverdueAttribute(): bool
    {
        return $this->sla_deadline && now()->gt($this->sla_deadline);
    }
}
```

### Error Handling Standards

#### Standardized Error Responses
```php
// Web responses
return back()
    ->withErrors($validator)
    ->withInput();
```

#### Exception Handling
```php
// ✅ DO: Use custom exceptions untuk business rules
throw new TicketAlreadyResolvedException($ticket);
throw new SlaBreachedException($ticket);
throw new AssetNotAvailableException($asset);

// ❌ DON'T: Use generic exceptions untuk business logic
throw new \Exception('Something went wrong');
```

### Validation Standards

```php
// ✅ DO: Use Form Request classes
class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->can('create', Ticket::class);
    }
    
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::in(TicketPriority::cases())],
            'category_id' => ['required', 'exists:categories,id'],
            'attachments.*' => ['nullable', 'file', 'max:5120'], // 5MB
        ];
    }
    
    public function messages(): array
    {
        return [
            'subject.required' => 'Ticket subject is required.',
            'description.required' => 'Please describe the issue.',
        ];
    }
}
```

### Authorization Standards

```php
// ✅ DO: Use Policies untuk resource authorization
class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id 
            || $user->hasPermission('view-all-tickets');
    }
    
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasPermission('manage-tickets')
            || $user->id === $ticket->assignee_id;
    }
}

// In Controller
public function show(Ticket $ticket)
{
    $this->authorize('view', $ticket);
    
    return view('tickets.show', compact('ticket'));
}
```

## 2.3 Database Standards

### Migration Standards
```php
// ✅ DO: Use descriptive migration names
// create_tickets_table.php
// add_sla_deadline_to_tickets_table.php

// ✅ DO: Include indexes untuk foreign keys dan queried columns
Schema::create('tickets', function (Blueprint $table) {
    $table->id();
    $table->string('ticket_number')->unique();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('department_id')->constrained()->restrictOnDelete();
    $table->string('subject');
    $table->text('description');
    $table->string('status')->default('open');
    $table->string('priority')->default('medium');
    $table->datetime('sla_deadline')->nullable();
    $table->softDeletes();
    $table->timestamps();
    
    // Indexes
    $table->index(['status', 'priority']);
    $table->index(['user_id', 'created_at']);
    $table->index(['department_id', 'status']);
});
```

### Query Standards
```php
// ✅ DO: Use Eloquent untuk most queries
$tickets = Ticket::with(['user', 'assignee', 'department'])
    ->where('status', TicketStatus::OPEN)
    ->latest()
    ->paginate(20);

// ✅ DO: Use Query Builder untuk complex queries
$report = DB::table('tickets')
    ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
    ->whereBetween('created_at', [$start, $end])
    ->groupBy('date')
    ->get();

// ❌ DON'T: Use raw SQL unless necessary
DB::select('SELECT * FROM tickets WHERE...'); // Avoid unless complex
```

## 2.4 Frontend Standards

### Blade Component Standards
```blade
{{-- ✅ DO: Use components untuk reusable UI --}}
<x-card>
    <x-slot name="title">Ticket Details</x-slot>
    
    <div class="space-y-4">
        <x-badge :type="$ticket->status">
            {{ $ticket->status->label() }}
        </x-badge>
        
        <x-alert type="warning" :dismissible="true">
            SLA deadline approaching!
        </x-alert>
    </div>
</x-card>
```

### Alpine.js Standards
```blade
{{-- ✅ DO: Keep Alpine logic simple --}}
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>Content</div>
</div>

{{-- ❌ DON'T: Put complex logic in Alpine --}}
<div x-data="{ 
    tickets: [], 
    init() { 
        // 50 lines of JavaScript...
    } 
}">
```

### Form Standards
```blade
{{-- ✅ DO: Use Laravel forms dengan CSRF --}}
<form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" id="subject" 
               value="{{ old('subject') }}" required>
        @error('subject')
            <span class="error">{{ $message }}</span>
        @enderror
    </div>
    
    <button type="submit">Submit</button>
</form>
```

## 2.5 Testing Standards

### Test Organization
```
tests/
├── Unit/
│   ├── Services/
│   │   ├── TicketServiceTest.php
│   │   └── SlaServiceTest.php
│   ├── Repositories/
│   └── Models/
├── Feature/
│   ├── Http/
│   │   ├── TicketControllerTest.php
│   │   └── AssetControllerTest.php
│   └── Authorization/
│       ├── TicketPolicyTest.php
│       └── AssetPolicyTest.php
└── Pest.php
```

### Test Standards
```php
// ✅ DO: Write descriptive test names
test('authenticated user can create tickets', function () {
    // ...
});

test('unauthenticated user cannot view tickets', function () {
    // ...
});

// ✅ DO: Use factories dan seeders
$user = User::factory()->create();
$ticket = Ticket::factory()->create([
    'user_id' => $user->id,
    'status' => TicketStatus::OPEN,
]);

// ✅ DO: Test happy path dan edge cases
test('ticket cannot be created with invalid data', function () {
    $response = $this->post(route('tickets.store'), []);
    
    $response->assertSessionHasErrors(['subject', 'description']);
});
```

## 2.6 Git & Commit Standards

### Commit Message Format
```
type: short description

long description (optional)

Types:
- feat: New feature
- fix: Bug fix
- refactor: Code refactoring
- style: Formatting, no code change
- docs: Documentation only
- test: Test additions/updates
- chore: Maintenance tasks
```

**Examples:**
```
feat: add ticket creation functionality

Implement ticket creation with validation, SLA initialization,
and auto-generated ticket numbers.

fix: resolve SLA calculation for business hours

refactor: extract ticket service dari controller

docs: add architecture decision records
```

### Branch Naming
```
feature/ticket-commenting
feature/knowledge-base
bugfix/sla-calculation
hotfix/asset-deprecation
release/v1.0.0
```

## 2.7 Code Review Checklist

Before merging any PR:
- [ ] Tests passing (Pest)
- [ ] No debugging code (dd(), dump(), var_dump())
- [ ] Follows naming conventions
- [ ] Controllers are thin (business logic in services)
- [ ] Form requests digunakan untuk validation
- [ ] Policies ada untuk authorization
- [ ] Error handling appropriate
- [ ] No hardcoded values (use config/constants)
- [ ] Database queries optimized (N+1 checked)
- [ ] Documentation updated (if needed)
- [ ] Migration reversible (up/down methods)
- [ ] Security considerations addressed

## 2.8 Deployment Standards

### Pre-Deployment Checklist
- [ ] All tests passing
- [ ] Database migrations tested
- [ ] Environment variables documented
- [ ] Assets compiled (`npm run build`)
- [ ] Cache cleared (`php artisan cache:clear`)
- [ ] Config cached (`php artisan config:cache`)
- [ ] Routes cached (`php artisan route:cache`)
- [ ] Views cached (`php artisan view:cache`)
- [ ] Queue worker running
- [ ] Error logging configured
- [ ] Backup strategy in place

### Environment Variables
```env
# Application
APP_NAME="PantauITE"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pantauite.company.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pantauite
DB_USERNAME=pantauite_user
DB_PASSWORD=<secure_password>

# Queue
QUEUE_CONNECTION=database

# SLA Configuration (business hours)
SLA_BUSINESS_HOURS_START=08:00
SLA_BUSINESS_HOURS_END=17:00
SLA_BUSINESS_DAYS=1,2,3,4,5

# File Upload
MAX_UPLOAD_SIZE=5120
```

---

# PART 3: DECISION LOG

| # | Decision | Rationale | Date |
|---|----------|-----------|------|
| 1 | Single-tenant only | Simplicity, no multi-tenant requirement | 2026-04-06 |
| 2 | No real-time updates | Requirement, reduces complexity | 2026-04-06 |
| 3 | No external API | Out of scope untuk v1.0 | 2026-04-06 |
| 4 | Invite-only registration | Security, internal IT system | 2026-04-06 |
| 5 | Database queue driver | No Redis infrastructure needed | 2026-04-06 |
| 6 | Service-Repository pattern | Separation of concerns, testability | 2026-04-06 |
| 7 | Blade + Alpine.js | Simplicity, no SPA complexity | 2026-04-06 |
| 8 | Custom RBAC | Simple role structure, no package needed | 2026-04-06 |
| 9 | Soft deletes untuk critical entities | Audit trail, data recovery | 2026-04-06 |
| 10 | Form Request validation | Clean controllers, reusable | 2026-04-06 |

---

# PART 4: GLOSSARY

| Term | Definition |
|------|-----------|
| **ITSM** | IT Service Management |
| **SLA** | Service Level Agreement - Target response/resolution time |
| **Ticket** | A reported issue atau request yang perlu ditangani |
| **KB** | Knowledge Base - Dokumentasi dan troubleshooting guides |
| **Asset** | IT resource (hardware, software, network device) |
| **Lifecycle** | Stages dari asset (procurement → retirement) |
| **Department** | Organizational unit (multi-department support) |
| **Priority** | Ticket urgency level (Critical, High, Medium, Low) |
| **SLA Breach** | Ketika ticket tidak meet SLA target |
| **RBAC** | Role-Based Access Control |
| **Escalation** | Process untuk notify management saat SLA breached |
| **Vendor** | Supplier/provider untuk assets atau maintenance services |

---

# APPENDIX

## A. Reference Documents
- [PRD](./01-prd.md) - Product Requirement Document
- [ADR](./02-adr.md) - Architecture Decision Records
- [Database Schema](./04-database-schema.md)
- [User Roles](./05-user-roles-permissions.md)

## B. External References
- [Laravel Documentation](https://laravel.com/docs)
- [PSR-12 Coding Standards](https://www.php-fig.org/psr/psr-12/)
- [ITIL Framework](https://www.axelos.com/certifications/itil-service-management)
