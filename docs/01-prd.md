# Product Requirement Document (PRD)
## PantauITE - IT Service Management Platform

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-06  
**Last Updated:** 2026-04-06

---

## 1. Problem Statement

Departemen IT membutuhkan sistem terintegrasi untuk:
- Mengelola ticket trouble dan request dari user secara terstruktur
- Mendokumentasikan knowledge base (SOP, troubleshooting guides) untuk self-service
- Melacak inventaris asset IT (hardware, software, network) dengan lifecycle
- Memantau kepatuhan SLA per priority level dengan escalation
- Menghasilkan laporan untuk manajemen dan audit

## 2. Goals & Objectives

### 2.1 Primary Goals
- [ ] Menyediakan ticketing system yang mudah digunakan dengan workflow jelas
- [ ] Centralized knowledge base untuk dokumentasi IT dan self-service
- [ ] Asset tracking dengan lifecycle management dan document attachment
- [ ] SLA monitoring dengan escalation dan reporting
- [ ] Generate laporan otomatis (PDF & Excel) untuk berbagai aspek
- [ ] Vendor management untuk asset procurement dan maintenance

### 2.2 Success Metrics
- Ticket resolution time < SLA target (90% compliance)
- Asset accuracy > 95%
- Knowledge base coverage untuk common issues (>80%)
- SLA compliance rate tracking dan reporting
- User adoption rate > 80% dalam 30 hari

## 3. User Roles

### 3.1 Role Hierarchy
1. **Super Admin** - Full system access dan user management
2. **IT Manager** - Manage all modules, view reports, configure SLA, manage team
3. **IT Staff** - Handle tickets, manage assets, create KB articles
4. **End User** - Submit tickets, view ticket status, browse KB (read-only)

### 3.2 Department Structure
- Multi-department support
- User terasosiasi dengan department masing-masing
- Ticket dapat diassign ke department tertentu
- Cross-department visibility untuk IT Manager

## 4. Functional Requirements

### 4.1 Ticketing System

#### 4.1.1 Ticket Workflow
```
Open → In Progress → Resolved → Closed
         ↓
      Reopened (jika issue belum tuntas)
```

#### 4.1.2 Ticket Features
- **Ticket Creation**: Submit ticket dengan detail (subject, description, category, priority, attachment)
- **Assignment**: Manual assign ke IT staff/department
- **Priority Levels**: Critical, High, Medium, Low
- **Status Tracking**: Real-time status update
- **Comments & Updates**: Thread comments pada ticket (internal notes untuk IT staff)
- **Search & Filter**: Filter by status, priority, department, date range, source
- **Ticket Numbering**: Auto-generated (e.g., TKT-2026-0001)
- **Source Tracking**: Track ticket source (web, phone, walk-in, email)

#### 4.1.3 Ticket Validation
- Required fields: subject, description, category, priority
- Attachment max 5MB per file, max 10 files per ticket
- SLA timer mulai saat ticket created

#### 4.1.4 Ticket Attachments
- Upload files saat create ticket atau add comment
- Supported types: Images (jpg, png, gif), Documents (pdf, doc, xls), Logs (txt, log)
- Attachments disimpan dengan metadata (filename, size, type, uploader)
- Downloadable untuk authorized users

### 4.2 Knowledge Base

#### 4.2.1 Structure
- **Categories**: Hierarchical (parent → child categories)
- **Articles**: Rich text content dengan attachment support
- **Tags**: Array of tags untuk easier discovery dan filtering
- **Visibility**: 
  - Public (all users can view)
  - Internal (IT staff only) untuk sensitive procedures

#### 4.2.2 Article Features
- CRUD articles dengan rich text editor
- **Version history**: Track changes dengan version numbering
  - Each edit increments version number
  - Changelog mencatat what changed dan when
  - Rollback capability untuk restore previous versions
- **Tags**: Multiple tags per article untuk better organization
- View counter untuk popularity tracking
- **User feedback**: Helpful/Not Helpful voting
  - Collect feedback untuk improve article quality
  - Display voting ratio untuk quality indicator
- Related articles suggestion
- Search functionality (title, content, tags) dengan full-text search
- Draft/Published/Archived status
- **Review process**: Articles dapat di-review dan approved oleh IT Manager

#### 4.2.3 Article Analytics
- Track views per article
- Track helpful vs not-helpful votes
- Identify outdated articles (low views, negative feedback)
- Most searched terms reporting

### 4.3 Asset Management

#### 4.3.1 Asset Types
1. **Hardware**: PC, Laptop, Server, Printer, Network devices
2. **Software**: Licenses, subscriptions, installations
3. **Network**: Router, Switch, Access Point, Firewall

