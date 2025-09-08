@echo off
echo ========================================
echo    ASSESSMENT CENTER SIMULATION
echo ========================================
echo.

echo [1/3] Resetting database...
php artisan migrate:fresh

echo.
echo [2/3] Running seeders...
php artisan db:seed

echo.
echo [3/3] Verification...
php artisan tinker --execute="echo 'Users: ' . App\Models\User::count(); echo 'Peserta: ' . App\Models\Peserta::count(); echo 'Sesi: ' . App\Models\SesiPenilaian::count(); echo 'Penilaian: ' . App\Models\Penilaian::count(); echo 'Item: ' . App\Models\ItemPenilaian::count(); echo 'Kemajuan: ' . App\Models\KemajuanPenilaian::count();"

echo.
echo ========================================
echo    SIMULATION READY!
echo ========================================
echo.
echo Admin Login:
echo   Email: admin@assessment.com
echo   Password: password
echo.
echo Peserta Login:
echo   Email: [nama]@example.com
echo   Password: password
echo   PIN: [6 digit sesuai data]
echo.
echo See SIMULASI_README.md for details
echo.
pause
