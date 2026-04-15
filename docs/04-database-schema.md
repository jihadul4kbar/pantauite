# Database Schema
## PantauITE - IT Service Management Platform

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-06  
**Last Updated:** 2026-04-06  
**Database:** MySQL 8.0+  
**Charset:** utf8mb4  
**Collation:** utf8mb4_unicode_ci

---

# DATABASE OVERVIEW

## Entity Relationship Diagram (Simplified)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│   users      │       │  departments │       │   categories │
│              │       │              │       │              │
│ id (PK)      │◄──┐   │ id (PK)      │   ┌──►│ id (PK)      │
│ name         │   │   │ name         │   │   │ name         │
│ email        │   │   │ description  │   │   │ parent_id    │
│ password     │   │   │ manager_id   │   │   │ description  │
│ role_id (FK) │   │   │ created_at   │   │   │ type         │
│ dept_id (FK) │   │   └──────────────┘   │   │ created_at   │
│ created_at   │                          │   └──────────────┘
└──────┬───────┘                          │            │
       │                                  │            │
       │        ┌──────────────┐          │            │
       │        │    roles     │          │            │
       │        │              │          │            │
       │        │ id (PK)      │          │            │
       └───────►│ name         │          │            │
                │ display_name │          │            │
                │ description  │          │            │
                │ permissions  │          │            │
                │ created_at   │          │            │
                └──────────────┘          │            │
                                          │            │
       ┌──────────────────────────────────┘            │
       │                                              │
       ▼                                              ▼
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│   tickets    │       │ ticket_      │       │kb_categories │
│              │       │  comments    │       │              │
│ id (PK)      │       │              │       └──────────────┘
│ ticket_number│       │ id (PK)      │            ▲
│ subject      │       │ ticket_id(FK)│            │
│ description  │       │ user_id (FK) │            │
│ status       │       │ comment      │            │
│ priority     │       │ attachments  │            │
│ user_id (FK) │       │ created_at   │            │
│ assignee_id  │       └──────────────┘            │
│ dept_id (FK) │                                   │
│ category_id  │                                   │
│ sla_deadline │                                   │
│ resolved_at  │                                   │
│ created_at   │       ┌──────────────┐            │
└──────┬───────┘       │  kb_articles │            │
       │               │              │            │
       ▼               │ id (PK)      │────────────┘
┌──────────────┐       │ category_id  │
│    sla_      │       │ title        │
│   policies   │       │ content      │
│              │       │ tags         │
│ id (PK)      │       │ status       │
│ priority     │       │ author_id    │
│ response_time│       │ views        │
│ resolve_time │       │ version      │
│ is_24_7      │       │ created_at   │
│ created_at   │       └──────────────┘
└──────────────┘


┌──────────────┐       ┌──────────────┐
│    assets    │       │asset_lifecyle│
│              │       │   _logs      │
│ id (PK)      │       │              │
│ asset_code   │       │ id (PK)      │
│ asset_type   │       │ asset_id(FK) │
│ name         │       │ from_status  │
│ brand        │       │ to_status    │
│ model        │       │ notes        │
│ serial_number│       │ user_id (FK) │
│ specs        │       │ changed_at   │
│ status       │       └──────────────┘
│ location     │
│ user_id (FK) │       ┌──────────────┐
│ dept_id (FK) │       │maintenance_  │
│ purchase_info│       │   logs       │
│ warranty_end │       │              │
│ price        │       │ id (PK)      │
│ depreciated  │       │ asset_id(FK) │
│ created_at   │       │ description  │
└──────────────┘       │ cost         │
                       │ performed_by │
┌──────────────┐       │ date         │
│  asset_      │       └──────────────┘
│  documents   │
│              │
│ id (PK)      │
│ asset_id(FK) │
│ filename     │
│ file_path    │
│ file_size    │
│ uploaded_by  │
│ uploaded_at  │
└──────────────┘

