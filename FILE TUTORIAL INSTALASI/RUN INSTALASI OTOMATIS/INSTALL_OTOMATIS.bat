@echo off
chcp 65001 >nul
cls
echo ===============================================================================
echo                    INSTALLER OTOMATIS SISTEM SURAT KETERANGAN
echo                               DESA GANTEN v2.0
echo ===============================================================================
echo.
echo Script ini akan menginstall project secara otomatis...
echo Pastikan Anda sudah menginstall XAMPP, Composer, dan Node.js terlebih dahulu!
echo.
pause

:: Cek apakah running sebagai Administrator
net session >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Running sebagai Administrator
) else (
    echo [✗] ERROR: Script harus dijalankan sebagai Administrator!
    echo Klik kanan pada file ini dan pilih "Run as administrator"
    pause
    exit
)

echo.
echo ===============================================================================
echo                            STEP 1: CEK PREREQUISITES
echo ===============================================================================

:: Cek XAMPP
if exist "C:\xampp\php\php.exe" (
    echo [✓] XAMPP terdeteksi di C:\xampp\
) else (
    echo [✗] XAMPP tidak ditemukan! Silakan install XAMPP terlebih dahulu.
    echo Download dari: https://www.apachefriends.org/
    pause
    exit
)

:: Cek Composer
composer --version >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Composer terdeteksi
) else (
    echo [✗] Composer tidak ditemukan! Silakan install Composer terlebih dahulu.
    echo Download dari: https://getcomposer.org/
    pause
    exit
)

:: Cek Node.js
node --version >nul 2>&1
if %errorLevel% == 0 (
    echo [✓] Node.js terdeteksi
) else (
    echo [✗] Node.js tidak ditemukan! Silakan install Node.js terlebih dahulu.
    echo Download dari: https://nodejs.org/
    pause
    exit
)

echo.
echo ===============================================================================
echo                            STEP 2: SETUP FOLDER PROJECT
echo ===============================================================================

:: Masuk ke folder project
cd /d "%~dp0"
echo [INFO] Lokasi project: %cd%

if not exist "composer.json" (
    echo [✗] File composer.json tidak ditemukan!
    echo Pastikan script ini berada di folder project Laravel.
    pause
    exit
)

echo [✓] Project Laravel terdeteksi

echo.
echo ===============================================================================
echo                        STEP 3: INSTALL DEPENDENCIES PHP
echo ===============================================================================

echo [INFO] Menginstall dependencies PHP dengan Composer...
echo Ini membutuhkan waktu beberapa menit, harap sabar...

composer install --optimize-autoloader --no-dev
if %errorLevel% == 0 (
    echo [✓] Dependencies PHP berhasil diinstall
) else (
    echo [!] Error saat install dependencies PHP, mencoba dengan --ignore-platform-reqs...
    composer install --ignore-platform-reqs --optimize-autoloader --no-dev
    if %errorLevel% == 0 (
        echo [✓] Dependencies PHP berhasil diinstall (dengan ignore platform)
    ) else (
        echo [✗] Gagal install dependencies PHP
        pause
        exit
    )
)

echo.
echo ===============================================================================
echo                        STEP 4: INSTALL DEPENDENCIES NODE.JS
echo ===============================================================================

echo [INFO] Menginstall dependencies Node.js dengan NPM...

npm install --production
if %errorLevel% == 0 (
    echo [✓] Dependencies Node.js berhasil diinstall
) else (
    echo [!] Error saat install dependencies Node.js, mencoba npm cache clean...
    npm cache clean --force
    npm install --production --force
    if %errorLevel% == 0 (
        echo [✓] Dependencies Node.js berhasil diinstall (dengan force)
    ) else (
        echo [✗] Gagal install dependencies Node.js
        pause
        exit
    )
)

echo.
echo ===============================================================================
echo                        STEP 5: SETUP ENVIRONMENT
echo ===============================================================================

