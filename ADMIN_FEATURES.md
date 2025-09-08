# 🎯 **Fitur Admin Assessment Center - Dokumentasi Lengkap**

## 📋 **Daftar Isi**
1. [Overview](#overview)
2. [Struktur File](#struktur-file)
3. [Cara Penggunaan](#cara-penggunaan)
4. [Fitur yang Tersedia](#fitur-yang-tersedia)
5. [Troubleshooting](#troubleshooting)
6. [API Endpoints](#api-endpoints)

## 🚀 **Overview**

Fitur Admin Assessment Center adalah sistem manajemen lengkap untuk mengelola assessment center yang mencakup:
- Dashboard monitoring real-time
- Manajemen sesi assessment
- Manajemen peserta
- Monitoring progress assessment
- Review jawaban dan catatan
- Export data progress

## 📁 **Struktur File**

### **Controllers**
```
app/Http/Controllers/
├── AdminController.php          # Controller utama admin
├── AuthController.php           # Controller autentikasi
└── Controller.php               # Base controller
```

### **Middleware**
```
app/Http/Middleware/
└── AdminMiddleware.php          # Middleware untuk akses admin
```

### **Views**
```
resources/views/
├── admin/
│   ├── layouts/
│   │   └── app.blade.php       # Layout utama admin
│   ├── dashboard.blade.php      # Dashboard admin
│   ├── sesi/
│   │   └── index.blade.php     # Manajemen sesi
│   └── progress/
│       └── index.blade.php     # Monitoring progress
└── auth/
    ├── login.blade.php          # Login admin/peserta
    └── participant-login.blade.php # Login dengan PIN
```

### **Routes**
```
routes/web.php                   # Semua routes admin dan auth
```

## 🔐 **Cara Penggunaan**

### **1. Login sebagai Admin**
- **URL**: `/login`
- **Email**: `admin@assessment.com`
- **Password**: `password`
- **Redirect**: `/admin/dashboard`

### **2. Login sebagai Peserta**
- **URL**: `/participant/login`
- **PIN**: `123456` (atau PIN lainnya sesuai data)
- **Redirect**: `/peserta/dashboard`

### **3. Setup Database**
```bash
# Reset dan seed database
php artisan migrate:fresh --seed

# Atau jalankan script otomatis
./run_simulation.bat    # Windows
./run_simulation.sh     # Linux/Mac
```

## ⚡ **Fitur yang Tersedia**

### **1. Dashboard Admin** (`/admin/dashboard`)
- **Statistik Real-time**:
  - Total peserta
  - Total sesi
  - Total penilaian
  - Status sesi aktif
- **Progress Assessment**:
  - Progress per jenis assessment
  - Visual progress bar
  - Statistik status (belum mulai, sedang berlangsung, selesai)
- **Sesi Aktif**:
  - Info sesi yang sedang berlangsung
  - Waktu mulai dan durasi
  - Quick action untuk kelola sesi
- **Aktivitas Terbaru**:
  - 10 aktivitas terbaru dari semua jenis assessment
  - Timestamp dan status
- **Quick Actions**:
  - Kelola Peserta
  - Monitor Progress
  - Export Data

### **2. Manajemen Sesi** (`/admin/sesi`)
- **CRUD Sesi**:
  - Buat sesi baru
  - Edit sesi existing
  - Hapus sesi
- **Kontrol Sesi**:
  - Start sesi (pending → active)
  - Pause sesi (active → paused)
  - Resume sesi (paused → active)
  - Complete sesi (active → completed)
- **Info Sesi**:
  - Nama dan catatan
  - Durasi (menit)
  - Status real-time
  - Waktu mulai/selesai
  - Jumlah penilaian

### **3. Manajemen Peserta** (`/admin/peserta`)
- **Data Peserta**:
  - Biodata lengkap
  - Informasi jabatan dan grade
  - Status aktif/nonaktif
- **Aksi**:
  - Lihat detail peserta
  - Edit data peserta
  - Hapus peserta
- **Link dengan User**:
  - Relasi dengan tabel users
  - Role-based access

### **4. Monitoring Progress** (`/admin/progress`)
- **Tabel Progress**:
  - Progress semua peserta per assessment
  - Status real-time (belum mulai, sedang berlangsung, selesai)
  - Waktu mulai dan selesai
- **Update Status**:
  - Button Start (belum mulai → sedang berlangsung)
  - Button Complete (sedang berlangsung → selesai)
- **Statistik Progress**:
  - Total progress
  - Count per status
  - Visual summary
- **Export Data**:
  - Download CSV progress assessment
  - Format: Nama, Assessment, Status, Waktu, Durasi

### **5. Review Jawaban** (`/admin/review/*`)
- **Studi Kasus**:
  - Review jawaban semua peserta
  - Status submission
  - Timestamp
- **In-Tray Exercise**:
  - Review disposisi memo
  - Grouped by peserta
  - Link dengan latihan in-tray
- **Roleplay**:
  - Review catatan roleplay
  - Status dan timestamp
- **FGD**:
  - Review catatan FGD
  - Status dan timestamp

## 🔧 **Troubleshooting**

### **Error: "Call to undefined method middleware()"**
**Penyebab**: Controller tidak extend dari base Controller yang proper
**Solusi**: Pastikan `AdminController` extend dari `Controller` dan `Controller` extend dari `BaseController`

### **Error: "Route not found"**
**Penyebab**: Routes tidak terdaftar dengan benar
**Solusi**: Jalankan `php artisan route:list --name=admin` untuk verifikasi

### **Error: "Class not found"**
**Penyebab**: Namespace atau autoload tidak sesuai
**Solusi**: Jalankan `composer dump-autoload`

### **Error: "Middleware admin not found"**
**Penyebab**: Middleware tidak terdaftar di `bootstrap/app.php`
**Solusi**: Pastikan middleware admin sudah terdaftar

## 🌐 **API Endpoints**

### **Authentication Routes**
```
GET    /login                    # Show login form
POST   /login                    # Handle login
POST   /logout                   # Handle logout
GET    /participant/login        # Show participant login
POST   /participant/login        # Handle participant login
```

### **Admin Routes**
```
GET    /admin/dashboard          # Dashboard admin
GET    /admin/sesi              # List sesi
GET    /admin/sesi/create       # Create sesi form
POST   /admin/sesi              # Store sesi
GET    /admin/sesi/{id}/edit    # Edit sesi form
PUT    /admin/sesi/{id}         # Update sesi
DELETE /admin/sesi/{id}         # Delete sesi
POST   /admin/sesi/{id}/start   # Start sesi
POST   /admin/sesi/{id}/pause   # Pause sesi
POST   /admin/sesi/{id}/complete # Complete sesi

GET    /admin/peserta           # List peserta
GET    /admin/peserta/{id}      # Show peserta
GET    /admin/peserta/{id}/edit # Edit peserta form
PUT    /admin/peserta/{id}      # Update peserta
DELETE /admin/peserta/{id}      # Delete peserta

GET    /admin/progress          # Progress index
GET    /admin/progress/{id}     # Progress peserta
PUT    /admin/progress/{id}/status # Update status
GET    /admin/progress/export   # Export progress

GET    /admin/review/studi-kasus/{id}    # Review studi kasus
GET    /admin/review/in-tray/{id}        # Review in-tray
GET    /admin/review/roleplay/{id}       # Review roleplay
GET    /admin/review/fgd/{id}            # Review FGD
```

### **Peserta Routes**
```
GET    /peserta/dashboard       # Dashboard peserta
GET    /peserta/biodata         # Biodata peserta
GET    /peserta/penilaian/{id}  # Assessment peserta
POST   /peserta/logout          # Logout peserta
```

## 📱 **Responsive Design**

- **Mobile-first approach** dengan Tailwind CSS
- **Grid system** yang adaptif
- **Touch-friendly** buttons dan forms
- **Responsive tables** dengan horizontal scroll
- **Modern UI/UX** dengan shadows dan transitions

## 🔒 **Security Features**

- **Role-based access control** (Admin vs Peserta)
- **Middleware protection** untuk semua routes admin
- **CSRF protection** untuk semua forms
- **Session management** yang aman
- **Input validation** untuk semua user inputs

## 🚀 **Performance Features**

- **Eager loading** untuk relationships
- **Database indexing** untuk queries yang sering
- **Caching** untuk data yang statis
- **Lazy loading** untuk komponen yang tidak critical
- **Optimized queries** dengan proper joins

## 📊 **Monitoring & Analytics**

- **Real-time progress tracking**
- **Activity logging** untuk audit trail
- **Performance metrics** per assessment
- **Export capabilities** untuk reporting
- **Dashboard analytics** dengan visual charts

## 🔄 **Maintenance & Updates**

### **Update Status Assessment**
```javascript
// JavaScript untuk update status real-time
function updateStatus(kemajuanId, newStatus) {
    fetch(`/admin/progress/${kemajuanId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
```

### **Export Data**
```php
// Export progress ke CSV
Route::get('/admin/progress/export', [AdminController::class, 'exportProgress'])
    ->name('admin.progress.export');
```

## 📝 **Notes**

- Semua fitur admin memerlukan login dengan role 'admin'
- Middleware admin akan redirect ke login jika tidak authenticated
- Data export tersedia dalam format CSV
- Progress monitoring real-time dengan AJAX updates
- Responsive design untuk semua device sizes

---

**Dibuat oleh**: AI Assistant  
**Versi**: 1.0  
**Tanggal**: 2024  
**Framework**: Laravel 11 + Tailwind CSS
