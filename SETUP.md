# Setup Aplikasi Assessment Center

## Prerequisites

Sebelum memulai setup, pastikan sistem Anda memiliki:

- **PHP 8.2+** dengan ekstensi yang diperlukan
- **Composer** untuk dependency management
- **Node.js & NPM** untuk frontend assets
- **MySQL 8.0+** atau MariaDB 10.5+
- **Web Server** (Apache/Nginx) atau gunakan `php artisan serve`

## Langkah Setup Lengkap

### 1. Clone & Setup Project

```bash
# Clone repository (jika menggunakan git)
git clone <repository-url>
cd assesmentdadakan

# Atau extract file zip ke direktori yang diinginkan
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment

Buat file `.env` di root project dengan konfigurasi berikut:

```env
APP_NAME="Assessment Center"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assessment_center
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 4. Setup Database

```bash
# Buat database MySQL
mysql -u root -p
CREATE DATABASE assessment_center;
USE assessment_center;
EXIT;

# Atau gunakan phpMyAdmin untuk membuat database
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migration

```bash
# Jalankan semua migration
php artisan migrate

# Jika ada error, coba fresh migration
php artisan migrate:fresh
```

### 7. Jalankan Seeder

```bash
# Jalankan semua seeder
php artisan db:seed

# Atau jalankan seeder tertentu
php artisan db:seed --class=AssessmentSessionSeeder
php artisan db:seed --class=ParticipantSeeder
php artisan db:seed --class=AssessmentSeeder
```

### 8. Build Frontend Assets

```bash
# Build untuk production
npm run build

# Atau jalankan dalam mode development
npm run dev
```

### 9. Jalankan Aplikasi

```bash
# Jalankan Laravel server
php artisan serve

# Buka browser dan akses
# http://localhost:8000
```

## Testing Aplikasi

### 1. Login sebagai Peserta

Gunakan salah satu PIN berikut untuk login:

- **Ahmad Rizki** - PIN: `123456`
- **Siti Nurhaliza** - PIN: `234567`
- **Budi Santoso** - PIN: `345678`
- **Dewi Sartika** - PIN: `456789`

### 2. Fitur yang Bisa Ditest

- **Login** - `/participant/login`
- **Dashboard** - `/participant/dashboard`
- **Biodata** - `/participant/biodata`
- **Assessment** - `/participant/assessment/{id}`

## Troubleshooting

### Error Database Connection

```bash
# Cek koneksi database
php artisan tinker
DB::connection()->getPdo();

# Pastikan service MySQL berjalan
# Windows: services.msc
# Linux: sudo systemctl status mysql
# macOS: brew services list
```

### Error Migration

```bash
# Reset database dan jalankan ulang
php artisan migrate:fresh --seed

# Cek status migration
php artisan migrate:status
```

### Error Permission

```bash
# Set permission untuk storage dan bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Windows: Run as Administrator
```

### Error Composer

```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Reinstall dependencies
rm -rf vendor composer.lock
composer install
```

### Error NPM

```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules dan reinstall
rm -rf node_modules package-lock.json
npm install
```

## Konfigurasi Web Server

### Apache (.htaccess sudah tersedia)

Pastikan modul `mod_rewrite` aktif:

```bash
# Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# CentOS/RHEL
sudo yum install mod_rewrite
sudo systemctl restart httpd
```

### Nginx

Buat konfigurasi virtual host:

```nginx
server {
    listen 80;
    server_name assessment-center.local;
    root /path/to/assesmentdadakan/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Development

### Struktur Project

```
assesmentdadakan/
├── app/
│   ├── Http/Controllers/
│   │   ├── ParticipantController.php
│   │   └── AssessmentController.php
│   └── Models/
│       ├── Participant.php
│       ├── Assessment.php
│       ├── AssessmentSession.php
│       └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       └── participant/
│           ├── login.blade.php
│           ├── dashboard.blade.php
│           ├── assessment.blade.php
│           └── biodata.blade.php
└── routes/
    └── web.php
```

### Command yang Berguna

```bash
# Buat migration baru
php artisan make:migration create_table_name

# Buat model baru
php artisan make:model ModelName

# Buat controller baru
php artisan make:controller ControllerName

# Buat seeder baru
php artisan make:seeder SeederName

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# List semua routes
php artisan route:list

# Tinker untuk testing
php artisan tinker
```

## Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate production app key
- [ ] Set proper database credentials
- [ ] Configure web server
- [ ] Set proper file permissions
- [ ] Enable HTTPS
- [ ] Configure backup strategy

### Performance Optimization

```bash
# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

## Support

Jika mengalami masalah, cek:

1. **Laravel Logs** - `storage/logs/laravel.log`
2. **PHP Error Log** - `/var/log/php_errors.log`
3. **Web Server Logs** - Apache/Nginx error logs
4. **Database Logs** - MySQL error log

## Update

```bash
# Update dependencies
composer update
npm update

# Jalankan migration baru
php artisan migrate

# Clear cache setelah update
php artisan cache:clear
php artisan config:clear
```
