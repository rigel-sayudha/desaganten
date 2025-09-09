@echo off
chcp 65001 >nul
title Sistem Surat Keterangan Desa Ganten - Auto Installer

echo.
echo ████████████████████████████████████████████████████████████████
echo ██                                                            ██
echo ██    🏛️  SISTEM SURAT KETERANGAN DESA GANTEN  🏛️             ██
echo ██                                                            ██
echo ██                    Auto Installer v2.0                    ██
echo ██                                                            ██
echo ████████████████████████████████████████████████████████████████
echo.

:: Check if running as administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ❌ Error: Script harus dijalankan sebagai Administrator!
    echo    Klik kanan pada file ini dan pilih "Run as administrator"
    pause
    exit /b 1
)

echo ✅ Administrator privileges detected
echo.

:: Set project directory
set PROJECT_DIR=%cd%
echo 📁 Project Directory: %PROJECT_DIR%
echo.

:: Step 1: Check Prerequisites
echo ===============================================
echo 🔍 [1/8] Checking Prerequisites...
echo ===============================================

:: Check PHP
php --version >nul 2>&1
if %errorLevel% neq 0 (
    echo ❌ PHP not found! Please install XAMPP first.
    goto :error_exit
) else (
    echo ✅ PHP found
)

:: Check Composer
composer --version >nul 2>&1
if %errorLevel% neq 0 (
    echo ❌ Composer not found! Please install Composer first.
    goto :error_exit
) else (
    echo ✅ Composer found
)

:: Check Node.js
node --version >nul 2>&1
if %errorLevel% neq 0 (
    echo ❌ Node.js not found! Please install Node.js first.
    goto :error_exit
) else (
    echo ✅ Node.js found
)

:: Check NPM
npm --version >nul 2>&1
if %errorLevel% neq 0 (
    echo ❌ NPM not found! Please install Node.js with NPM.
    goto :error_exit
) else (
    echo ✅ NPM found
)

echo.

:: Step 2: Setup Environment
echo ===============================================
echo ⚙️ [2/8] Setting up Environment...
echo ===============================================

if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo ✅ .env file created from .env.example
    ) else (
        echo ❌ .env.example not found!
        goto :error_exit
    )
) else (
    echo ✅ .env file already exists
)

echo.

:: Step 3: Install Composer Dependencies
echo ===============================================
echo 📦 [3/8] Installing Composer Dependencies...
echo ===============================================

echo Installing PHP packages... (this may take a few minutes)
composer install --no-interaction --prefer-dist --optimize-autoloader
if %errorLevel% neq 0 (
    echo ❌ Composer install failed!
    echo Trying with --ignore-platform-reqs...
    composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs
    if %errorLevel% neq 0 (
        echo ❌ Composer install failed even with --ignore-platform-reqs
        goto :error_exit
    )
)
echo ✅ Composer dependencies installed successfully
echo.

:: Step 4: Install NPM Dependencies
echo ===============================================
echo 📦 [4/8] Installing NPM Dependencies...
echo ===============================================

echo Installing Node.js packages... (this may take a few minutes)
npm install --silent
if %errorLevel% neq 0 (
    echo ❌ NPM install failed! Trying to clear cache and retry...
    npm cache clean --force
    npm install --silent --force
    if %errorLevel% neq 0 (
        echo ❌ NPM install failed even after cache clear
        goto :error_exit
    )
)
echo ✅ NPM dependencies installed successfully
echo.

:: Step 5: Generate Application Key
echo ===============================================
echo 🔑 [5/8] Generating Application Key...
echo ===============================================

php artisan key:generate --force
if %errorLevel% neq 0 (
    echo ❌ Failed to generate application key
    goto :error_exit
)
echo ✅ Application key generated successfully
echo.

:: Step 6: Setup Database
echo ===============================================
echo 🗄️ [6/8] Setting up Database...
echo ===============================================

