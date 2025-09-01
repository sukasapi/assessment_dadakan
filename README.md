# Assessment Center

Aplikasi web "Assessment Center" untuk mengelola dan melaksanakan assessment kompetensi karyawan dengan fitur multi-step assessment dan timer yang dikendalikan admin.

## Teknologi

- **Backend**: Laravel 12
- **Database**: MySQL
- **Frontend**: Blade Templates + Tailwind CSS
- **Theme**: Video Game / Cyberpunk

## Fitur

### 👤 Fitur User (Peserta)

#### 1. Biodata
- User hanya bisa melihat biodata (read-only)
- Password peserta digenerate otomatis oleh sistem

#### 2. Assessment Multi-Step
- Peserta mengerjakan 4 jenis tes secara berurutan (stepper)
- Tiap jenis tes memiliki halaman/step sendiri
- User tidak bisa melompat ke step berikutnya sebelum step saat ini selesai
- Progress tracking dengan visual stepper

#### 3. Jenis Tes
- **Studi Kasus**: Soal narasi + jawaban text
- **In-Tray Exercise**: Urutkan 10-15 memo + berikan disposisi
- **Role-Play**: Instruksi/tugas (tanpa input jawaban)
- **FGD**: Instruksi/tugas (tanpa input jawaban)

#### 4. Timer & Auto-Save
- Timer yang dikendalikan admin
- Auto-save saat waktu habis
- Tombol "Simpan Sementara" dan "Simpan Final"
- Timestamp untuk setiap penyimpanan

### 🔧 Fitur Admin (Akan Dibangun)
- Manajemen peserta
- Setup assessment dan timer
- Monitoring progress peserta
- Download hasil assessment

## Alur Kerja

1. **Admin membuat data peserta** → password digenerate otomatis → tersimpan di tabel users
2. **Admin input soal** (tiap jenis tes) → tersimpan di tabel assessments dan assessment_items
3. **Peserta login** → masuk dashboard berisi tombol mulai test → diarahkan ke step pertama (Studi Kasus)
4. **Peserta menjawab** → jawaban tersimpan ke tabel answers
5. **Saat tekan Simpan** → status = draft + timestamp simpan
6. **Saat tekan Simpan Final** → status = final + timestamp final
7. **Stepper berjalan** → peserta lanjut ke step berikutnya
8. **Timer diset oleh admin** di tabel assessment_sessions
9. **Saat admin start timer**, semua peserta bisa mengerjakan
10. **Saat admin stop timer**, semua peserta otomatis dihentikan

## Setup & Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd assesmentdadakan
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Database
Buat file `.env` dengan konfigurasi:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assessment_center
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key
```bash
php artisan key:generate
```

### 5. Jalankan Migration
```bash
php artisan migrate
```

### 6. Jalankan Seeder
```bash
php artisan db:seed
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
npm run dev
```

## Data Sample

Aplikasi dilengkapi dengan data sample untuk 4 peserta:

### Peserta
1. **Ahmad Rizki** - PIN: 123456
2. **Siti Nurhaliza** - PIN: 234567  
3. **Budi Santoso** - PIN: 345678
4. **Dewi Sartika** - PIN: 456789

### Assessment Session
- **Assessment Center Batch 1 - 2024**
- Durasi: 120 menit
- Status: Pending (akan diaktifkan admin)

### Jenis Assessment
1. **Studi Kasus** - Manajemen Konflik (30 menit)
2. **In-Tray Exercise** - Prioritas Manajemen (45 menit)
3. **Role-Play** - Negosiasi dengan Klien (20 menit)
4. **FGD** - Inovasi dalam Pelayanan (25 menit)

## Struktur Database

### Tabel Utama
- `users` - Akun user (admin/participant)
- `participants` - Biodata peserta
- `assessment_sessions` - Session assessment dan timer
- `assessments` - Jenis-jenis assessment
- `assessment_items` - Item soal individual
- `assessment_progress` - Progress peserta per assessment

### Tabel Jawaban
- `case_study_answers` - Jawaban studi kasus
- `in_tray_answers` - Jawaban in-tray exercise
- `roleplay_notes` - Catatan role-play
- `fgd_notes` - Catatan FGD

## Routes

### Participant Routes
- `GET /participant/login` - Halaman login
- `POST /participant/login` - Proses login
- `GET /participant/dashboard` - Dashboard peserta
- `GET /participant/biodata` - Halaman biodata
- `GET /participant/assessment/{id}` - Halaman assessment
- `POST /participant/logout` - Logout

### Assessment Routes
- `POST /assessment/{id}/case-study` - Simpan jawaban studi kasus
- `POST /assessment/{id}/in-tray` - Simpan jawaban in-tray
- `POST /assessment/{id}/roleplay` - Simpan catatan role-play
- `POST /assessment/{id}/fgd` - Simpan catatan FGD

## Fitur Keamanan

- Session-based authentication
- CSRF protection
- Input validation
- Role-based access control (akan diimplementasikan)

## UI/UX Features

- **Responsive Design** - Mobile-friendly
- **Cyberpunk Theme** - Video game aesthetic
- **Real-time Timer** - Countdown dengan visual feedback
- **Progress Stepper** - Visual progress tracking
- **Auto-save** - Mencegah kehilangan data
- **Modern Interface** - Clean dan intuitive

## Development Notes

- Aplikasi menggunakan Laravel 12 dengan fitur terbaru
- Frontend menggunakan Tailwind CSS untuk styling
- JavaScript vanilla untuk interaktivitas
- Database MySQL dengan relasi yang proper
- Seeder untuk data testing dan development

## Kontribusi

Untuk berkontribusi pada project ini:
1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Buat Pull Request

## License

MIT License - lihat file LICENSE untuk detail.
