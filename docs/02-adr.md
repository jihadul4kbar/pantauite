# Architecture Decision Record (ADR)
## PantauITE - IT Service Management Platform

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-06  
**Last Updated:** 2026-04-06

---

## ADR-001: Use Laravel 13 as Backend Framework

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan web framework yang mature, well-documented, dan memiliki ecosystem yang kuat untuk membangun ITSM platform.

### Decision
Menggunakan **Laravel 13** sebagai backend framework dengan pertimbangan:
- Built-in authentication & authorization
- Eloquent ORM untuk database abstraction
- Migration system untuk version control database
- Queue system untuk background jobs
- Excellent documentation dan community support
- Convention over configuration (productive untuk team)

### Consequences
**Positive:**
- Development cepat dengan built-in features
- Code structure yang konsisten
- Easy to maintain dan scale
- Large package ecosystem

**Negative:**
- PHP version requirement (8.3+)
- Slightly heavier than micro-frameworks
- Team perlu understanding Laravel conventions

---

## ADR-002: Use MySQL as Primary Database

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan relational database untuk menyimpan data terstruktur (tickets, assets, users, relationships).

### Decision
Menggunakan **MySQL 8.0+** sebagai primary database dengan pertimbangan:
- Relational data model (tickets, users, assets punya relationships)
- ACID compliance untuk data integrity
- Laravel native support yang excellent
- Widely adopted dan well-supported
- Free dan open-source
- Support untuk JSON columns jika perlu flexible schema

### Consequences
**Positive:**
- Strong data consistency
- Complex queries dengan JOINs
- Transaction support
- Easy backup dan replication

**Negative:**
- Vertical scaling limitation (harus shard untuk horizontal)
- Performance tuning needed untuk large datasets

---

## ADR-003: Use Vite + Tailwind CSS + Alpine.js for Frontend

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan frontend stack yang ringan, easy to integrate dengan Laravel, dan tidak terlalu complex (tidak perlu React/Vue full SPA).

### Decision
Menggunakan **Vite + Tailwind CSS 4 + Alpine.js** dengan pertimbangan:
- **Vite**: Fast build tool, Laravel native integration
- **Tailwind CSS 4**: Utility-first CSS, rapid UI development, consistent design
- **Alpine.js**: Lightweight JavaScript untuk interactivity (tidak perlu full framework)

Pendekatan: **Server-side rendered Blade templates** dengan Alpine.js untuk interactivity.

### Consequences
**Positive:**
- Simple architecture (tidak perlu separate frontend/backend)
- Fast development
- Minimal JavaScript overhead
- Good SEO dan performance
- Easy to maintain

**Negative:**
- Not as dynamic as full SPA
- Page reloads untuk navigation
- Alpine.js ecosystem lebih kecil dari React/Vue

---

## ADR-004: Use Service-Repository Pattern

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan architecture pattern yang memisahkan concerns dan membuat code maintainable.

### Decision
Menggunakan **Service-Repository Pattern** dengan structure:
```
Controllers → Handle HTTP requests/responses
Services    → Business logic
Repositories → Data access layer
Models      → Eloquent models dengan relationships
```

**Layer Responsibilities:**
- **Controller**: Validate input, call service, return response
- **Service**: Business logic, orchestration, transactions
- **Repository**: Query logic, data access
- **Model**: Entity definition, relationships, accessors/mutators

### Consequences
**Positive:**
- Separation of concerns
- Easy to test (mock repositories/services)
- Business logic terpisah dari HTTP layer
- Reusable services

**Negative:**
- More files dan structure overhead
- Might be overkill untuk simple CRUD
- Team perlu understand pattern

---

## ADR-005: Use Database Queue Driver

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan queue system untuk background jobs (report generation, SLA checks) tanpa menambah infrastructure complexity.

### Decision
Menggunakan **Database queue driver** dengan pertimbangan:
- No additional infrastructure needed (no Redis/RabbitMQ)
- Simpler deployment dan maintenance
- Sufficient untuk scale yang diharapkan (100+ users)
- Laravel native support

**Jobs yang akan di-queue:**
- Report generation (PDF/Excel)
- SLA breach checks
- Data cleanup tasks

### Consequences
**Positive:**
- Zero additional infrastructure
- Easy to monitor (jobs table)
- Transactional safety

**Negative:**
- Slower dari Redis/RabbitMQ
- Database load increase
- Not suitable untuk high-throughput scenarios

---

## ADR-006: Use Blade Templates untuk Views

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan templating engine yang integrate well dengan Laravel dan easy to maintain.

### Decision
Menggunakan **Laravel Blade** dengan component-based architecture:
- Blade components untuk reusable UI elements
- Layouts untuk consistent page structure
- Alpine.js untuk client-side interactivity
- Server-side rendering untuk simplicity

### Consequences
**Positive:**
- Tight Laravel integration
- Server-side rendering (good SEO)
- Simple deployment (no separate frontend build)
- Easy to learn

**Negative:**
- Less dynamic daripada SPA
- Backend developer perlu frontend skills
- Testing UI lebih sulit

---

## ADR-007: Use Role-Based Access Control (RBAC)

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan authorization system untuk mengontrol akses berdasarkan role user.

### Decision
Menggunakan **custom RBAC** dengan structure:
- **Roles**: Super Admin, IT Manager, IT Staff, End User
- **Permissions**: Granular permissions (manage-tickets, view-reports, dll)
- **Policies**: Laravel policies untuk resource-level authorization
- **Gates**: Untuk simple permission checks

Tidak menggunakan package seperti Spatie Laravel-Permission karena role structure sederhana dan fixed.

