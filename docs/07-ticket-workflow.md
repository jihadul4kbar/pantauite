# Ticket Workflow - PantauITE IT Service Management

**Version:** 1.0  
**Status:** Final  
**Created:** 2026-04-09  
**Last Updated:** 2026-04-09

---

# 🎫 Alur Tiket di PantauITE - Dari Masuk Sampai Selesai

Dokumen ini menjelaskan alur lengkap tiket di PantauITE, dari pembuatan hingga penutupan, termasuk pelaku, tahapan, dan fitur pendukung.

---

## 📊 Diagram Alur Tiket

```
┌─────────────────────────────────────────────────────────────────┐
│                    TICKET LIFECYCLE                              │
│                                                                  │
│  ┌──────┐     Assign      ┌─────────────┐   Resolve    ┌────────┐│
│  │ OPEN │ ──────────────► │ IN PROGRESS │ ──────────► │RESOLVED││
│  └──────┘                └─────────────┘             └───┬────┘│
│     ▲                                                    │      │
│     │        Reopen                 Close               │      │
│     └───────────────────────────────────────────────────┘      │
│                                ↓                                │
│                         ┌────────┐                              │
│                         │ CLOSED │                              │
│                         └────────┘                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 👥 Pelaku & Peran Mereka

| Role | Siapa | Tugas Utama |
|------|-------|-------------|
| **End User** | Karyawan non-IT | Membuat tiket, tracking status, memberi komentar |
| **IT Manager** | Manajer IT | Assign tiket, monitoring, close tiket, laporan |
| **IT Staff** | Teknisi IT | Menangani tiket, update status, resolve |
| **Super Admin** | Admin Sistem | Full akses ke semua fitur |

---

## 📝 Tahapan Lengkap Tiket

### 1️⃣ PEMBUATAN TIKET (Ticket Creation)

**Pelaku:** End User / IT Staff / IT Manager / Super Admin

**Alur:**
1. User login ke sistem
2. Klik menu **"Tickets" → "Create New Ticket"**
3. Isi form:
   - **Subject** - Judul masalah
   - **Description** - Detail masalah
   - **Category** - Hardware/Software/Network/Email/dll
   - **Priority** - Critical/High/Medium/Low
   - **Department** - Departemen terkait
   - **Attachments** (opsional) - Screenshot, log, dll

**Yang Terjadi di Sistem:**
```php
✅ Auto-generate ticket number: TKT-2026-0001
✅ Status otomatis: "OPEN"
✅ SLA deadline dihitung berdasarkan priority
✅ Created_at tercatat
✅ Notifikasi ke IT Manager/Staff
```

**Contoh:**
```
Ticket: TKT-2026-0019
Subject: "Laptop tidak bisa booting"
Creator: John Doe (End User)
Status: OPEN
Priority: High
SLA Deadline: 8 jam dari sekarang
```

---

### 2️⃣ PENUGASAN TIKET (Ticket Assignment)

**Pelaku:** IT Manager (atau Super Admin)

**Alur:**
1. IT Manager buka halaman **Tickets Index**
2. Lihat daftar tiket dengan status **OPEN**
3. Klik tombol **"Assign"** pada tiket
4. Pilih IT Staff yang akan menangani
5. Klik **"Submit"**

**Yang Terjadi di Sistem:**
```php
✅ Ticket.assignee_id = ID IT Staff
✅ Status tetap OPEN (belum diubah)
✅ Audit log tercatat: "Assigned to [Staff Name]"
✅ First Response Timer dimulai
```

**Contoh:**
```
Ticket: TKT-2026-0019
Assigned to: IT Support Staff
Assigned by: IT Manager
Time: 2 menit setelah tiket dibuat
```

---

### 3️⃣ PENANGANAN TIKET (Ticket Handling)

**Pelaku:** IT Staff (yang ditugaskan)

**Alur:**
1. IT Staff login, lihat **"My Assigned Tickets"**
2. Klik tiket yang akan ditangani
3. Update status: **OPEN → IN PROGRESS**
4. Tambahkan komentar/internal note:
   - *"Saya akan cek laptopnya"*
   - *"Sedang diagnosa hardware"*
5. Mulai troubleshooting

**Yang Terjadi di Sistem:**
```php
✅ Status berubah: "open" → "in_progress"
✅ First Response Timer tercatat
✅ SLA Timer terus berjalan
✅ Komentar tercatat (bisa internal note untuk IT staff saja)
✅ Audit log: "Status changed to IN PROGRESS"
```

---

### 4️⃣ RESOLUSI TIKET (Ticket Resolution)

**Pelaku:** IT Staff

**Alur:**
1. IT Staff selesai troubleshooting
2. Masalah ditemukan dan diperbaiki
3. Update status: **IN PROGRESS → RESOLVED**
4. Isi **Resolution Notes**:
   - *"RAM diganti, laptop sudah bisa booting normal"*
5. Klik **"Resolve Ticket"**

**Yang Terjadi di Sistem:**
```php
✅ Status: "in_progress" → "resolved"
✅ Resolved_at tercatat (untuk hitung SLA compliance)
✅ Resolution notes disimpan
✅ Audit log: "Ticket resolved by [Staff Name]"
```

---

### 5️⃣ VERIFIKASI & PENUTUPAN (Verification & Closing)

**Pelaku:** IT Manager (atau End User jika puas)

**Alur:**
1. IT Manager review tiket yang sudah resolved
2. Cek resolution notes
3. Jika OK → Klik **"Close Ticket"**
4. Jika belum OK → Klik **"Reopen Ticket"**

**Yang Terjadi di Sistem:**

**Jika DITUTUP:**
```php
✅ Status: "resolved" → "closed"
✅ Closed_at tercatat
✅ SLA compliance dihitung
✅ Ticket selesai
```

**Jika DIBUKA KEMBALI:**
```php
✅ Status: "resolved" → "reopened"
✅ Kembali ke IT Staff untuk penanganan ulang
✅ Audit log: "Reopened - issue not resolved"
```

---

## ⏱️ SLA (Service Level Agreement)

**SLA Timer** berjalan sejak tiket dibuat:

| Priority | Response Time | Resolution Time | Business Hours |
|----------|---------------|-----------------|----------------|
| **Critical** | 15 menit | 4 jam | 24/7 |
| **High** | 1 jam | 8 jam | Jam kerja |
| **Medium** | 4 jam | 24 jam | Jam kerja |
| **Low** | 8 jam | 72 jam | Jam kerja |

### SLA Status:

| Status | Warna | Keterangan |
|--------|-------|------------|
| ✅ **On Track** | 🟢 Hijau | Masih dalam waktu SLA |
| ⚠️ **At Risk** | 🟠 Kuning | Mendekati batas SLA (30 menit sebelum breach) |
| 🔴 **Breached** | 🔴 Merah | Melewati batas SLA |
| ⏸️ **Paused** | 🟡 Kuning | Ditangguhkan (menunggu customer/external party) |

---

## 📋 Contoh Kasus Lengkap

```
┌─────────────────────────────────────────────────────────────────┐
│                    CONTOH KASUS:                                │
│              "Laptop Tidak Bisa Booting"                         │
└─────────────────────────────────────────────────────────────────┘

