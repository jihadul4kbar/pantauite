# Repair Request Workflow - Dokumentasi

## 📋 Overview

Permintaan Perbaikan (Repair Request) adalah alur untuk pengguna **non-login** (publik) untuk melaporkan masalah atau permintaan perbaikan. Setelah diverifikasi dan disetujui, permintaan akan dikonversi menjadi tiket.

---

## 🔄 Alur Kerja

```
┌─────────────────┐
│ 1. User Publik  │
│    Submit Form  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 2. Status:      │
│    SUBMITTED    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 3. IT Manager   │
│    Verifikasi   │
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
    ▼         ▼
┌───────┐ ┌──────────┐
│APPROVE│ │  REJECT  │
└───┬───┘ └──────────┘
    │
    ▼
┌─────────────────┐
│ 4. Status:      │
│    APPROVED     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 5. Convert to   │
│    TICKET       │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│ 6. Status:      │
│    CONVERTED    │
│    (Tiket ID)   │
└─────────────────┘
```

---

## 📝 Detail Setiap Tahap

### **1. Submit Form (Publik)**

**Route:** `GET /repair-requests/create`

**Form Fields:**
- **Informasi Pemohon:**
  - Nama Lengkap (required)
  - Email (required)
  - Phone (optional)
  - Department (optional) - **PENTING: Ini akan menjadi "Dilaporkan Oleh"**

- **Detail Masalah:**
  - Subject (required)
  - Description (required)
  - Priority (required)
  - Category (required)
  - Location (optional)
  - Asset Name (optional)
  - Asset Serial (optional)

- **Upload Foto:**
  - Multiple images (max 5, 2MB each)
  - Format: JPG, PNG, WebP
  - Auto-compress to WebP 80%

- **CAPTCHA:**
  - Simple math captcha untuk keamanan

**Model:** `RepairRequest`
**Status:** `submitted`

---

### **2. Status: SUBMITTED**

Setelah submit, permintaan masuk ke database dengan:
- Request Number: `REQ-YYYY-NNNN`
- Status: `submitted`
- Photos tersimpan di `repair_request_photos`

**Notifikasi:** User mendapat nomor permintaan untuk tracking.

---

### **3. Verifikasi oleh IT Manager**

**Route:** `GET /admin/repair-requests/{id}`

**Aksi yang Tersedia:**

#### **A. Approve**
```php
$repairRequest->approve(auth()->id());
```
- Set status: `approved`
- Set verified_by: IT Manager ID
- Set verified_at: timestamp

#### **B. Reject**
```php
$repairRequest->reject($reason, auth()->id());
```
- Set status: `rejected`
- Set rejection_reason: required
- Set verified_by: IT Manager ID
- Set verified_at: timestamp

#### **C. Convert to Ticket**
```php
// Only if status === 'approved'
```
- Lihat detail di section 5

---

### **4. Status: APPROVED / REJECTED**

**APPROVED:**
- Siap untuk dikonversi menjadi tiket
- IT Manager dapat langsung convert

**REJECTED:**
- Permintaan ditolak
- User tidak dapat convert
- Dapat di-delete oleh Super Admin

---

### **5. Convert to Ticket**

**Route:** `POST /admin/repair-requests/{id}/convert`

**Proses Konversi:**

#### **A. Cari Department sebagai Pelapor**

```php
// Cari department berdasarkan requester_department
$department = Department::where('name', $repairRequest->requester_department)
    ->orWhere('code', $repairRequest->requester_department)
    ->first();

$departmentId = $department ? $department->id : null;
```

**Prioritas "Dilaporkan Oleh":**
1. ✅ **Department** (jika ditemukan match)
2. ✅ **Requester Department** (text dari form, jika department tidak ditemukan)
3. ✅ **User** (fallback ke user yang membuat tiket)

#### **B. Buat Tiket**

```php
$ticket = TicketService::createTicket([
    'subject' => $repairRequest->subject,
    'description' => $repairRequest->description,
    'priority' => $repairRequest->priority,
    'category_id' => $repairRequest->category_id,
    'department_id' => $departmentId, // Department sebagai pelapor
    'source' => 'web',
    'requester_name' => $repairRequest->requester_name,
    'requester_email' => $repairRequest->requester_email,
    'requester_department' => $repairRequest->requester_department,
], $defaultUser); // IT Manager/Staff
```

**Fields yang Dipindahkan:**
- ✅ subject
- ✅ description
- ✅ priority
- ✅ category_id
- ✅ department_id (**PENTING: sebagai pelapor**)
- ✅ requester_name (backup info)
- ✅ requester_email (backup info)
- ✅ requester_department (backup info)

**Photos:**
- Photos dari RepairRequest **TIDAK** otomatis dipindahkan ke Ticket
- IT Staff perlu upload ulang jika diperlukan
- Atau develop feature untuk auto-copy photos

#### **C. Update Repair Request**

```php
$repairRequest->markAsConverted($ticket->id);
```
- Set status: `converted`
- Set ticket_id: ID tiket yang dibuat

---

### **6. Status: CONVERTED**

- Repair Request selesai
- Tiket sudah dibuat dan dapat diakses
- "Dilaporkan Oleh" menampilkan **Department** atau **Requester Department**

---

## 🎯 Tampilan "Dilaporkan Oleh" di Detail Tiket

### **Prioritas Display:**

