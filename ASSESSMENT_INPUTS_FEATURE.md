# Fitur Detail Inputan Peserta Assessment

## Deskripsi
Fitur ini memungkinkan admin untuk melihat detail inputan peserta untuk setiap assessment dengan kemampuan filter, paging, dan export CSV.

## Fitur Utama

### 1. Tampilan Data
- Menampilkan semua inputan peserta dari berbagai jenis assessment:
  - Studi Kasus (jawaban text)
  - In-Tray Exercise (prioritas dan disposisi)
  - Role-Play (catatan)
  - FGD (catatan)
- Informasi yang ditampilkan:
  - Nama peserta, instansi, jabatan
  - Nama assessment dan jenis
  - Sesi penilaian
  - Jawaban/catatan peserta
  - Status inputan
  - Waktu simpan

### 2. Filter
- **Nama Peserta**: Pencarian berdasarkan nama lengkap peserta
- **Instansi**: Filter berdasarkan instansi peserta
- **Assessment**: Filter berdasarkan assessment tertentu
- **Jenis Input**: Filter berdasarkan jenis assessment (Studi Kasus, In-Tray, Role-Play, FGD)

### 3. Paging
- Tabel menerapkan pagination dengan 15 data per halaman (dapat disesuaikan)
- Navigasi halaman dengan link Previous/Next
- Informasi jumlah data yang ditampilkan

### 4. Export CSV
- Export semua data yang sudah difilter ke format CSV
- Pilihan delimiter: Semicolon (;) atau Comma (,)
- File CSV dengan encoding UTF-8 dan BOM untuk kompatibilitas Excel
- Nama file otomatis dengan timestamp

### 5. Keamanan
- Hanya dapat diakses oleh admin (menggunakan middleware `admin`)
- Terintegrasi dengan sistem autentikasi Laravel

## File yang Dibuat/Dimodifikasi

### Controller
- `app/Http/Controllers/Admin/AssessmentInputController.php`
  - Method `index()`: Menampilkan data dengan filter dan paging
  - Method `export()`: Export data ke CSV
  - Method `buildQuery()`: Membangun query dengan filter
  - Method `getFilters()`: Mengambil parameter filter dari request
  - Method `formatAnswer()`: Format jawaban untuk display

### View
- `resources/views/admin/assessment-inputs/index.blade.php`
  - Form filter dengan input nama, instansi, assessment, dan jenis
  - Tabel data dengan pagination
  - Tombol export CSV dengan pilihan delimiter
  - JavaScript untuk toggle jawaban panjang dan export

### Routes
- `routes/web.php`
  - `GET /admin/assessment-inputs` → `AssessmentInputController@index`
  - `GET /admin/assessment-inputs/export` → `AssessmentInputController@export`

### Navigation
- `resources/views/admin/layouts/app.blade.php`
  - Menambahkan link "Input Assessment" di menu navigasi admin

## Cara Menggunakan

1. **Akses Fitur**
   - Login sebagai admin
   - Klik menu "Input Assessment" di navigasi admin

2. **Filter Data**
   - Isi form filter sesuai kebutuhan
   - Klik tombol "Filter" untuk menerapkan filter
   - Klik "Reset" untuk menghapus semua filter

3. **Export Data**
   - Pilih delimiter CSV (semicolon atau comma)
   - Klik tombol "Export CSV"
   - File akan otomatis terdownload

4. **Navigasi Data**
   - Gunakan pagination di bawah tabel untuk navigasi halaman
   - Klik "lihat selengkapnya" untuk jawaban yang panjang

## Teknis

### Database Query
- Menggunakan UNION query untuk menggabungkan data dari 4 tabel:
  - `jawaban_studi_kasus`
  - `jawaban_in_tray`
  - `catatan_roleplay`
  - `catatan_fgd`
- Join dengan tabel `peserta`, `penilaian`, dan `sesi_penilaian`

### Performance
- Query dioptimasi dengan index pada kolom yang sering difilter
- Pagination untuk menghindari loading data yang terlalu banyak
- Lazy loading untuk relasi model

### Responsive Design
- Menggunakan Tailwind CSS untuk styling
- Tabel responsive dengan horizontal scroll pada layar kecil
- Form filter yang responsif

## Catatan Penting

1. **Data In-Tray**: Menampilkan prioritas, disposisi, dan konten memo
2. **Jawaban Panjang**: Otomatis dipotong dengan opsi "lihat selengkapnya"
3. **Export**: File CSV menggunakan UTF-8 dengan BOM untuk kompatibilitas Excel
4. **Security**: Semua route dilindungi dengan middleware admin
5. **Error Handling**: Graceful handling untuk data kosong atau error

## Perbaikan yang Dilakukan

### Masalah yang Ditemukan
- Error "site can't be reach" pada export CSV
- Error "Call to a member function format() on string" pada line 94 controller
- Tabel `jawaban_studi_kasus` kosong menyebabkan query union gagal

### Solusi yang Diterapkan
1. **Perbaikan Format Tanggal**: Menggunakan `\Carbon\Carbon::parse()` untuk parsing string tanggal
2. **Optimasi Query**: Membuat query yang lebih robust untuk menangani tabel kosong
3. **Error Handling**: Menambahkan COALESCE untuk field yang mungkin null
4. **Dynamic Query Building**: Hanya membangun query untuk tabel yang memiliki data

### Hasil Testing
- ✅ Export CSV berfungsi dengan delimiter semicolon (;) dan comma (,)
- ✅ Data berhasil dieksport dengan format yang benar
- ✅ UTF-8 encoding dengan BOM untuk kompatibilitas Excel
- ✅ Filter dan paging berfungsi dengan baik
- ✅ Middleware admin melindungi akses fitur

## Dependencies
- Laravel Framework
- Tailwind CSS (untuk styling)
- Carbon (untuk format tanggal)
- PHP built-in CSV functions