:: Copy .env file jika belum ada
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [✓] File .env berhasil dibuat dari .env.example
    ) else (
        echo [✗] File .env.example tidak ditemukan!
        pause
        exit
    )
) else (
    echo [INFO] File .env sudah ada, tidak akan ditimpa
)

:: Generate application key
echo [INFO] Generate application key...
php artisan key:generate --force
if %errorLevel% == 0 (
    echo [✓] Application key berhasil di-generate
) else (
    echo [✗] Gagal generate application key
    pause
    exit
)

echo.
echo ===============================================================================
echo                        STEP 6: KONFIGURASI DATABASE
echo ===============================================================================

echo [INFO] Silakan buat database 'desaganten' di phpMyAdmin terlebih dahulu!
echo.
echo Langkah-langkah:
echo 1. Buka http://localhost/phpmyadmin
echo 2. Login dengan username: root, password: (kosong)
echo 3. Klik tab "Databases"
echo 4. Ketik nama database: desaganten
echo 5. Pilih Collation: utf8mb4_unicode_ci
echo 6. Klik "Create"
echo.
echo Tekan ENTER setelah database berhasil dibuat...
pause

:: Jalankan migrasi
echo [INFO] Menjalankan migrasi database...
php artisan migrate --force
if %errorLevel% == 0 (
    echo [✓] Migrasi database berhasil
) else (
    echo [✗] Gagal menjalankan migrasi database
    echo Pastikan:
    echo - MySQL di XAMPP sudah berjalan
    echo - Database 'desaganten' sudah dibuat
    echo - Konfigurasi .env sudah benar
    pause
    exit
)

:: Isi data sample
echo [INFO] Mengisi database dengan data contoh...
php artisan db:seed --force
if %errorLevel% == 0 (
    echo [✓] Data contoh berhasil diisi
) else (
    echo [!] Gagal mengisi data contoh (tidak fatal)
)

echo.
echo ===============================================================================
echo                        STEP 7: BUILD FRONTEND ASSETS
echo ===============================================================================

echo [INFO] Build frontend assets...
npm run build
if %errorLevel% == 0 (
    echo [✓] Frontend assets berhasil di-build
) else (
    echo [!] Error saat build frontend, mencoba npm run production...
    npm run production
    if %errorLevel% == 0 (
        echo [✓] Frontend assets berhasil di-build (production)
    ) else (
        echo [✗] Gagal build frontend assets
        pause
        exit
    )
)

echo.
echo ===============================================================================
echo                        STEP 8: OPTIMISASI APLIKASI
echo ===============================================================================

echo [INFO] Optimisasi aplikasi Laravel...

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo [✓] Optimisasi selesai

echo.
echo ===============================================================================
echo                            INSTALASI SELESAI!
echo ===============================================================================
echo.
echo [✓] Project berhasil diinstall!
echo.
echo CARA MENJALANKAN APLIKASI:
echo 1. Pastikan XAMPP (Apache + MySQL) sudah berjalan
echo 2. Buka Command Prompt di folder project ini
echo 3. Ketik: php artisan serve
echo 4. Buka browser: http://localhost:8000
echo.
echo AKUN DEFAULT:
echo - Admin: admin@desa.com / password
echo - User: Daftar sendiri melalui /register
echo.
echo URL PENTING:
echo - Homepage: http://localhost:8000
echo - Admin Panel: http://localhost:8000/admin
echo - Registrasi: http://localhost:8000/register
echo.
echo Apakah ingin menjalankan server sekarang? (Y/N)
set /p choice=Pilihan: 

if /i "%choice%"=="Y" (
    echo.
    echo [INFO] Menjalankan Laravel development server...
    echo Server akan berjalan di: http://127.0.0.1:8000
    echo Tekan Ctrl+C untuk menghentikan server
    echo.
    php artisan serve
) else (
    echo.
    echo Instalasi selesai! Jalankan 'php artisan serve' kapan saja untuk memulai server.
)

echo.
echo Terima kasih telah menggunakan Installer Otomatis!
echo Dibuat oleh: Rigel Sayudha
echo.
pause