1. 📝 TIKET DIBUAT (09:00)
   User:     John Doe (Marketing)
   Subject:  "Laptop Dell Latitude tidak bisa booting"
   Priority: High
   Status:   OPEN
   SLA:      8 jam (deadline: 17:00)
   
2. 📋 TIKET DIASSIGN (09:05)
   By:       IT Manager
   To:       IT Support Staff (Ahmad)
   Status:   OPEN
   
3. 🔧 MULAI DITANGANI (09:30)
   By:       Ahmad (IT Staff)
   Status:   OPEN → IN PROGRESS
   Note:     "Saya cek laptopnya, sepertinya hardware issue"
   
4. ✅ TIKET DISELESAIKAN (11:00)
   By:       Ahmad (IT Staff)
   Status:   IN PROGRESS → RESOLVED
   Resolution: "RAM diganti, laptop normal kembali"
   Waktu:    2 jam (dalam SLA ✅)
   
5. 🔒 TIKET DITUTUP (11:30)
   By:       IT Manager
   Status:   RESOLVED → CLOSED
   SLA:      ✅ COMPLIANT (resolved dalam 2 jam, target 8 jam)
```

---

## 🎯 Ringkasan Peran

| Tahapan | End User | IT Staff | IT Manager | Super Admin |
|---------|:--------:|:--------:|:----------:|:-----------:|
| Buat Tiket | ✅ | ✅ | ✅ | ✅ |
| Assign Tiket | ❌ | ❌ | ✅ | ✅ |
| Handle Tiket | ❌ | ✅ | ✅ | ✅ |
| Resolve Tiket | ❌ | ✅ | ✅ | ✅ |
| Close Tiket | ❌ | ❌ | ✅ | ✅ |
| Reopen Tiket | ❌ | ✅ | ✅ | ✅ |
| Pause SLA | ❌ | ✅ | ✅ | ✅ |
| View Laporan | ❌ | ✅ | ✅ | ✅ |

---

## 💡 Fitur Tambahan

### Komentar & Internal Notes

- **End User** bisa tambah komentar di tiket mereka
- **IT Staff** bisa tambah **Internal Note** (hanya visible untuk IT team)
- Semua komentar tercatat dengan timestamp dan user yang menambahkan

**Contoh Internal Note:**
```
[INTERNAL NOTE - Hanya IT Team]
"User sudah dikonfirmasi, masalah ada pada hardware. 
Order part replacement dari vendor Dell."
```

### Attachment

- Bisa upload screenshot, log, dokumen (max 5MB per file, max 10 files)
- Tersimpan di storage dan terasosiasi dengan tiket
- Tipe file yang didukung: Images, Documents, Logs

### Audit Trail

Semua perubahan tercatat:

| Field | Keterangan |
|-------|-----------|
| **User** | Siapa yang mengubah |
| **Action** | Jenis perubahan (status, assign, comment, dll) |
| **Old Values** | Nilai sebelum perubahan |
| **New Values** | Nilai setelah perubahan |
| **Timestamp** | Kapan diubah |
| **IP Address** | Dari mana perubahan dilakukan |

### SLA Pause

- IT Staff bisa pause SLA saat menunggu respon customer
- Timer berhenti sampai di-resume kembali
- Berguna untuk kasus:
  - Menunggu konfirmasi dari user
  - Menunggu part dari vendor
  - User tidak available untuk testing

---

## 🔍 Status Tiket

| Status | Warna | Keterangan |
|--------|-------|------------|
| **Open** | 🔵 Biru | Tiket baru, belum ditangani |
| **In Progress** | 🟡 Kuning | Sedang ditangani oleh IT Staff |
| **Resolved** | 🟢 Hijau | Masalah sudah diselesaikan |
| **Closed** | ⚪ Abu-abu | Tiket ditutup dan selesai |
| **Reopened** | 🔴 Merah | Tiket dibuka kembali karena masalah belum tuntas |

---

## 📊 Laporan Tiket

IT Manager dan IT Staff dapat menghasilkan laporan:

1. **Ticket Summary** - Ringkasan semua tiket
2. **Ticket by Status** - Distribusi tiket berdasarkan status
3. **Ticket by Priority** - Distribusi tiket berdasarkan prioritas
4. **SLA Compliance Report** - Persentase kepatuhan SLA
5. **Staff Performance Report** - Performa IT Staff dalam menangani tiket
6. **Escalation Report** - Tiket yang mengalami escalation

---

## 📞 Source Tiket

Tiket bisa berasal dari berbagai sumber:

| Source | Keterangan |
|--------|-----------|
| **Web** | User submit melalui form web |
| **Phone** | User lapor via telepon, IT Staff yang input |
| **Walk-in** | User datang langsung ke IT |
| **Email** | User kirim email ke IT support |

---

## 🎓 Kesimpulan

Alur tiket di PantauITE dirancang untuk:

1. **Sederhana** - Mudah dipahami dan digunakan
2. **Terstruktur** - Setiap tahapan jelas dan tercatat
3. **Terukur** - SLA tracking memastikan tiket ditangani tepat waktu
4. **Audit Trail** - Semua perubahan tercatat untuk accountability
5. **Fleksibel** - Bisa handle berbagai jenis permintaan IT

Dengan workflow ini, departemen IT dapat:
- ✅ Melacak semua permintaan secara terpusat
- ✅ Memastikan SLA compliance
- ✅ Meningkatkan produktivitas IT Staff
- ✅ Memberikan transparansi ke user
- ✅ Menghasilkan laporan untuk manajemen

---

**Dokumen ini merupakan bagian dari dokumentasi resmi PantauITE IT Service Management Platform.** 📚