┌──────────────┐
│   vendors    │
│              │
│ id (PK)      │
│ name         │
│ code         │
│ contact      │
│ email        │
│ phone        │
│ created_at   │
└──────────────┘
```

---

# TABLE DEFINITIONS

## 1. USERS

**Purpose:** User accounts untuk semua system users

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    department_id BIGINT UNSIGNED NULL,
    employee_id VARCHAR(50) NULL COMMENT 'Employee ID/NIP',
    avatar VARCHAR(255) NULL COMMENT 'Profile photo path',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete',
    
    CONSTRAINT fk_users_roles FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    CONSTRAINT fk_users_departments FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    
    INDEX idx_users_role (role_id),
    INDEX idx_users_department (department_id),
    INDEX idx_users_status (status),
    INDEX idx_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 2. ROLES

**Purpose:** Role definitions dengan permissions dalam JSON

```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE COMMENT 'system name: super_admin, it_manager, it_staff, end_user',
    display_name VARCHAR(100) NOT NULL COMMENT 'display name: Super Admin, IT Manager',
    description TEXT NULL,
    permissions JSON NOT NULL COMMENT 'JSON array of permissions',
    is_system_role BOOLEAN DEFAULT FALSE COMMENT 'cannot be deleted',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Default Roles:**
```json
{
  "super_admin": {
    "permissions": ["*"]
  },
  "it_manager": {
    "permissions": [
      "manage-users",
      "manage-tickets",
      "manage-assets",
      "manage-kb",
      "manage-sla",
      "manage-departments",
      "view-reports",
      "view-dashboard"
    ]
  },
  "it_staff": {
    "permissions": [
      "view-tickets",
      "update-tickets",
      "manage-assets",
      "manage-kb",
      "view-reports",
      "view-dashboard"
    ]
  },
  "end_user": {
    "permissions": [
      "create-tickets",
      "view-own-tickets",
      "view-kb"
    ]
  }
}
```

---

## 3. DEPARTMENTS

**Purpose:** Department/organizational unit structure

```sql
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE COMMENT 'department code: IT, HR, FINANCE',
    description TEXT NULL,
    manager_id BIGINT UNSIGNED NULL COMMENT 'department head',
    parent_id BIGINT UNSIGNED NULL COMMENT 'for hierarchical structure',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_departments_manager FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_departments_parent FOREIGN KEY (parent_id) REFERENCES departments(id) ON DELETE SET NULL,
    
    INDEX idx_departments_code (code),
    INDEX idx_departments_parent (parent_id),
    INDEX idx_departments_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 4. TICKET CATEGORIES

**Purpose:** Kategori untuk ticket classification

```sql
CREATE TABLE ticket_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL COMMENT 'for sub-categories',
    icon VARCHAR(50) NULL COMMENT 'icon class atau emoji',
    color VARCHAR(7) NULL COMMENT 'hex color code',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_tc_parent FOREIGN KEY (parent_id) REFERENCES ticket_categories(id) ON DELETE SET NULL,
    
    INDEX idx_tc_slug (slug),
    INDEX idx_tc_parent (parent_id),
    INDEX idx_tc_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. TICKETS

**Purpose:** Core ticket records

