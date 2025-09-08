# Testing Routes & API - Assessment Center

## Available Routes

### 1. Authentication Routes
```bash
# Login
POST /login
{
    "email": "admin@assessment.com",
    "password": "password"
}

# Logout
POST /logout
```

### 2. Admin Routes
```bash
# Dashboard
GET /admin/dashboard

# Sesi Management
GET /admin/sesi
POST /admin/sesi
PUT /admin/sesi/{id}
DELETE /admin/sesi/{id}

# Peserta Management
GET /admin/peserta
POST /admin/peserta
PUT /admin/peserta/{id}
DELETE /admin/peserta/{id}

# Assessment Progress
GET /admin/progress
GET /admin/progress/{peserta_id}
```

### 3. Participant Routes
```bash
# Login dengan PIN
POST /participant/login
{
    "pin": "123456"
}

# Dashboard Peserta
GET /participant/dashboard

# Assessment
GET /participant/assessment/{jenis}
POST /participant/assessment/{jenis}/answer
PUT /participant/assessment/{jenis}/answer/{id}

# Progress
GET /participant/progress
```

## Testing Commands

### 1. Check Routes
```bash
# List semua routes
php artisan route:list

# Check route tertentu
php artisan route:list --name=admin
```

### 2. Test API dengan Tinker
```php
// Test login admin
$user = App\Models\User::where('email', 'admin@assessment.com')->first();
echo "Admin: " . $user->name . " - Role: " . $user->role;

// Test login peserta
$peserta = App\Models\Peserta::where('pin', '123456')->first();
echo "Peserta: " . $peserta->nama_lengkap . " - User ID: " . $peserta->user_id;

// Test assessment progress
$progress = App\Models\KemajuanPenilaian::with(['peserta', 'penilaian'])->get();
foreach($progress as $p) {
    echo $p->peserta->nama_lengkap . " - " . $p->penilaian->jenis . " - " . $p->status;
}
```

### 3. Test dengan Browser/Postman

#### Login Admin
```http
POST /login
Content-Type: application/json

{
    "email": "admin@assessment.com",
    "password": "password"
}
```

#### Login Peserta
```http
POST /participant/login
Content-Type: application/json

{
    "pin": "123456"
}
```

#### Get Assessment Progress
```http
GET /admin/progress
Authorization: Bearer {token}
```

## Sample Data Testing

### 1. Test Studi Kasus
```php
// Get studi kasus assessment
$studiKasus = App\Models\Penilaian::where('jenis', 'studi_kasus')->first();
$items = $studiKasus->itemPenilaian;
echo "Studi Kasus Items: " . $items->count();

// Get jawaban peserta
$jawaban = App\Models\JawabanStudiKasus::with(['peserta', 'penilaian'])->get();
foreach($jawaban as $j) {
    echo $j->peserta->nama_lengkap . " - " . $j->status . " - " . substr($j->jawaban, 0, 50) . "...";
}
```

### 2. Test In-Tray Exercise
```php
// Get in-tray assessment
$inTray = App\Models\Penilaian::where('jenis', 'in_tray')->first();
$latihan = App\Models\LatihanInTray::where('penilaian_id', $inTray->id)->get();
echo "In-Tray Exercises: " . $latihan->count();

// Get jawaban in-tray
$jawaban = App\Models\JawabanInTray::with(['peserta', 'latihanInTray'])->get();
foreach($jawaban as $j) {
    echo $j->peserta->nama_lengkap . " - Memo " . $j->urutan_prioritas . " - " . $j->disposisi;
}
```

### 3. Test Roleplay & FGD
```php
// Get roleplay catatan
$roleplay = App\Models\CatatanRoleplay::with(['peserta', 'penilaian'])->get();
echo "Roleplay Notes: " . $roleplay->count();

// Get FGD catatan
$fgd = App\Models\CatatanFgd::with(['peserta', 'penilaian'])->get();
echo "FGD Notes: " . $fgd->count();
```

## Performance Testing

### 1. Database Queries
```bash
# Enable query log
php artisan tinker --execute="DB::enableQueryLog(); App\Models\KemajuanPenilaian::with(['peserta', 'penilaian'])->get(); print_r(DB::getQueryLog());"
```

### 2. Memory Usage
```bash
# Check memory usage
php artisan tinker --execute="echo 'Memory: ' . memory_get_usage(true) / 1024 / 1024 . ' MB';"
```

## Troubleshooting

### 1. Route Not Found
```bash
# Clear route cache
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### 2. Database Connection
```bash
# Test database connection
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connected: ' . DB::connection()->getDatabaseName(); } catch (Exception \$e) { echo 'Error: ' . \$e->getMessage(); }"
```

### 3. Model Issues
```bash
# Check model relationships
php artisan tinker --execute="echo 'User count: ' . App\Models\User::count(); echo 'Peserta count: ' . App\Models\Peserta::count();"
```

## Notes
- Semua routes harus di-test dengan authentication yang tepat
- Test dengan berbagai status assessment (belum_mulai, sedang_berlangsung, selesai)
- Verify foreign key relationships dan data integrity
- Test error handling dan validation