#### 4.3.2 Lifecycle Stages
```
Procurement → Inventory → Deployed → Maintenance → Retired → Disposed
```

#### 4.3.3 Asset Features
- **Asset Tracking**: Unique asset code (auto-generated, format: AST-{TYPE}-NNNN)
- **Details**: Name, type, brand, model, serial number, part number, specs
- **Location**: Building, floor, room
- **Assignment**: Assigned to user/department/location
- **Purchase Info**: Vendor, purchase date, price, warranty period
- **Vendor Management**: 
  - Maintain vendor database (name, contact, email, phone, address)
  - Track vendor performance via maintenance logs
  - Vendor linkage untuk warranty claims
- **Lifecycle History**: Track status changes dengan timestamp dan reason
- **Depreciation**: Calculate current value (straight-line atau declining balance method)
- **Maintenance Log**: Record maintenance activities dengan cost tracking
- **Warranty Tracking**: 
  - Warranty start dan end dates
  - Warranty provider informasi
  - Alert jika warranty akan expire (30 days warning)

#### 4.3.4 Asset Documents
- Upload dan attach documents ke assets
- Document types: Invoice, Warranty certificate, User manual, Contract, Certificate
- Track document metadata (type, upload date, uploader, expiry date)
- Max file size: 5MB per file
- Downloadable untuk authorized users
- Expiry date tracking untuk warranties dan contracts

#### 4.3.5 Vendor Management
- Maintain vendor list untuk asset procurement
- Vendor details: contact person, email, phone, address, website, type
- Link vendors ke assets untuk procurement tracking
- Link vendors ke maintenance logs (external maintenance provider)
- Track vendor type (hardware, software, network, maintenance, other)
- Vendor active/inactive status

### 4.4 SLA Management

#### 4.4.1 SLA Definition per Priority
| Priority | Response Time | Resolution Time | Business Hours |
|----------|--------------|-----------------|----------------|
| Critical | 15 minutes   | 4 hours         | 24/7           |
| High     | 1 hour       | 8 hours         | Business hours |
| Medium   | 4 hours      | 24 hours        | Business hours |
| Low      | 8 hours      | 72 hours        | Business hours |

*Configurable by IT Manager*

#### 4.4.2 SLA Tracking
- **SLA Timer**: Start saat ticket created
- **First Response Tracking**: Track ketika IT staff pertama kali merespons
- **Breach Detection**: Flag ticket yang mendekati/exceed SLA
- **SLA Status**: On Track, At Risk, Breached
- **Pause SLA**: Saat ticket waiting for customer (paused_at timestamp)
- **Escalation** (Configurable):
  - Enable/disable escalation per SLA policy
  - Warning threshold (e.g., warn 30 minutes before breach)
  - Escalate to designated user (manager atau senior staff)
  - Escalation logged ke ticket audit trail
- **SLA Report**: Compliance rate per period, breach analysis

#### 4.4.3 Escalation Process
```
SLA At Risk (threshold reached) → Warning notification
        ↓
SLA Breached → Escalate to designated user
        ↓
Escalated ticket flagged untuk reporting
```

*Note: Escalation adalah in-app notification saja (no email di v1.0)*

### 4.5 Reporting

#### 4.5.1 Report Types
1. **Ticket Reports**:
   - Ticket summary (daily/weekly/monthly)
   - Ticket by status/priority/category/source
   - Average resolution time
   - SLA compliance report dengan breach analysis
   - Staff performance report (tickets handled, avg resolution time)
   - Escalation report (tickets escalated, escalation reasons)

2. **Asset Reports**:
   - Asset inventory list
   - Asset by type/status/department/vendor
   - Warranty expiry report (assets expiring in next 30/60/90 days)
   - Depreciation report (current value calculation)
   - Asset lifecycle report (transitions history)
   - Maintenance cost report (total maintenance cost per asset)

3. **Knowledge Base Reports**:
   - Article views statistics
   - Most viewed articles (top 10, top 20)
   - Articles with negative feedback (improvement candidates)
   - Articles by category/status
   - Search terms analytics (most searched terms)

#### 4.5.2 Report Features
- Generate reports on-demand dengan custom filters
- Export includes metadata (generated by, date, filters used)
- Report history tracking (who generated, when, format)

#### 4.5.3 Export Formats
- PDF (formatted report dengan header, footer, charts)
- Excel/CSV (raw data untuk analysis)

#### 4.5.4 Dashboard
- Ticket statistics (open, in progress, overdue, resolved)
- SLA compliance percentage
- Asset summary (total, deployed, in maintenance)
- Recent tickets
- Top KB articles
- Quick action buttons