```sql
CREATE TABLE tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(50) NOT NULL UNIQUE COMMENT 'format: TKT-YYYY-NNNN',
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed', 'reopened') DEFAULT 'open',
    priority ENUM('critical', 'high', 'medium', 'low') DEFAULT 'medium',
    
    -- Relationships
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'ticket creator',
    assignee_id BIGINT UNSIGNED NULL COMMENT 'assigned IT staff',
    department_id BIGINT UNSIGNED NULL COMMENT 'responsible department',
    category_id BIGINT UNSIGNED NULL,
    
    -- SLA
    sla_policy_id BIGINT UNSIGNED NULL,
    sla_deadline TIMESTAMP NULL COMMENT 'when SLA breaches',
    sla_breached BOOLEAN DEFAULT FALSE,
    sla_breached_at TIMESTAMP NULL,
    paused_at TIMESTAMP NULL COMMENT 'SLA paused when waiting for user',
    
    -- Timestamps
    resolved_at TIMESTAMP NULL,
    closed_at TIMESTAMP NULL,
    first_response_at TIMESTAMP NULL COMMENT 'first response time tracking',
    
    -- Additional
    source ENUM('web', 'email', 'phone', 'walk-in') DEFAULT 'web',
    resolution_notes TEXT NULL,
    satisfaction_rating TINYINT NULL COMMENT '1-5 stars',
    satisfaction_feedback TEXT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Foreign Keys
    CONSTRAINT fk_tickets_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_tickets_assignee FOREIGN KEY (assignee_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_tickets_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    CONSTRAINT fk_tickets_category FOREIGN KEY (category_id) REFERENCES ticket_categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_tickets_sla FOREIGN KEY (sla_policy_id) REFERENCES sla_policies(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_tickets_number (ticket_number),
    INDEX idx_tickets_status (status),
    INDEX idx_tickets_priority (priority),
    INDEX idx_tickets_user (user_id),
    INDEX idx_tickets_assignee (assignee_id),
    INDEX idx_tickets_department (department_id),
    INDEX idx_tickets_category (category_id),
    INDEX idx_tickets_created (created_at),
    INDEX idx_tickets_sla_deadline (sla_deadline),
    INDEX idx_tickets_status_priority (status, priority),
    INDEX idx_tickets_user_status (user_id, status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 6. TICKET COMMENTS

**Purpose:** Comments dan updates pada tickets

```sql
CREATE TABLE ticket_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'comment author',
    comment TEXT NOT NULL,
    is_internal BOOLEAN DEFAULT FALSE COMMENT 'internal note (not visible to end user)',
    is_solution BOOLEAN DEFAULT FALSE COMMENT 'marked as solution',
    
    -- Attachments
    attachments JSON NULL COMMENT 'array of file info: [{filename, path, size}]',
    
    -- Audit
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_tc_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    CONSTRAINT fk_tc_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_tc_ticket (ticket_id),
    INDEX idx_tc_user (user_id),
    INDEX idx_tc_internal (is_internal),
    INDEX idx_tc_solution (is_solution),
    INDEX idx_tc_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 7. TICKET AUDIT LOG

**Purpose:** Track semua perubahan pada tickets (audit trail)

