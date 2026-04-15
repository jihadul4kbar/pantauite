# PantauITE - Sistem Manajemen Layanan TI

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)

**PantauITE** adalah platform terintegrasi untuk manajemen layanan TI (IT Service Management) yang dirancang untuk membantu organisasi dalam mengelola tiket layanan, basis pengetahuan, aset TI, pemeliharaan preventif, dan pelaporan kinerja layanan TI.

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Alur Fitur](#-alur-fitur)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Role & Hak Akses](#-role--hak-akses)
- [Struktur Database](#-struktur-database)
- [Screenshot](#-screenshot)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

### 1. 🎫 Sistem Tiket
- Pembuatan tiket manual & otomatis dari permintaan perbaikan
- Workflow lengkap: Open → In Progress → Resolved → Closed
- SLA tracking dengan pause/resume dan breach detection
- Prioritas: Critical, High, Medium, Low
- Komentar internal (hanya tim IT) dan solusi
- Lampiran file (gambar, dokumen, log) hingga 5MB
- Audit trail lengkap
- Kategori hierarkis dengan ikon dan warna

### 2. 📚 Basis Pengetahuan (Knowledge Base)
- Artikel dengan versi dan changelog
- Voting system (Helpful / Not Helpful)
- Pencarian full-text (MySQL FULLTEXT)
- Kategori publik & internal (hanya staf IT)
- Artikel unggulan (featured)
- Upload gambar via CKEditor
- Link artikel ke tiket terkait

### 3. 🖥️ Manajemen Aset
- Jenis aset: Hardware, Software, Network
- Lifecycle: Procurement → Inventory → Deployed → Maintenance → Retired → Disposed
- Depresiasi (straight-line, declining balance)
- Tracking garansi dan vendor
- Dokumen aset (faktur, sertifikat garansi, manual)
- Multi-image upload
- Link ke tiket layanan

### 4. 🔧 Pemeliharaan (Maintenance)
- Jadwal preventive maintenance (harian, mingguan, bulanan, tahunan)
- Checklist pelaksanaan dengan status pass/fail/N/A
- Upload foto dokumentasi (before, during, after, evidence)
- Approval workflow untuk biaya tinggi
- Evaluasi pasca-pemeliharaan dengan rating 1-5 bintang
- Inventaris suku cadang dengan stok minimum alert
- Notifikasi otomatis via Telegram

### 5. 📊 Pelaporan & Analitik
- Laporan tiket dengan filter lengkap
- Laporan kepatuhan SLA
- Laporan kinerja staf IT
- Laporan aset dan inventaris
- Riwayat pembuatan laporan
- Dashboard dengan statistik real-time

### 6. 📝 Permintaan Perbaikan Publik
- Form publik tanpa login
- Upload foto langsung dari kamera smartphone
- Kompresi otomatis ke WebP
- Verifikasi oleh IT Manager sebelum jadi tiket
- Konversi otomatis ke tiket layanan
- CAPTCHA matematika untuk validasi manusia

### 7. 🔐 Autentikasi & Otorisasi
- Login dengan email/password
- Paksa ganti password pertama kali
- Role-based access control (4 role)
- Permission berbasis JSON
- Session management

### 8. 🏢 Manajemen Departemen
- Struktur hierarkis (parent/child)
- Assignment manager
- Tracking pengguna per departemen

---

## 🔄 Alur Fitur

### Alur Permintaan Perbaikan Publik → Tiket
```
Pengisi Form Publik → Isi Form + Upload Foto → Submit (CAPTCHA)
    ↓
Status: SUBMITTED
    ↓
IT Manager Verifikasi
    ├── Ditolak → Status: REJECTED + Email notifikasi
    └── Disetujui → Status: APPROVED
        ↓
IT Manager Konversi ke Tiket
    ↓
Status: CONVERTED → Tiket Baru Terbuka (Open)
    ↓
Ditugaskan ke IT Staff → Status: IN_PROGRESS
    ↓
Selesai → Status: RESOLVED → CLOSED
```

### Alur Tiket Layanan
```
Pembuatan Tiket (Manual/Auto)
    ↓
Open → Ditugaskan ke Staff
    ↓
In Progress → Kerjakan
    ↓
Selesai → Resolved
    ↓
User Konfirmasi → Closed
    ↓
(Tidak puas) → Reopened → In Progress
```

### Alur Pemeliharaan Preventif
```
Buat Jadwal → Pilih Frekuensi & Checklist
    ↓
Generate Tasks (Manual/Otomatis)
    ↓
Task: Scheduled → In Progress
    ↓
Isi Checklist + Upload Foto + Catat Spare Part
    ↓
Jika biaya > threshold → Approval Required
    ├── Approved → Lanjut
    └── Rejected → Cancel
    ↓
Completed → Evaluasi (Rating 1-5)
    ↓
Rekomendasi pemeliharaan berikutnya
```

### Alur Artikel Basis Pengetahuan
```
Buat Artikel (Draft)
    ↓
Review → Published
    ↓
User Vote (Helpful/Not Helpful)
    ↓
Update Artikel → Versi Baru + Changelog
    ↓
Arsip → Archived
```

---

## 🛠️ Teknologi yang Digunakan

| Kategori | Teknologi |
|----------|-----------|
| **Backend** | Laravel 13, PHP 8.3+ |
| **Frontend** | Tailwind CSS 4, Alpine.js, Vite |
| **Database** | MySQL 8+ |
| **Image Processing** | Intervention Image 4 |
| **Editor** | CKEditor 5 |
| **Notification** | Telegram Bot API |
| **Captcha** | Custom Math CAPTCHA |
| **Scheduler** | Laravel Task Scheduling |

---

## 💻 Persyaratan Sistem

- PHP 8.3 atau lebih tinggi
- MySQL 8.0+ atau MariaDB 10.6+
- Composer
- Node.js 18+ & NPM
- Extension PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD, Exif
- Server dengan minimum 2GB RAM

---

## 📦 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/your-username/pantauite.git
cd pantauite
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database
```bash
# Edit .env sesuai database Anda
DB_DATABASE=pantauite
DB_USERNAME=root
DB_PASSWORD=

# Jalankan migrasi
php artisan migrate
```

### 5. Build Assets
```bash
npm run build
```

### 6. Jalankan Aplikasi
```bash
# Development
php artisan serve
npm run dev

# Production
php artisan optimize
php artisan storage:link
```

### 7. Setup Scheduler (Untuk Notifikasi Otomatis)
```bash
# Tambahkan ke crontab
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚙️ Konfigurasi

### Telegram Notification
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

---

## 👥 Role & Hak Akses

| Role | Deskripsi | Hak Akses |
|------|-----------|-----------|
| **Super Admin** | Administrator penuh | Semua akses, termasuk manajemen user |
| **IT Manager** | Manajer TI | Verifikasi permintaan, kelola tiket, aset, KB, laporan, departemen |
| **IT Staff** | Staf TI | Kerjakan tiket, kelola aset, KB, pemeliharaan |
| **End User** | Pengguna akhir | Buat tiket, lihat tiket sendiri, akses KB publik |

### Matriks Permission

| Permission | Super Admin | IT Manager | IT Staff | End User |
|------------|:-----------:|:----------:|:--------:|:--------:|
| `manage-tickets` | ✅ | ✅ | ✅ | ❌ |
| `manage-kb` | ✅ | ✅ | ✅ | ❌ |
| `manage-assets` | ✅ | ✅ | ✅ | ❌ |
| `manage-departments` | ✅ | ✅ | ❌ | ❌ |
| `view-reports` | ✅ | ✅ | ❌ | ❌ |
| `manage-users` | ✅ | ❌ | ❌ | ❌ |

---

## 🗄️ Struktur Database

### Tabel Utama (39 Migrations)

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna sistem |
| `roles` | Role & permissions (JSON) |
| `departments` | Departemen organisasi |
| `tickets` | Tiket layanan utama |
| `ticket_categories` | Kategori tiket |
| `ticket_comments` | Komentar pada tiket |
| `ticket_attachments` | Lampiran file tiket |
| `ticket_audit_logs` | Audit trail tiket |
| `sla_policies` | Kebijakan SLA |
| `kb_articles` | Artikel basis pengetahuan |
| `kb_categories` | Kategori KB |
| `kb_article_votes` | Voting artikel |
| `assets` | Aset TI |
| `asset_documents` | Dokumen aset |
| `asset_lifecycle_logs` | Log lifecycle aset |
| `vendors` | Vendor/Pemasok |
| `maintenance_schedules` | Jadwal pemeliharaan |
| `maintenance_tasks` | Tugas pemeliharaan |
| `maintenance_checklist_items` | Item checklist |
| `maintenance_checklist_results` | Hasil checklist |
| `maintenance_requirements` | Kebutuhan pemeliharaan |
| `maintenance_evaluations` | Evaluasi pemeliharaan |
| `maintenance_photos` | Foto pemeliharaan |
| `maintenance_approvals` | Approval pemeliharaan |
| `inventory_parts` | Suku cadang inventaris |
| `inventory_transactions` | Transaksi stok |
| `repair_requests` | Permintaan perbaikan publik |
| `repair_request_photos` | Foto permintaan perbaikan |
| `report_runs` | Riwayat laporan |

---

## 📸 Screenshot

*(Tambahkan screenshot aplikasi di sini)*

---

## 🚀 Development

### Menambahkan Fitur Baru
```bash
# Buat model, migration, controller
php artisan make:model NewFeature -mc

# Buat form request
php artisan make:request StoreNewFeatureRequest

# Buat policy
php artisan make:policy NewFeaturePolicy
```

### Testing
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

---

## 📄 Lisensi

PantauITE dilisensikan di bawah [MIT License](LICENSE).

---

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📞 Support

Untuk dukungan teknis atau pertanyaan, silakan hubungi tim pengembang atau buat issue di repository.

---

**Dibuat dengan ❤️ menggunakan Laravel 13**