### Consequences
**Positive:**
- Full control over implementation
- No dependency pada third-party package
- Tailored untuk specific use case
- Simpler dan lighter

**Negative:**
- Perlu maintain sendiri
- No community support untuk issues
- Must build permission UI dari scratch

---

## ADR-008: Use Soft Deletes untuk Critical Entities

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Data seperti tickets, assets, dan KB articles tidak boleh benar-benar dihapus untuk audit trail.

### Decision
Menggunakan **Laravel Soft Deletes** untuk entities:
- Tickets
- Assets
- Knowledge Base Articles
- Users
- Departments

Deleted records ditandai dengan `deleted_at` timestamp dan di-hide dari default queries.

### Consequences
**Positive:**
- Data recovery possible
- Audit trail maintained
- Accidental deletion safe

**Negative:**
- Database size grows faster
- Queries perlu consider soft deletes
- Unique constraints perlu handle deleted records

---

## ADR-009: Use Form Request Validation

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Kita memerlukan validation approach yang clean dan reusable.

### Decision
Menggunakan **Laravel Form Request classes** untuk semua validation:
- Separate class per request type
- Reusable validation rules
- Authorization checks dalam form request
- Custom error messages

### Consequences
**Positive:**
- Controllers tetap clean
- Validation logic centralized
- Reusable across endpoints
- Easy to test

**Negative:**
- More files
- Slightly more complex untuk simple forms

---

## ADR-010: No Real-time WebSocket Updates

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Requirement menyebutkan tidak perlu real-time updates.

### Decision
Tidak menggunakan WebSocket atau real-time technology. Status updates menggunakan:
- **Manual refresh** oleh user
- **Polling** (opsional, configurable) untuk dashboard stats
- **Meta refresh** untuk critical pages (optional)

### Consequences
**Positive:**
- Simpler architecture
- No additional infrastructure (no Pusher/Soketi)
- Lower cost
- Easier deployment

**Negative:**
- User perlu manual refresh
- Not as responsive
- Might feel outdated

---

## ADR-011: Use Auto-Increment IDs dengan Custom Codes

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Pertimbangan menggunakan UUID untuk semua public-facing IDs (ticket numbers, asset codes).

### Decision
**Rejected**. Menggunakan **auto-increment integer IDs** dengan custom numbering:
- Tickets: `TKT-YYYY-NNNN` format (e.g., TKT-2026-0001)
- Assets: `AST-{TYPE}-NNNN` format (e.g., AST-HW-0001)
- KB Articles: `KB-NNNN` format

UUID terlalu panjang dan tidak user-friendly untuk display.

### Consequences
**Positive:**
- User-friendly codes
- Easier to reference verbally
- Shorter URLs

**Negative:**
- Need to handle code generation
- Potential for code collisions (handled by unique constraints)
- Sequential IDs reveal business metrics

---

## ADR-012: Use Database Indexing untuk Performance

**Status:** Accepted  
**Date:** 2026-04-06

### Context
Dengan expected data volume (10,000+ tickets, 5,000+ assets), query performance menjadi concern.

### Decision
Menambahkan indexes pada:
- Foreign keys (user_id, department_id, category_id)
- Frequently queried columns (status, priority, created_at)
- Search columns (title, description)
- Composite indexes untuk common filter combinations
- Full-text index untuk KB articles

### Consequences
**Positive:**
- Faster queries
- Better user experience
- Scalable untuk growth

**Negative:**
- Slower writes
- More storage needed
- Need maintenance

---

## ADR-013: Use Environment-Based Configuration

**Status:** Accepted  
**Date:** 2026-04-06

### Context
SLA thresholds, business hours, dan konfigurasi lain perlu flexible tanpa code changes.

### Decision
Menggunakan **configuration files** (`config/*.php`) yang membaca dari `.env`:
- SLA thresholds di `config/sla.php`
- App settings di `config/app.php`
- Asset settings di `config/asset.php`

Configurable via `.env` variables untuk easy deployment.

### Consequences
**Positive:**
- No code changes untuk config updates
- Environment-specific settings
- Easy deployment

**Negative:**
- Config sprawl jika tidak organized
- Need documentation untuk all settings

---

## Appendix

### Technology Stack Summary
| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 13.x |
| Language | PHP | 8.3+ |
| Database | MySQL | 8.0+ |
| Frontend Build | Vite | 8.x |
| CSS | Tailwind CSS | 4.x |
| JavaScript | Alpine.js | 3.x |
| Templating | Blade | 13.x |
| Queue | Database | - |
| Testing | Pest | 4.x |

### Architecture Diagram
```
┌─────────────────────────────────────────┐
│           Client (Browser)              │
│      (Blade + Tailwind + Alpine.js)     │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│          Laravel Application            │
│  ┌───────────────────────────────────┐  │
│  │          Routes                   │  │
│  └──────────────┬────────────────────┘  │
│  ┌──────────────▼────────────────────┐  │
│  │        Controllers                │  │
│  └──────────────┬────────────────────┘  │
│  ┌──────────────▼────────────────────┐  │
│  │       Services (Business Logic)   │  │
│  └──────────────┬────────────────────┘  │
│  ┌──────────────▼────────────────────┐  │
│  │    Repositories (Data Access)     │  │
│  └──────────────┬────────────────────┘  │
│  ┌──────────────▼────────────────────┐  │
│  │       Models (Eloquent)           │  │
│  └───────────────────────────────────┘  │
└────────────────┬────────────────────────┘
                 │
                 ▼
┌─────────────────────────────────────────┐
│          MySQL Database                 │
└─────────────────────────────────────────┘
```