```sql
CREATE TABLE ticket_audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NULL COMMENT 'user who made the change',
    action VARCHAR(50) NOT NULL COMMENT 'created, status_changed, priority_changed, assigned, etc',
    
    -- Changes
    old_values JSON NULL COMMENT 'previous values',
    new_values JSON NULL COMMENT 'new values',
    
    -- Additional context
    notes TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_tal_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    CONSTRAINT fk_tal_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_tal_ticket (ticket_id),
    INDEX idx_tal_user (user_id),
    INDEX idx_tal_action (action),
    INDEX idx_tal_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 8. TICKET ATTACHMENTS

**Purpose:** File attachments untuk tickets

```sql
CREATE TABLE ticket_attachments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_id BIGINT UNSIGNED NULL,
    comment_id BIGINT UNSIGNED NULL,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT UNSIGNED NOT NULL COMMENT 'size in bytes',
    mime_type VARCHAR(100) NOT NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_ta_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    CONSTRAINT fk_ta_comment FOREIGN KEY (comment_id) REFERENCES ticket_comments(id) ON DELETE CASCADE,
    CONSTRAINT fk_ta_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_ta_ticket (ticket_id),
    INDEX idx_ta_comment (comment_id),
    INDEX idx_ta_mime (mime_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 9. SLA POLICIES

**Purpose:** SLA definitions per priority level

```sql
CREATE TABLE sla_policies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'policy name',
    priority ENUM('critical', 'high', 'medium', 'low') NOT NULL UNIQUE,
    
    -- Time in minutes
    response_time_minutes INT UNSIGNED NOT NULL COMMENT 'max time for first response',
    resolution_time_minutes INT UNSIGNED NOT NULL COMMENT 'max time to resolve',
    
    -- Business hours
    use_business_hours BOOLEAN DEFAULT TRUE COMMENT 'if false, 24/7 SLA',
    business_hours_start TIME DEFAULT '08:00:00',
    business_hours_end TIME DEFAULT '17:00:00',
    business_days JSON NOT NULL DEFAULT '[1,2,3,4,5]' COMMENT '1=Mon, 7=Sun',
    
    -- Escalation
    escalation_enabled BOOLEAN DEFAULT FALSE,
    escalation_threshold_minutes INT UNSIGNED NULL COMMENT 'warn before breach',
    escalation_user_id BIGINT UNSIGNED NULL COMMENT 'escalate to this user',
    
    is_active BOOLEAN DEFAULT TRUE,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_sla_escalation_user FOREIGN KEY (escalation_user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_sla_priority (priority),
    INDEX idx_sla_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Default SLA Policies:**
```
| Priority | Response Time | Resolution Time | Business Hours |
|----------|---------------|-----------------|----------------|
| critical | 15 minutes    | 240 minutes (4h)| 24/7           |
| high     | 60 minutes    | 480 minutes (8h)| Yes (8-17)     |
| medium   | 240 minutes   | 1440 minutes    | Yes (8-17)     |
| low      | 480 minutes   | 4320 minutes    | Yes (8-17)     |
```

---

## 10. KNOWLEDGE BASE CATEGORIES

**Purpose:** Kategori untuk knowledge base articles

```sql
CREATE TABLE kb_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    parent_id BIGINT UNSIGNED NULL COMMENT 'for sub-categories',
    icon VARCHAR(50) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_kbc_parent FOREIGN KEY (parent_id) REFERENCES kb_categories(id) ON DELETE SET NULL,
    
    INDEX idx_kbc_slug (slug),
    INDEX idx_kbc_parent (parent_id),
    INDEX idx_kbc_active (is_active),
    INDEX idx_kbc_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 11. KNOWLEDGE BASE ARTICLES

**Purpose:** Knowledge base articles dengan versioning

```sql
CREATE TABLE kb_articles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_number VARCHAR(50) NOT NULL UNIQUE COMMENT 'format: KB-NNNN',
    category_id BIGINT UNSIGNED NOT NULL,
    
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    summary TEXT NULL COMMENT 'short description/excerpt',
    
    -- Organization
    tags JSON NULL COMMENT 'array of tags',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'pinned/highlighted',
    is_internal BOOLEAN DEFAULT FALSE COMMENT 'IT staff only',
    
    -- Versioning (simplified)
    version INT UNSIGNED DEFAULT 1,
    changelog TEXT NULL COMMENT 'what changed in latest version',
    
    -- Status
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    
    -- Authorship
    author_id BIGINT UNSIGNED NOT NULL,
    reviewed_by BIGINT UNSIGNED NULL COMMENT 'reviewer/approver',
    reviewed_at TIMESTAMP NULL,
    
    -- Analytics
    views INT UNSIGNED DEFAULT 0,
    helpful_votes INT UNSIGNED DEFAULT 0,
    not_helpful_votes INT UNSIGNED DEFAULT 0,
    
    -- SEO
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords JSON NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_kb_category FOREIGN KEY (category_id) REFERENCES kb_categories(id) ON DELETE RESTRICT,
    CONSTRAINT fk_kb_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_kb_reviewer FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_kb_number (article_number),
    INDEX idx_kb_category (category_id),
    INDEX idx_kb_slug (slug),
    INDEX idx_kb_status (status),
    INDEX idx_kb_author (author_id),
    INDEX idx_kb_featured (is_featured),
    INDEX idx_kb_internal (is_internal),
    INDEX idx_kb_published (published_at),
    FULLTEXT idx_kb_search (title, content, summary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 12. KB ARTICLE VOTES

**Purpose:** Track user feedback pada KB articles

```sql
CREATE TABLE kb_article_votes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    vote_type ENUM('helpful', 'not_helpful') NOT NULL,
    feedback TEXT NULL COMMENT 'optional feedback',
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Prevent duplicate votes
    UNIQUE KEY unique_article_user_vote (article_id, user_id),
    
    CONSTRAINT fk_kbav_article FOREIGN KEY (article_id) REFERENCES kb_articles(id) ON DELETE CASCADE,
    CONSTRAINT fk_kbav_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_kbav_article (article_id),
    INDEX idx_kbav_user (user_id),
    INDEX idx_kbav_type (vote_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 13. ASSETS

**Purpose:** IT asset inventory (hardware, software, network)

```sql
CREATE TABLE assets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'format: AST-{TYPE}-NNNN',
    asset_type ENUM('hardware', 'software', 'network') NOT NULL,
    
    -- Basic Info
    name VARCHAR(255) NOT NULL,
    brand VARCHAR(100) NULL,
    model VARCHAR(100) NULL,
    serial_number VARCHAR(100) NULL,
    part_number VARCHAR(100) NULL,
    
    -- Specifications (flexible per type)
    specs JSON NULL COMMENT '{cpu, ram, storage, os, etc}',
    
    -- Status
    status ENUM('procurement', 'inventory', 'deployed', 'maintenance', 'retired', 'disposed') DEFAULT 'inventory',
    condition ENUM('new', 'good', 'fair', 'poor', 'broken') DEFAULT 'new',
    
    -- Assignment
    assigned_to_user_id BIGINT UNSIGNED NULL,
    assigned_to_department_id BIGINT UNSIGNED NULL,
    assigned_at TIMESTAMP NULL,
    assigned_notes TEXT NULL,
    
    -- Location
    location VARCHAR(255) NULL COMMENT 'building, floor, room',
    
    -- Purchase Information
    vendor_id BIGINT UNSIGNED NULL,
    vendor_name VARCHAR(255) NULL,
    purchase_order_number VARCHAR(100) NULL,
    purchase_date DATE NULL,
    price DECIMAL(15, 2) NULL,
    currency VARCHAR(3) DEFAULT 'IDR',
    
    -- Warranty
    warranty_start DATE NULL,
    warranty_end DATE NULL,
    warranty_provider VARCHAR(255) NULL,
    warranty_notes TEXT NULL,
    
    -- Depreciation
    depreciation_method ENUM('straight_line', 'declining_balance', 'none') DEFAULT 'straight_line',
    useful_life_years INT UNSIGNED NULL,
    depreciated_value DECIMAL(15, 2) NULL,
    depreciation_start_date DATE NULL,
    
    -- End of Life
    disposal_date DATE NULL,
    disposal_reason TEXT NULL,
    disposal_value DECIMAL(15, 2) NULL,
    
    -- Additional
    notes TEXT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Foreign Keys
    CONSTRAINT fk_assets_vendor FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE SET NULL,
    CONSTRAINT fk_assets_user FOREIGN KEY (assigned_to_user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_assets_department FOREIGN KEY (assigned_to_department_id) REFERENCES departments(id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_assets_code (asset_code),
    INDEX idx_assets_type (asset_type),
    INDEX idx_assets_status (status),
    INDEX idx_assets_assigned_user (assigned_to_user_id),
    INDEX idx_assets_assigned_dept (assigned_to_department_id),
    INDEX idx_assets_warranty_end (warranty_end),
    INDEX idx_assets_condition (condition),
    INDEX idx_assets_location (location(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 14. ASSET LIFECYCLE LOGS

**Purpose:** Track semua perubahan status asset

```sql
CREATE TABLE asset_lifecycle_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_id BIGINT UNSIGNED NOT NULL,
    from_status VARCHAR(50) NOT NULL,
    to_status VARCHAR(50) NOT NULL,
    reason TEXT NULL,
    notes TEXT NULL,
    changed_by BIGINT UNSIGNED NOT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_all_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    CONSTRAINT fk_all_user FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_all_asset (asset_id),
    INDEX idx_all_user (changed_by),
    INDEX idx_all_status (from_status, to_status),
    INDEX idx_all_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 15. MAINTENANCE LOGS

**Purpose:** Record maintenance activities untuk assets

```sql
CREATE TABLE maintenance_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_id BIGINT UNSIGNED NOT NULL,
    maintenance_type ENUM('preventive', 'corrective', 'upgrade', 'inspection') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    performed_by BIGINT UNSIGNED NOT NULL COMMENT 'technician',
    vendor_name VARCHAR(255) NULL COMMENT 'external vendor jika ada',
    cost DECIMAL(15, 2) NULL,
    currency VARCHAR(3) DEFAULT 'IDR',
    start_date DATETIME NOT NULL,
    end_date DATETIME NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'completed',
    
    -- Attachments
    attachments JSON NULL,
    
    -- Result
    outcome TEXT NULL,
    recommendations TEXT NULL,
    next_maintenance_date DATE NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_ml_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    CONSTRAINT fk_ml_user FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_ml_asset (asset_id),
    INDEX idx_ml_user (performed_by),
    INDEX idx_ml_type (maintenance_type),
    INDEX idx_ml_status (status),
    INDEX idx_ml_dates (start_date, end_date),
    INDEX idx_ml_next_maintenance (next_maintenance_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 16. ASSET DOCUMENTS

**Purpose:** Documents terkait assets (manual, invoice, warranty, dll)

```sql
CREATE TABLE asset_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    asset_id BIGINT UNSIGNED NOT NULL,
    document_type ENUM('invoice', 'warranty', 'manual', 'certificate', 'contract', 'other') NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT UNSIGNED NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    description TEXT NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    expiry_date DATE NULL COMMENT 'for warranties, contracts, dll',
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    CONSTRAINT fk_ad_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    CONSTRAINT fk_ad_user FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_ad_asset (asset_id),
    INDEX idx_ad_type (document_type),
    INDEX idx_ad_expiry (expiry_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 17. VENDORS

**Purpose:** Vendor/supplier information untuk assets dan maintenance

```sql
CREATE TABLE vendors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    contact_person VARCHAR(100) NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(50) NULL,
    address TEXT NULL,
    website VARCHAR(255) NULL,
    vendor_type ENUM('hardware', 'software', 'network', 'maintenance', 'other') NULL,
    is_active BOOLEAN DEFAULT TRUE,
    notes TEXT NULL,
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_vendors_code (code),
    INDEX idx_vendors_type (vendor_type),
    INDEX idx_vendors_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 18. REPORT RUNS

**Purpose:** Log setiap kali report di-generate

```sql
CREATE TABLE report_runs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(50) NOT NULL,
    filters JSON NULL COMMENT 'filters used untuk this run',
    format ENUM('pdf', 'excel', 'csv') NOT NULL,
    file_path VARCHAR(500) NULL,
    file_size INT UNSIGNED NULL,
    generated_by BIGINT UNSIGNED NOT NULL,
    generation_time_ms INT UNSIGNED NULL COMMENT 'how long it took',
    
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_rr_user FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE RESTRICT,
    
    INDEX idx_rr_user (generated_by),
    INDEX idx_rr_format (format),
    INDEX idx_rr_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 19. SESSIONS

**Purpose:** Laravel session storage (required untuk database session driver)

```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT UNSIGNED NOT NULL,
    
    INDEX idx_sessions_user (user_id),
    INDEX idx_sessions_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 20. CACHE

**Purpose:** Laravel cache storage

```sql
CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 21. CACHE LOCKS

```sql
CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 22. JOBS

**Purpose:** Laravel queue jobs

```sql
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    
    INDEX idx_jobs_queue (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 23. JOB BATCHES

```sql
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids JSON NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 24. FAILED JOBS

```sql
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 25. PASSWORD RESET TOKENS

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

# SUMMARY

## Table Count by Module

| Module | Tables | Count |
|--------|--------|-------|
| **User Management** | users, roles, departments | 3 |
| **Ticketing** | tickets, ticket_comments, ticket_audit_logs, ticket_attachments, ticket_categories | 5 |
| **SLA** | sla_policies | 1 |
| **Knowledge Base** | kb_categories, kb_articles, kb_article_votes | 3 |
| **Asset Management** | assets, asset_lifecycle_logs, maintenance_logs, asset_documents, vendors | 5 |
| **Reporting** | report_runs | 1 |
| **Laravel Core** | sessions, cache, cache_locks, jobs, job_batches, failed_jobs, password_reset_tokens | 7 |
| **TOTAL** | | **25** |

## Estimated Data Volume

| Table | Year 1 | Year 3 | Year 5 |
|-------|--------|--------|--------|
| users | 200 | 500 | 1,000 |
| tickets | 5,000 | 15,000 | 30,000 |
| ticket_comments | 15,000 | 50,000 | 100,000 |
| assets | 2,000 | 5,000 | 10,000 |
| kb_articles | 500 | 1,500 | 3,000 |
| maintenance_logs | 3,000 | 10,000 | 20,000 |
| audit_logs | 50,000 | 150,000 | 300,000 |

---

# INDEXING STRATEGY

## Critical Indexes

### High-Traffic Queries
1. `tickets(status, priority, created_at)` - Dashboard queries
2. `tickets(user_id, status)` - User ticket list
3. `tickets(assignee_id, status)` - Staff workload
4. `tickets(sla_deadline)` - SLA breach checks
5. `kb_articles(status, published_at)` - Published articles
6. `assets(status, asset_type)` - Asset inventory

### Full-Text Search
1. `kb_articles(title, content, summary)` - KB search

### Composite Indexes
1. `tickets(department_id, status, created_at)` - Department reports
2. `tickets(category_id, priority, status)` - Category analysis
3. `assets(assigned_to_user_id, status)` - User assets
4. `maintenance_logs(asset_id, start_date)` - Asset maintenance history

---

# FOREIGN KEY CONSTRAINTS STRATEGY

## ON DELETE Actions

| Relationship | Action | Rationale |
|-------------|--------|-----------|
| user → department | SET NULL | Department deleted, user remains |
| user → role | RESTRICT | Cannot delete role with users |
| ticket → user | RESTRICT | Cannot delete user dengan tickets |
| ticket → assignee | SET NULL | Unassign jika staff deleted |
| ticket → category | SET NULL | Category deleted, ticket remains |
| ticket_comment → ticket | CASCADE | Delete comments dengan ticket |
| asset → user | SET NULL | Unassign jika user deleted |
| kb_article → category | RESTRICT | Cannot delete category dengan articles |
| audit_log → ticket | CASCADE | Audit trail dengan ticket |

---

# DATA SEEDING PLAN

## Seeders Required

1. **RoleSeeder** - Default roles dengan permissions
2. **DepartmentSeeder** - Initial departments
3. **SlaPolicySeeder** - Default SLA policies
4. **UserSeeder** - Super Admin default user
5. **TicketCategorySeeder** - Default ticket categories
6. **KbCategorySeeder** - Default KB categories

---

# MIGRATION ORDER

```
1.  create_roles_table
2.  create_departments_table
3.  create_users_table
4.  create_ticket_categories_table
5.  create_sla_policies_table
6.  create_tickets_table
7.  create_ticket_comments_table
8.  create_ticket_audit_logs_table
9.  create_ticket_attachments_table
10. create_kb_categories_table
11. create_kb_articles_table
12. create_kb_article_votes_table
13. create_vendors_table
14. create_assets_table
15. create_asset_lifecycle_logs_table
16. create_maintenance_logs_table
17. create_asset_documents_table
18. create_report_runs_table
19. create_sessions_table
20. create_cache_table
21. create_cache_locks_table
22. create_jobs_table
23. create_job_batches_table
24. create_failed_jobs_table
25. create_password_reset_tokens_table
```

---

# APPENDIX

## A. Enum Values Reference

### Ticket Status
- `open` - Baru dibuat, belum ditangani
- `in_progress` - Sedang ditangani
- `resolved` - Issue resolved, menunggu konfirmasi
- `closed` - Selesai, tidak ada tindakan lanjutan
- `reopened` - Issue muncul lagi setelah resolved

### Ticket Priority
- `critical` - Sistem down, impact luas, immediate action required
- `high` - Major feature broken, urgent tetapi ada workaround
- `medium` - Partial issue, can wait for normal business hours
- `low` - Minor issue, cosmetic, enhancement request

### Asset Type
- `hardware` - PC, laptop, server, printer, physical devices
- `software` - Licenses, subscriptions, applications
- `network` - Router, switch, access point, firewall

## B. Custom Format Patterns

| Code | Pattern | Example |
|------|---------|---------|
| Ticket Number | `TKT-YYYY-NNNN` | `TKT-2026-0001` |
| Asset Code | `AST-{TYPE}-NNNN` | `AST-HW-0001`, `AST-SW-0042` |
| KB Article | `KB-NNNN` | `KB-0001` |
| Department Code | `{UPPERCASE}` | `IT`, `HR`, `FIN` |

## C. Storage Calculation

### Average Row Size
| Table | Avg Row Size | 1M Rows |
|-------|-------------|---------|
| users | ~500 bytes | ~500 MB |
| tickets | ~1 KB | ~1 GB |
| ticket_comments | ~2 KB | ~2 GB |
| kb_articles | ~5 KB | ~5 GB |
| assets | ~1 KB | ~1 GB |

### Total Estimated Storage
- **Year 1:** ~10 GB
- **Year 3:** ~30 GB
- **Year 5:** ~60 GB

*Include 30% buffer untuk indexes dan overhead*
