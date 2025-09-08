# Panduan Simulasi Assessment Center

## Deskripsi
Dokumen ini menjelaskan cara menjalankan simulasi 1 cycle lengkap assessment center yang mencakup 4 jenis penilaian: Studi Kasus, In-Tray Exercise, Roleplay, dan FGD.

## Struktur Data yang Dibuat

### 1. User dan Peserta
- **Admin**: admin@assessment.com (password: password)
- **Peserta**: 5 orang dengan data lengkap
  - Ahmad Rizki (Manager Marketing)
  - Siti Nurhaliza (Supervisor HR)
  - Budi Santoso (Team Leader IT)
  - Dewi Sartika (Senior Analyst)
  - Eko Prasetyo (Project Manager)

### 2. Sesi Penilaian
- 1 sesi assessment center dengan durasi 60 menit
- Status: active (sedang berlangsung)

### 3. Jenis Penilaian (4 jenis)
1. **Studi Kasus** - Manajemen Konflik (30 menit)
2. **In-Tray Exercise** - Prioritas Manajemen (45 menit)
3. **Roleplay** - Negosiasi Tim (20 menit)
4. **FGD** - Strategi Digitalisasi (25 menit)

### 4. Item Penilaian
- **Studi Kasus**: 2 item (Analisis Situasi Perusahaan, Manajemen Konflik Tim)
- **In-Tray**: 1 item (Memo Prioritas) + 5 memo latihan
- **Roleplay**: 2 item (Presentasi kepada Direksi, Negosiasi dengan Vendor)
- **FGD**: 2 item (Strategi Digital Transformation, Work-Life Balance)

### 5. Data Simulasi
- **Kemajuan Penilaian**: Status random (belum_mulai, sedang_berlangsung, selesai)
- **Jawaban Studi Kasus**: 70% peserta sudah menjawab
- **Jawaban In-Tray**: 60% peserta sudah menjawab + 5 memo dengan disposisi
- **Catatan Roleplay**: 50% peserta sudah melakukan roleplay
- **Catatan FGD**: 40% peserta sudah berpartisipasi

## Cara Menjalankan Simulasi

### 1. Setup Database
```bash
# Pastikan database sudah ter-setup
php artisan migrate:fresh

# Jalankan semua seeder
php artisan db:seed
```

### 2. Login sebagai Admin
- Email: admin@assessment.com
- Password: password
- Role: admin

### 3. Login sebagai Peserta
- Email: [email peserta]@example.com
- Password: password
- PIN: [6 digit PIN sesuai data]

## Skenario Simulasi

### Skenario 1: Peserta Baru Mulai Assessment
1. Login sebagai peserta
2. Masukkan PIN
3. Mulai assessment dari step pertama (Studi Kasus)
4. Kerjakan item penilaian sesuai durasi
5. Lanjut ke step berikutnya

### Skenario 2: Peserta Sedang Berlangsung
1. Login sebagai peserta yang sedang mengerjakan
2. Lanjutkan assessment yang sedang berlangsung
3. Lihat progress dan waktu tersisa
4. Selesaikan assessment

### Skenario 3: Peserta Selesai Assessment
1. Login sebagai peserta yang sudah selesai
2. Lihat hasil dan review jawaban
3. Lihat catatan dari assessor

### Skenario 4: Admin Monitoring
1. Login sebagai admin
2. Monitor progress semua peserta
3. Lihat real-time status assessment
4. Review jawaban dan berikan catatan

## Fitur yang Dapat Diuji

### 1. Manajemen Sesi
- ✅ Buat sesi assessment
- ✅ Set status sesi (pending, active, paused, completed)
- ✅ Monitor durasi dan waktu

### 2. Assessment Flow
- ✅ Stepper navigation antar jenis penilaian
- ✅ Timer countdown untuk setiap assessment
- ✅ Progress tracking per peserta

### 3. Jenis Penilaian
- ✅ **Studi Kasus**: Text input dengan validasi
- ✅ **In-Tray**: Drag & drop memo + disposisi
- ✅ **Roleplay**: Video recording + catatan assessor
- ✅ **FGD**: Group discussion + individual notes

### 4. Data Management
- ✅ Auto-save draft answers
- ✅ Final submission
- ✅ Progress tracking
- ✅ Assessor notes

## Troubleshooting

### 1. Database Error
```bash
# Reset database dan jalankan ulang
php artisan migrate:fresh --seed
```

### 2. Seeder Error
```bash
# Jalankan seeder satu per satu
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SesiPenilaianSeeder
php artisan db:seed --class=PenilaianSeeder
php artisan db:seed --class=ItemPenilaianSeeder
php artisan db:seed --class=PesertaSeeder
php artisan db:seed --class=KemajuanPenilaianSeeder
php artisan db:seed --class=JawabanSeeder
```

### 3. Data Tidak Muncul
- Pastikan semua seeder berhasil dijalankan
- Check database connection
- Verify model relationships

## Catatan Penting

1. **Urutan Seeder**: Harus dijalankan sesuai urutan karena ada foreign key constraints
2. **Data Realistic**: Semua data dibuat realistic untuk simulasi assessment center
3. **Status Random**: Status kemajuan dibuat random untuk variasi testing
4. **Timestamps**: Waktu dibuat relatif terhadap waktu sekarang untuk realism

## Support

Jika ada masalah atau pertanyaan, silakan buat issue atau hubungi tim development.
