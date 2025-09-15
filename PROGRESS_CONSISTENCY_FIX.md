# Perbaikan Konsistensi Progress Admin dan User

## Masalah
Progress yang ditampilkan di halaman admin (/admin/progress) tidak sama dengan progress di dashboard user. Admin menampilkan progress dari sesi lain yang seharusnya tidak ditampilkan.

## Root Cause
**Dashboard User (Benar):**
- Menggunakan kombinasi `penilaian_id` dan `sesi_penilaian_id`
- Progress map dibuat dengan key `$item->penilaian_id . '_' . $item->sesi_penilaian_id`

**Admin Progress (Salah):**
- Hanya menggunakan `penilaian_id` saja
- Query: `KemajuanPenilaian::where('peserta_id',$peserta->id)->where('penilaian_id',$penilaianId)->first()`
- Ini menyebabkan progress dari sesi lain ikut ditampilkan

## Perbaikan yang Dilakukan

### 1. View Admin Progress (`resources/views/admin/progress/index.blade.php`)
**Sebelum:**
```php
$prog = \App\Models\KemajuanPenilaian::where('peserta_id',$peserta->id)
    ->where('penilaian_id',$penilaianId)
    ->first();
```

**Sesudah:**
```php
$prog = \App\Models\KemajuanPenilaian::where('peserta_id',$peserta->id)
    ->where('penilaian_id',$penilaianId)
    ->where('sesi_penilaian_id',$sesi->id)  // Tambahan filter sesi
    ->first();
```

### 2. Model KemajuanPenilaian (`app/Models/KemajuanPenilaian.php`)
**Ditambahkan relasi:**
```php
public function sesiPenilaian(): BelongsTo
{
    return $this->belongsTo(SesiPenilaian::class);
}
```

### 3. Controller AdminController (`app/Http/Controllers/AdminController.php`)
**Disederhanakan:**
```php
public function progressIndex()
{
    // Progress sudah dihitung langsung di view dengan logika yang sama seperti dashboard user
    return view('admin.progress.index');
}
```

## Hasil Perbaikan

### ✅ **Konsistensi Data:**
- Progress admin sekarang sama dengan dashboard user
- Progress berdasarkan kombinasi `penilaian_id` dan `sesi_penilaian_id`
- Tidak ada lagi progress dari sesi lain yang ikut ditampilkan

### ✅ **Akurasi Status:**
- Status "belum" untuk assessment yang belum dimulai
- Status "draft" untuk assessment yang sedang berlangsung
- Status "selesai" untuk assessment yang sudah selesai
- Status "tidak tersedia" untuk assessment yang tidak ada di sesi

### ✅ **Isolasi Sesi:**
- Setiap sesi menampilkan progress yang benar-benar sesuai
- Progress dari sesi lain tidak mempengaruhi tampilan

## Testing
- ✅ Progress admin sama dengan dashboard user
- ✅ Tidak ada progress dari sesi lain yang ikut ditampilkan
- ✅ Status badge menampilkan informasi yang akurat
- ✅ Relasi model berfungsi dengan benar

## Catatan Penting
- Perbaikan ini memastikan konsistensi data antara admin dan user
- Progress sekarang benar-benar berdasarkan sesi yang sedang dilihat
- Tidak ada perubahan pada logika dashboard user (yang sudah benar)
- Admin progress sekarang mengikuti logika yang sama dengan dashboard user

