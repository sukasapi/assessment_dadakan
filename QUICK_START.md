# 🚀 Quick Start - Assessment Center Simulation

## ⚡ 1-Minute Setup

### Windows
```bash
# Double click file ini
run_simulation.bat
```

### Linux/Mac
```bash
# Make executable & run
chmod +x run_simulation.sh
./run_simulation.sh
```

### Manual
```bash
php artisan migrate:fresh --seed
```

## 🔑 Login Credentials

### Admin
- **Email**: admin@assessment.com
- **Password**: password

### Peserta
- **Email**: [nama]@example.com
- **Password**: password
- **PIN**: [6 digit]

| Nama | Email | PIN |
|------|-------|-----|
| Ahmad Rizki | ahmad.rizki@example.com | 123456 |
| Siti Nurhaliza | siti.nurhaliza@example.com | 234567 |
| Budi Santoso | budi.santoso@example.com | 345678 |
| Dewi Sartika | dewi.sartika@example.com | 456789 |
| Eko Prasetyo | eko.prasetyo@example.com | 567890 |

## 📊 What You Get

✅ **6 Users** (1 admin + 5 peserta)  
✅ **1 Sesi Assessment** (60 menit)  
✅ **4 Jenis Penilaian** (Studi Kasus, In-Tray, Roleplay, FGD)  
✅ **7 Item Penilaian** dengan konten realistic  
✅ **20 Progress Records** dengan status random  
✅ **Sample Answers** untuk testing  

## 🎯 Test Scenarios

### 1. Admin Dashboard
- Login sebagai admin
- Monitor progress semua peserta
- Manage sesi assessment

### 2. Participant Flow
- Login dengan PIN
- Kerjakan assessment step by step
- Lihat progress dan timer

### 3. Assessment Types
- **Studi Kasus**: Text input dengan 2 item
- **In-Tray**: 5 memo dengan disposisi
- **Roleplay**: 2 skenario dengan catatan
- **FGD**: 2 topik diskusi

## 🛠️ Troubleshooting

### Data Tidak Muncul?
```bash
php artisan migrate:fresh --seed
```

### Seeder Error?
```bash
# Jalankan satu per satu
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PenilaianSeeder
# dst...
```

### Route Error?
```bash
php artisan route:clear
php artisan config:clear
```

## 📚 More Info

- **Full Guide**: `SIMULASI_README.md`
- **Data Summary**: `DATA_SUMMARY.md`
- **API Testing**: `TEST_ROUTES.md`

## 🎉 Ready to Test!

Setelah setup selesai, Anda bisa:
1. Login sebagai admin untuk monitoring
2. Login sebagai peserta untuk assessment
3. Test semua fitur assessment center
4. Simulasi berbagai skenario

**Happy Testing! 🚀**
