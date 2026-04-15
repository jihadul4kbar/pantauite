# Alur Pemeliharaan Berkala Asset (Maintenance Workflow)

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-09  
**Last Updated:** 2026-04-09

---

## 📋 Daftar Isi

1. [Gambaran Umum](#gambaran-umum)
2. [Diagram Alur Lengkap](#diagram-alur-lengkap)
3. [Penjadwalan Perawatan (Scheduling)](#1-penjadwalan-perawatan-scheduling)
4. [Pembuatan Task (Work Order)](#2-pembuatan-task-work-order)
5. [Pelaksanaan Perawatan (Execution)](#3-pelaksanaan-perawatan-execution)
6. [Dokumentasi & Parts](#4-dokumentasi--parts)
7. [Evaluasi Pasca Perawatan](#5-evaluasi-pasca-perawatan)
8. [Approval Workflow](#6-approval-workflow)
9. [Inventory Management](#7-inventory-management)
10. [Notifikasi Otomatis (Telegram)](#8-notifikasi-otomatis-telegram)
11. [Role-Based Access](#role-based-access)
12. [Database Tables](#database-tables)
13. [API Endpoints](#api-endpoints)

---

## Gambaran Umum

Sistem pemeliharaan berkala di PantauITE dirancang untuk:

- ✅ **Menjadwalkan** perawatan asset secara berkala (harian/mingguan/bulanan/tahunan)
- ✅ **Membuat** work order (task) untuk setiap perawatan
- ✅ **Melaksanakan** perawatan dengan checklist terstruktur
- ✅ **Mendokumentasikan** setiap aktivitas dengan foto dan catatan
- ✅ **Mencatat** parts/materials yang digunakan
- ✅ **Mengevaluasi** hasil perawatan dan kondisi asset
- ✅ **Mengelola** stok spare parts & inventory
- ✅ **Mengirim** notifikasi otomatis via Telegram

---

## Diagram Alur Lengkap

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    MAINTENANCE WORKFLOW                                  │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  1. PENJADWALAN (MaintenanceSchedule)                             │   │
│  │     • Buat jadwal berkala untuk asset                            │   │
│  │     • Tentukan frekuensi (daily/weekly/monthly/yearly)           │   │
│  │     • Assign ke IT Staff / Vendor                                │   │
│  │     • Set approval threshold untuk biaya tinggi                  │   │
│  └──────────────────────┬───────────────────────────────────────────┘   │
│                         │                                               │
│                         ▼                                               │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  2. PEMBUATAN TASK (MaintenanceTask)                              │   │
│  │     • Auto-generate dari schedule (via cron job)                 │   │
│  │     • Atau buat manual (corrective/emergency)                    │   │
│  │     • Status: pending → scheduled → in_progress → completed      │   │
│  │     • Approval required jika biaya > threshold                   │   │
│  └──────────────────────┬───────────────────────────────────────────┘   │
│                         │                                               │
│            ┌────────────┴────────────┐                                  │
│            ▼                         ▼                                  │
│  ┌──────────────────┐    ┌──────────────────────┐                      │
│  │ 3. EXECUTION     │    │ 4. APPROVAL (jika    │                      │
│  │                  │    │    biaya tinggi)      │                      │
│  │ • Isi checklist  │    │ • IT Manager review  │                      │
│  │ • Upload foto    │    │ • Approve/Reject     │                      │
│  │ • Catat parts    │    │ • Comment            │                      │
│  │ • Resolution     │    └──────────────────────┘                      │
│  └────────┬─────────┘                                                  │
│           │                                                            │
│           ▼                                                            │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  5. EVALUASI (MaintenanceEvaluation)                               │   │
│  │     • Rating 1-5 stars                                            │   │
│  │     • Kondisi sebelum & sesudah                                   │   │
│  │     • Issues ditemukan                                            │   │
│  │     • Rekomendasi perbaikan                                       │   │
│  │     • Asset health score                                          │   │
│  └──────────────────────┬───────────────────────────────────────────┘   │
│                         │                                               │
│                         ▼                                               │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  6. INVENTORY UPDATE (InventoryPart)                               │   │
│  │     • Auto deduct parts yang digunakan                            │   │
│  │     • Stock-out transaction tercatat                              │   │
│  │     • Low stock alert jika di bawah reorder point                 │   │
│  └──────────────────────────────────────────────────────────────────┘   │
│                                                                          │
│  ┌──────────────────────────────────────────────────────────────────┐   │
│  │  7. NOTIFIKASI TELEGRAM (Otomatis)                                 │   │
│  │     • Reminder H-3 jadwal perawatan                               │   │
│  │     • Alert task overdue                                          │   │
│  │     • Approval request                                            │   │
│  │     • Low stock warning                                           │   │
│  └──────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 1. Penjadwalan Perawatan (Scheduling)

### **Tujuan:**
Membuat jadwal perawatan berkala untuk setiap asset agar tidak ada yang terlewat.

### **Pelaku:** IT Manager / Super Admin

### **Alur:**

```
1. IT Manager buka menu Maintenance → Schedules
2. Klik "Add Schedule"
3. Pilih asset yang akan dijadwalkan
4. Isi informasi:
   • Nama jadwal (contoh: "Monthly Server Maintenance")
   • Deskripsi detail pekerjaan
   • Jenis: Preventive / Corrective / Predictive
   • Frekuensi: Daily / Weekly / Monthly / Yearly / Custom (hari)
   • Tanggal mulai (next_due_date)
   • Estimasi durasi & biaya
   • Personil yang ditugaskan (IT Staff)
   • Vendor eksternal (jika ada)
   • Approval threshold (biaya yang perlu approval)
5. Simpan
6. Status: Active / Inactive
```

### **Contoh Jadwal:**

| Asset | Jadwal | Frekuensi | Assigned To | Threshold |
|-------|--------|-----------|-------------|-----------|
| Server Dell R750 | Monthly Preventive | Setiap 1 bulan | Ahmad (IT Staff) | Rp 5.000.000 |
| Printer HP M404dn | Quarterly Cleaning | Setiap 3 bulan | Vendor XYZ | Rp 2.000.000 |
| UPS APC 3000VA | Battery Check | Setiap 6 bulan | Ahmad (IT Staff) | Rp 1.000.000 |

### **Database:**
Table: `maintenance_schedules`

---

## 2. Pembuatan Task (Work Order)

### **Tujuan:**
Membuat work order individual yang bisa dieksekusi, baik dari jadwal otomatis maupun manual.

### **Pelaku:** System (auto-generate) atau IT Manager (manual)

### **Alur Otomatis (dari Schedule):**

```
1. Cron job berjalan setiap hari jam 08:00
   Command: php artisan maintenance:notify --generate

2. System mencari schedule dengan next_due_date dalam 7 hari ke depan

3. Untuk setiap schedule yang memenuhi syarat:
   • Buat task baru dengan status "scheduled"
   • Copy informasi dari schedule ke task
   • Update schedule last_completed_date
   • Hitung next_due_date berikutnya
   • Kirim reminder ke assigned user (jika Telegram configured)

4. Task muncul di daftar "Upcoming Tasks"
```

### **Alur Manual:**

```
1. IT Manager buka menu Maintenance → Tasks
2. Klik "New Task"
3. Isi form:
   • Pilih asset
   • Pilih schedule (opsional, jika terkait jadwal)
   • Judul task
   • Deskripsi detail
   • Jenis: Preventive / Corrective / Predictive / Emergency
   • Priority: Low / Medium / High / Critical
   • Tanggal scheduled
   • Assigned to user/vendor
   • Estimasi biaya
4. Simpan
5. Status awal: "pending"
```

### **Task Status Flow:**

```
pending → scheduled → in_progress → completed
                           ↓
                    (jika overdue)
                           ↓
                        overdue
```

### **Approval Check:**
```
Jika estimated_cost > approval_threshold:
    status → "pending_approval"
    Notifikasi ke IT Manager via Telegram
    IT Manager approve/reject
    Jika approved → lanjut ke execution
    Jika rejected → task dibatalkan
```

### **Database:**
Table: `maintenance_tasks`

---

## 3. Pelaksanaan Perawatan (Execution)

### **Tujuan:**
Mencatat pelaksanaan perawatan dengan checklist, foto, dan parts yang digunakan.

### **Pelaku:** IT Staff (yang di-assign ke task)

### **Alur:**

```
1. IT Staff buka task yang assigned ke mereka
2. Klik tombol "Execute"
3. Form execution terbuka dengan 3 section:

   SECTION A: CHECKLIST
   • Tampilkan checklist items dari schedule template
   • Untuk setiap item:
     - Pilih status: ✅ Pass / ❌ Fail / ➖ N/A
     - Isi notes (opsional, wajib jika Fail)
     - Upload foto (jika item requires_photo = true)

   SECTION B: PARTS & MATERIALS
   • Daftar parts/materials yang digunakan
   • Untuk setiap part:
     - Nama part
     - Quantity
     - Unit cost (auto-fill dari inventory jika ada)
     - Total = Qty × Unit Cost
   • Jika part ada di inventory → auto stock-out

   SECTION C: RESOLUTION NOTES
   • Deskripsi pekerjaan yang dilakukan
   • Masalah ditemukan
   • Solusi yang diterapkan
   • Rekomendasi untuk perawatan berikutnya

4. Klik "Complete Task"
5. System update:
   • status → "completed"
   • completed_at → now()
   • actual_duration_minutes → hitung dari started_at
   • actual_cost → sum dari parts total_cost
```

### **Checklist Template Example:**

| # | Item | Status | Notes | Photo |
|---|------|--------|-------|-------|
| 1 | Check CPU temperature | ✅ Pass | Normal: 45°C | - |
| 2 | Check disk space | ❌ Fail | Disk C: 92% full | 📷 disk_usage.jpg |
| 3 | Clean dust from fans | ✅ Pass | Selesai | - |
| 4 | Test backup process | ✅ Pass | Success | - |
| 5 | Check UPS battery | ➖ N/A | Baru diganti bulan lalu | - |

### **Database:**
Tables: `maintenance_checklist_items`, `maintenance_checklist_results`, `maintenance_requirements`

---

## 4. Dokumentasi & Parts

### **Dokumentasi Foto:**

```
Jenis foto yang bisa diupload:
📷 Before     - Kondisi sebelum maintenance
🔧 During     - Proses pengerjaan
✅ After      - Kondisi setelah maintenance
📎 Evidence   - Bukti pendukung (error log, dll)

Max file size: 5MB per foto
Format: JPG, PNG, WEBP
```

### **Parts & Materials:**

```
Saat mencatat parts:
1. Jika part ada di inventory (part_id terisi):
   • Auto deduct stock
   • Buat inventory transaction (type: out)
   • Reference: maintenance task

2. Jika part tidak ada di inventory:
   • Tetap tercatat untuk cost calculation
   • Tidak ada stock deduction
```

### **Cost Calculation:**

```
Total Task Cost = Sum(Part Quantity × Unit Cost)
                + Labor cost (jika ada di masa depan)

Example:
- RAM DDR4 16GB    : 2 × Rp 850.000 = Rp 1.700.000
- Thermal Paste    : 1 × Rp 150.000 = Rp 150.000
- Cleaning Kit     : 1 × Rp 200.000 = Rp 200.000
─────────────────────────────────────────────
Total                          = Rp 2.050.000
```

### **Database:**
Tables: `maintenance_photos`, `maintenance_requirements`, `inventory_transactions`

---

## 5. Evaluasi Pasca Perawatan

### **Tujuan:**
Mengevaluasi kualitas perawatan dan kondisi asset setelah maintenance.

### **Pelaku:** IT Manager / Supervisor

### **Alur:**

```
1. Setelah task completed, IT Manager buka task detail
2. Scroll ke section "Evaluations"
3. Klik "Add Evaluation"
4. Isi form evaluasi:
   • Overall Rating: ⭐⭐⭐⭐⭐ (1-5)
   • Kondisi asset sebelum maintenance
   • Kondisi asset setelah maintenance
   • Issues yang ditemukan
   • Rekomendasi perbaikan
   • Follow-up required? (Yes/No)
   • Jika Yes: Follow-up notes
   • Rekomendasi untuk perawatan berikutnya
   • Asset health score: 0-100%
5. Simpan
```

### **Contoh Evaluasi:**

```
Rating: ⭐⭐⭐⭐ (4/5)

Asset Condition Before:
"Server mengalami overheating, CPU usage 85%, disk space C: 92%"

Asset Condition After:
"Server normal, CPU 45°C, disk space C: 65% setelah cleanup"

Issues Found:
- Disk hampir penuh karena log files tidak di-rotate
- Fan belakang berisik, kemungkinan bearing aus

Recommendations:
- Implement log rotation untuk semua services
- Ganti fan belakang dalam 2 minggu ke depan
- Jadwalkan disk cleanup monthly

Asset Health Score: 85%
```

### **Database:**
Table: `maintenance_evaluations`

---

## 6. Approval Workflow

### **Tujuan:**
Mengontrol biaya maintenance yang tinggi dengan approval dari manajemen.

### **Trigger:**
```
Jika task.estimated_cost > schedule.approval_threshold
    → status = "pending_approval"
    → Kirim notifikasi ke IT Manager
```

### **Alur:**

```
1. IT Staff membuat task dengan estimated_cost = Rp 8.000.000
2. Schedule approval_threshold = Rp 5.000.000
3. Karena 8.000.000 > 5.000.000:
   • Task status → "pending_approval"
   • IT Manager menerima notifikasi Telegram
   • Task tidak bisa dieksekusi sampai approved

4. IT Manager membuka task, klik "Approve"
5. Isi justification & comments
6. Approve atau Reject

7. Jika Approved:
   • status → "scheduled" atau "pending"
   • IT Staff bisa mulai execute

8. Jika Rejected:
   • status → "cancelled"
   • IT Staff mendapat notifikasi
   • Task ditutup
```

### **Database:**
Table: `maintenance_approvals`

---

## 7. Inventory Management

### **Tujuan:**
Mengelola stok spare parts dan materials untuk maintenance.

### **Fitur:**

```
✅ CRUD Inventory Parts
✅ Stock In (pembelian/penerimaan barang)
✅ Stock Out (otomatis saat maintenance)
✅ Stock Adjustment (manual correction)
✅ Low Stock Alerts
✅ Reorder Point Tracking
```

### **Alur Stock In:**

```
1. Buka Inventory → Pilih Part → Stock In
2. Isi:
   • Quantity to add
   • Unit cost (harga beli terbaru)
   • Supplier
   • Notes (PO number, receipt, dll)
3. Submit
4. System:
   • Update quantity_in_stock
   • Buat transaction record (type: in)
   • Update last_restocked date
   • Update unit_cost (latest price)
```

### **Alur Stock Out (Otomatis):**

```
1. Saat execution task, IT Staff mencatat part yang digunakan
2. Jika part_id terisi dan ada di inventory:
   • Check quantity_in_stock >= quantity_needed
   • Jika cukup:
     - Deduct stock
     - Buat transaction record (type: out)
     - Reference: maintenance task
   • Jika tidak cukup:
     - Warning: "Insufficient stock"
     - User bisa pilih lanjutkan atau batal
```

### **Low Stock Alert:**

```
Cron job harian jam 09:00:
• Check parts dengan quantity_in_stock <= reorder_point
• Kirim notifikasi Telegram ke IT Manager
• Contoh: "⚠️ Low Stock: RAM DDR4 16GB (Stock: 2, Reorder: 5)"
```

### **Inventory Transaction Types:**

| Type | Keterangan | Trigger |
|------|-----------|---------|
| `in` | Stock masuk | Manual Stock In |
| `out` | Stock keluar | Maintenance execution |
| `adjust` | Adjustment | Manual correction |

### **Database:**
Tables: `inventory_parts`, `inventory_transactions`

---

## 8. Notifikasi Otomatis (Telegram)

### **Setup:**

```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_or_group_id
```

### **Jenis Notifikasi:**

| Notifikasi | Trigger | Receiver | Waktu |
|------------|---------|----------|-------|
| Task Reminder | Upcoming tasks (3 hari) | Assigned user | Daily 08:00 |
| Overdue Alert | Task melewati jadwal | Assigned user + Manager | Every 2 hours |
| Approval Request | Task butuh approval | IT Manager | Real-time |
| Task Completed | Task selesai | Assigned user | Real-time |
| Low Stock Alert | Stock <= reorder point | IT Manager | Daily 09:00 |

### **Cron Schedule:**

```bash
# Daily full check (08:00)
php artisan maintenance:notify --all

# Overdue check (every 2 hours, 08:00-18:00)
php artisan maintenance:notify --overdue

# Low stock check (09:00)
php artisan maintenance:notify --lowstock
```

### **Contoh Pesan Telegram:**

```
📅 Maintenance Reminder

🔧 Task: MNT-2026-0015
📝 Monthly Server Maintenance
📦 Asset: AST-HW-0003
📅 Scheduled: 15 Apr 2026
⚡ Priority: High
```

### **Setup Telegram Bot:**

1. Chat [@BotFather](https://t.me/botfather) di Telegram
2. Kirim `/newbot`
3. Ikuti instruksi sampai dapat Bot Token
4. Tambahkan ke `.env`
5. Tambahkan `telegram_chat_id` ke user profile untuk notifikasi personal

---

## Role-Based Access

| Fitur | Super Admin | IT Manager | IT Staff | End User |
|-------|:-----------:|:----------:|:--------:|:--------:|
| Buat Schedule | ✅ | ✅ | ❌ | ❌ |
| Edit Schedule | ✅ | ✅ | ❌ | ❌ |
| Create Task Manual | ✅ | ✅ | ❌ | ❌ |
| Execute Task | ✅ | ✅ | ✅ (assigned) | ❌ |
| Fill Checklist | ✅ | ✅ | ✅ | ❌ |
| Upload Photos | ✅ | ✅ | ✅ | ❌ |
| Record Parts | ✅ | ✅ | ✅ | ❌ |
| Evaluate Task | ✅ | ✅ | ❌ | ❌ |
| Approve Task | ✅ | ✅ | ❌ | ❌ |
| View Reports | ✅ | ✅ | ✅ | ❌ |
| Manage Inventory | ✅ | ✅ | ✅ | ❌ |

---

## Database Tables

| Table | Purpose | Key Fields |
|-------|---------|------------|
| `maintenance_schedules` | Jadwal berkala | asset_id, frequency_type, next_due_date |
| `maintenance_tasks` | Work orders | task_number, status, scheduled_date |
| `maintenance_checklist_items` | Template checklist | schedule_id, item_name, is_required |
| `maintenance_checklist_results` | Hasil checklist | task_id, status (pass/fail/na) |
| `maintenance_requirements` | Parts/materials used | task_id, part_name, quantity, total_cost |
| `maintenance_evaluations` | Evaluasi pasca maintenance | task_id, rating, health_score |
| `maintenance_photos` | Dokumentasi foto | task_id, photo_type, file_path |
| `maintenance_approvals` | Approval history | task_id, status, approver_id |
| `inventory_parts` | Spare parts stock | part_number, quantity_in_stock, reorder_point |
| `inventory_transactions` | Stock movement log | part_id, type (in/out/adjust), quantity |

---

## API Endpoints

### Schedules
```
GET    /maintenance/schedules              → List schedules
POST   /maintenance/schedules              → Create schedule
GET    /maintenance/schedules/{id}/edit    → Edit form
PUT    /maintenance/schedules/{id}         → Update schedule
DELETE /maintenance/schedules/{id}         → Delete schedule
POST   /maintenance/schedules/generate-tasks → Auto-generate tasks
```

### Tasks
```
GET    /maintenance/tasks                  → List tasks
POST   /maintenance/tasks                  → Create task
GET    /maintenance/tasks/{id}             → Task detail
GET    /maintenance/tasks/{id}/execute     → Execution form
POST   /maintenance/tasks/{id}/execute     → Save execution
POST   /maintenance/tasks/{id}/photos      → Upload photos
GET    /maintenance/tasks/{id}/approval    → Approval form
POST   /maintenance/tasks/{id}/approve     → Approve task
POST   /maintenance/tasks/{id}/reject      → Reject task
```

### Inventory
```
GET    /maintenance/inventory              → List parts
POST   /maintenance/inventory              → Create part
GET    /maintenance/inventory/{id}         → Part detail
GET    /maintenance/inventory/{id}/edit    → Edit form
PUT    /maintenance/inventory/{id}         → Update part
GET    /maintenance/inventory/{id}/stock-in → Stock In form
POST   /maintenance/inventory/{id}/stock-in → Process Stock In
POST   /maintenance/inventory/{id}/adjust   → Adjust stock
```

---

## 📊 Key Metrics & Reports

### Maintenance KPIs:
- **Completion Rate**: % tasks completed on time
- **Average Task Duration**: Mean waktu pengerjaan
- **Total Maintenance Cost**: Biaya maintenance per periode
- **Asset Health Score**: Rata-rata kondisi asset
- **Parts Usage Rate**: Frekuensi penggunaan parts
- **SLA Compliance**: % tasks completed within SLA

### Inventory Reports:
- **Low Stock Parts**: Parts yang perlu di-reorder
- **Total Inventory Value**: Nilai total stock
- **Parts Usage by Asset**: Parts yang paling sering digunakan
- **Cost per Asset**: Biaya maintenance per asset

---

**Dokumen ini merupakan bagian dari dokumentasi resmi PantauITE IT Service Management Platform.** 📚
