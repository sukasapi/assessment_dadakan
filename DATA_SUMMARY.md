# Data Summary - Assessment Center Simulation

## Quick Reference

### Admin Access
- **Email**: admin@assessment.com
- **Password**: password
- **Role**: admin

### Peserta Data

| No | Nama | Email | PIN | Jabatan | Grade |
|----|------|-------|-----|---------|-------|
| 1 | Ahmad Rizki | ahmad.rizki@example.com | 123456 | Manager Marketing | IV |
| 2 | Siti Nurhaliza | siti.nurhaliza@example.com | 234567 | Supervisor HR | III |
| 3 | Budi Santoso | budi.santoso@example.com | 345678 | Team Leader IT | III |
| 4 | Dewi Sartika | dewi.sartika@example.com | 456789 | Senior Analyst | IV |
| 5 | Eko Prasetyo | eko.prasetyo@example.com | 567890 | Project Manager | V |

**Password untuk semua peserta**: password

### Assessment Structure

#### 1. Studi Kasus (30 menit)
- **Item 1**: Analisis Situasi Perusahaan
- **Item 2**: Manajemen Konflik Tim

#### 2. In-Tray Exercise (45 menit)
- **Item**: Memo Prioritas
- **Memo Latihan**: 5 memo dengan berbagai tingkat urgensi

#### 3. Roleplay (20 menit)
- **Item 1**: Presentasi kepada Direksi
- **Item 2**: Negosiasi dengan Vendor

#### 4. FGD (25 menit)
- **Item 1**: Strategi Digital Transformation
- **Item 2**: Work-Life Balance di Era Digital

### Data Statistics
- **Total Users**: 6 (1 admin + 5 peserta)
- **Total Sesi**: 1
- **Total Penilaian**: 4 jenis
- **Total Item**: 7 item
- **Total Kemajuan**: 20 records (4 penilaian × 5 peserta)
- **Jawaban Studi Kasus**: 3 (70% completion rate)
- **Jawaban In-Tray**: 10 (60% completion rate)
- **Catatan Roleplay**: 4 (50% completion rate)
- **Catatan FGD**: 2 (40% completion rate)

### Sample Scenarios

#### Scenario 1: New Participant
- Login: ahmad.rizki@example.com / password
- PIN: 123456
- Status: belum_mulai untuk semua assessment

#### Scenario 2: Active Participant
- Login: siti.nurhaliza@example.com / password
- PIN: 234567
- Status: sedang_berlangsung untuk studi_kasus

#### Scenario 3: Completed Participant
- Login: budi.santoso@example.com / password
- PIN: 345678
- Status: selesai untuk beberapa assessment

### Quick Commands

```bash
# Reset dan jalankan simulasi
php artisan migrate:fresh --seed

# Jalankan seeder tertentu
php artisan db:seed --class=UserSeeder

# Check data count
php artisan tinker --execute="echo 'Users: ' . App\Models\User::count();"

# Windows
run_simulation.bat

# Linux/Mac
chmod +x run_simulation.sh
./run_simulation.sh
```

### Notes
- Semua timestamps dibuat relatif terhadap waktu sekarang
- Status kemajuan dibuat random untuk variasi testing
- Data dibuat realistic untuk simulasi assessment center
- Foreign key constraints sudah dihandle dengan benar