### 4.6 User Management

#### 4.6.1 User Features
- Invite-only registration (admin creates users)
- Profile management (name, email, phone, employee ID)
- Department assignment
- Role-based access control
- Active/Inactive status
- Last login tracking
- Force password change on first login

## 5. Non-Functional Requirements

### 5.1 Performance
- Page load time < 3 seconds (95th percentile)
- Support 100+ concurrent users
- Handle 10,000+ tickets tanpa degradation
- Handle 5,000+ assets tanpa performance issues
- Database queries < 100ms untuk common operations

### 5.2 Security
- Password hashing (bcrypt, min 8 chars, complexity required)
- CSRF protection
- XSS prevention
- SQL injection prevention
- Role-based authorization
- Audit logging untuk critical actions
- Session timeout setelah 2 hours inactive

### 5.3 Scalability
- Modular architecture (easy to add features)
- Database indexing untuk large datasets
- Queue untuk background jobs (report generation)
- Vertical scaling ready

### 5.4 Usability
- Responsive design (desktop, tablet, mobile)
- Intuitive UI dengan minimal training
- Consistent design system (Tailwind CSS)
- Error messages yang jelas dan helpful
- Breadcrumb navigation

### 5.5 Reliability
- Daily automated backups
- Error logging dan monitoring
- Graceful error handling
- Data validation di frontend dan backend
- Soft deletes untuk critical entities

## 6. Technical Constraints

### 6.1 Technology Stack
- **Backend**: Laravel 13 (PHP 8.3+)
- **Frontend**: Vite + Tailwind CSS 4 + Alpine.js
- **Database**: MySQL 8.0+
- **Queue**: Database driver
- **No real-time WebSocket** (polling untuk status updates)

### 6.2 Limitations
- No multi-tenant support
- No external API integration (v1.0)
- No real-time updates (scheduled polling only)
- Single language (English UI)
- No email notifications (v1.0)

## 7. Future Enhancements (Out of Scope v1.0)

- [ ] Email notifications (ticket updates, SLA warnings)
- [ ] External API untuk integration dengan other systems
- [ ] Mobile app (iOS/Android)
- [ ] Real-time WebSocket updates
- [ ] AI-powered ticket categorization
- [ ] Chatbot untuk KB search
- [ ] Multi-language support
- [ ] Advanced analytics dengan Machine Learning
- [ ] Saved report configurations dengan auto-scheduling
- [ ] Full version history untuk KB articles (dengan diff view)

## 8. Success Criteria

### 8.1 Launch Criteria
- All functional requirements implemented
- All user roles tested dan working
- SLA tracking accurate dengan escalation
- Reports generating correctly (PDF & Excel)
- Performance benchmarks met
- Security audit passed

### 8.2 Post-Launch
- User adoption rate > 80% dalam 30 hari
- Ticket resolution time improvement 20% dalam 3 bulan
- Asset accuracy > 95%
- Zero critical bugs dalam 2 minggu pertama
- SLA compliance rate > 90%

## 9. Timeline & Milestones

### Phase 1: Foundation (Week 1-2)
- User management & authentication
- Role-based access control
- Department management
- Basic dashboard

### Phase 2: Ticketing (Week 3-4)
- Ticket CRUD dengan attachments
- Ticket workflow dan assignment
- Comments system (dengan internal notes)
- SLA tracking dan escalation
- Audit logging

### Phase 3: Knowledge Base (Week 5)
- Category management
- Article CRUD dengan versioning
- Tags dan voting system
- Search functionality
- Internal/public visibility

### Phase 4: Asset Management (Week 6-7)
- Asset CRUD dengan lifecycle tracking
- Vendor management
- Maintenance logs
- Document management
- Depreciation calculation
- Warranty tracking

### Phase 5: Reporting (Week 8)
- Report generators (ticket, asset, KB)
- PDF export
- Excel/CSV export
- Dashboard widgets
- Report run history

### Phase 6: Testing & Launch (Week 9-10)
- UAT testing
- Bug fixes
- Performance optimization
- Production deployment
- User training

## 10. Appendix

### 10.1 Glossary
- **SLA**: Service Level Agreement - Target response/resolution time
- **KB**: Knowledge Base - Dokumentasi dan troubleshooting guides
- **ITSM**: IT Service Management
- **Lifecycle**: Stages dari asset procurement sampai disposal
- **Escalation**: Process untuk notify management saat SLA breached
- **Vendor**: Supplier/provider untuk assets atau maintenance services

### 10.2 References
- ITIL Framework (for ticketing best practices)
- ISO 20000 (IT Service Management standard)
- COBIT (for IT governance)