```blade
@if($ticket->department)
    <!-- Tampilkan Department -->
    🏢 {{ $ticket->department->name }}
    👤 {{ $ticket->requester_name }} (jika ada)
    
@elseif($ticket->requester_department)
    <!-- Tampilkan Requester Department (text) -->
    🏢 {{ $ticket->requester_department }}
    👤 {{ $ticket->requester_name }} (jika ada)
    
@else
    <!-- Fallback ke User -->
    👤 {{ $ticket->user->name }}
@endif
```

### **Visual Design:**

**Department Match:**
```
┌─────────────────────────────────┐
│ 🏢 Dilaporkan Oleh              │
├─────────────────────────────────┤
│ [🏢] Departemen IT              │
│      John Doe                   │
└─────────────────────────────────┘
```

**Requester Department (Text):**
```
┌─────────────────────────────────┐
│ 🏢 Dilaporkan Oleh              │
├─────────────────────────────────┤
│ [🏢] HRD                        │
│      Jane Smith                 │
└─────────────────────────────────┘
```

**User (Fallback):**
```
┌─────────────────────────────────┐
│ 👤 Dilaporkan Oleh              │
├─────────────────────────────────┤
│ [👤] Admin IT                   │
└─────────────────────────────────┘
```

---

## 🗄️ Database Schema

### **repair_requests**
```php
- id
- request_number (REQ-2026-0001)
- requester_name
- requester_email
- requester_phone (nullable)
- requester_department (nullable) ← PENTING
- subject
- description
- priority
- category_id
- location (nullable)
- asset_name (nullable)
- asset_serial (nullable)
- status (submitted/approved/rejected/converted)
- rejection_reason (nullable)
- verified_by (nullable)
- verified_at (nullable)
- ticket_id (nullable)
- deleted_at (soft delete)
- timestamps
```

### **repair_request_photos**
```php
- id
- repair_request_id (FK)
- filename
- path
- mime_type
- file_size
- original_filename
- width
- height
- photo_taken_at (from EXIF)
- exif_data (JSON)
- timestamps
```

### **tickets** (updated)
```php
- id
- ...
- department_id (FK to departments) ← DIGUNAKAN SEBAGAI PELAPOR
- requester_name (nullable) ← BACKUP INFO
- requester_email (nullable) ← BACKUP INFO
- requester_department (nullable) ← BACKUP INFO
- ...
```

---

## 🔧 Code Changes Summary

### **1. Migration**
```bash
php artisan make:migration add_requester_fields_to_tickets_table
```
- Add: `requester_name`, `requester_email`, `requester_department`

### **2. Ticket Model**
```php
protected $fillable = [
    ...
    'requester_name',
    'requester_email',
    'requester_department',
];
```

### **3. RepairRequestController**
**Method:** `convertToTicket()`

**Changes:**
- Find department by name/code
- Set department_id pada tiket
- Simpan requester info sebagai backup

### **4. Ticket Show View**
**File:** `resources/views/tickets/show.blade.php`

**Changes:**
- Conditional display: Department > Requester Department > User
- Blue theme untuk Department
- Green theme untuk User

---

## ✅ Testing Checklist

### **Scenario 1: Department Match**
- [ ] Submit request dengan department "IT"
- [ ] Approve dan convert
- [ ] Cek detail tiket → "Dilaporkan Oleh: IT" (dengan icon 🏢)

### **Scenario 2: Department Not Found**
- [ ] Submit request dengan department "Research & Development"
- [ ] Jika department tidak ada di DB
- [ ] Cek detail tiket → "Dilaporkan Oleh: Research & Development" (text)

### **Scenario 3: No Department**
- [ ] Submit request tanpa department
- [ ] Convert to ticket
- [ ] Cek detail tiket → "Dilaporkan Oleh: [User yang convert]"

### **Scenario 4: Complete Flow**
- [ ] Submit → Approve → Convert → View Ticket
- [ ] Verify semua fields terpindah dengan benar
- [ ] Verify "Dilaporkan Oleh" menampilkan department

---

## 📊 Metrics & Reporting

**Data yang Dapat Dilacak:**
- Total requests per department
- Conversion rate (submitted → approved → converted)
- Average verification time
- Rejection reasons analysis
- Department dengan requests terbanyak

---

## 🔐 Security & Permissions

**Access Control:**
- **Create:** Public (no login required)
- **Verify:** IT Manager, Super Admin only
- **Convert:** IT Manager, Super Admin only
- **Delete:** Super Admin only
- **View:** Authorized users dengan permission

**CAPTCHA:**
- Simple math captcha untuk mencegah spam
- Configurable difficulty

---

## 🚀 Future Enhancements

**Planned Features:**
1. [ ] Auto-copy photos dari Repair Request ke Ticket
2. [ ] Email notification ke requester saat approved/rejected
3. [ ] Email notification ke requester saat ticket dibuat
4. [ ] Requester portal untuk track status (tanpa login)
5. [ ] Department auto-suggest saat submit form
6. [ ] Bulk convert (multiple requests → multiple tickets)
7. [ ] SLA untuk verification time (max 24 jam)

---

## 🐛 Known Issues

**Current Limitations:**
1. Photos tidak otomatis dipindahkan ke ticket
2. Department matching case-sensitive (perlu improvement)
3. Tidak ada notifikasi email ke requester
4. Requester tidak dapat track status tanpa nomor request

---

## 📞 Support

Untuk pertanyaan atau issue terkait Repair Request workflow:
- Contact: IT Manager
- Documentation: `/docs/10-repair-request-workflow.md`

---

**Last Updated:** April 21, 2026
**Version:** 1.0