echo Checking database connection...
php artisan migrate:status >nul 2>&1
if %errorLevel% neq 0 (
    echo ⚠️  Database not accessible. Please ensure:
    echo   1. XAMPP/MySQL is running
    echo   2. Database 'desaganten' exists
    echo   3. .env file has correct database credentials
    echo.
    echo Would you like to continue anyway? (Y/N)
    set /p continue_anyway=
    if /i "%continue_anyway%" neq "Y" goto :error_exit
) else (
    echo ✅ Database connection successful
    
    echo Running database migrations...
    php artisan migrate --force
    if %errorLevel% neq 0 (
        echo ❌ Database migration failed
        goto :error_exit
    )
    echo ✅ Database migrations completed
    
    echo Seeding database with sample data...
    php artisan db:seed --force
    if %errorLevel% neq 0 (
        echo ⚠️  Database seeding failed, but continuing...
    ) else (
        echo ✅ Database seeded successfully
    )
)
echo.

:: Step 7: Build Frontend Assets
echo ===============================================
echo 🎨 [7/8] Building Frontend Assets...
echo ===============================================

echo Building CSS and JavaScript files...
npm run build
if %errorLevel% neq 0 (
    echo ❌ Failed to build frontend assets
    echo Trying development build...
    npm run dev
    if %errorLevel% neq 0 (
        echo ❌ Both production and development builds failed
        goto :error_exit
    )
)
echo ✅ Frontend assets built successfully
echo.

:: Step 8: Clear Caches and Optimize
echo ===============================================
echo 🧹 [8/8] Optimizing Application...
echo ===============================================

echo Clearing application caches...
php artisan config:clear >nul 2>&1
php artisan route:clear >nul 2>&1
php artisan view:clear >nul 2>&1
php artisan cache:clear >nul 2>&1

echo Optimizing application...
php artisan config:cache >nul 2>&1
php artisan route:cache >nul 2>&1
composer dump-autoload --optimize >nul 2>&1

echo ✅ Application optimized successfully
echo.

:: Success message
echo.
echo ████████████████████████████████████████████████████████████████
echo ██                                                            ██
echo ██                    🎉 INSTALASI BERHASIL! 🎉                ██
echo ██                                                            ██
echo ████████████████████████████████████████████████████████████████
echo.
echo ✅ Sistem Surat Keterangan Desa Ganten berhasil diinstal!
echo.
echo 🚀 Untuk menjalankan aplikasi:
echo    1. Pastikan XAMPP (Apache + MySQL) berjalan
echo    2. Jalankan: php artisan serve
echo    3. Buka browser: http://localhost:8000
echo.
echo 👤 Login Admin:
echo    Email: admin@desa.com
echo    Password: password
echo.
echo 📱 Untuk akses mobile/network:
echo    Jalankan: php artisan serve --host=0.0.0.0 --port=8000
echo    Akses: http://[IP_ADDRESS]:8000
echo.
echo 📊 Test Report System:
echo    http://localhost:8000/report/system/pdf
echo.

set /p start_server="Apakah ingin menjalankan server sekarang? (Y/N): "
if /i "%start_server%" equ "Y" (
    echo.
    echo 🚀 Starting Laravel development server...
    echo Press Ctrl+C to stop the server
    echo.
    php artisan serve --host=0.0.0.0 --port=8000
)

goto :end

:error_exit
echo.
echo ████████████████████████████████████████████████████████████████
echo ██                                                            ██
echo ██                    ❌ INSTALASI GAGAL! ❌                   ██
echo ██                                                            ██
echo ████████████████████████████████████████████████████████████████
echo.
echo 🔧 Troubleshooting Steps:
echo 1. Pastikan semua prerequisites terinstal:
echo    - XAMPP (PHP 8.1+, MySQL, Apache)
echo    - Composer
echo    - Node.js & NPM
echo.
echo 2. Pastikan berjalan sebagai Administrator
echo.
echo 3. Check network connection untuk download packages
echo.
echo 4. Baca TUTORIAL_INSTALASI_GOOGLE_DRIVE.md untuk detail lengkap
echo.
echo 5. Jika masih error, hubungi support atau buat GitHub issue
echo.

:end
echo.
echo Tekan tombol apa saja untuk keluar...
pause >nul
