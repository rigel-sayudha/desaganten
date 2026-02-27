# 📚 TUTORIAL INSTALASI SISTEM SURAT KETERANGAN DESA GANTEN
## 🌐 Instalasi via Google Drive - Step by Step Guide

---

## 📋 DAFTAR ISI
1. [Persiapan & Requirements](#1-persiapan--requirements)
2. [Download Project dari Google Drive](#2-download-project-dari-google-drive)
3. [Instalasi Software Pendukung](#3-instalasi-software-pendukung)
4. [Setup Project Laravel](#4-setup-project-laravel)
5. [Konfigurasi Database](#5-konfigurasi-database)
6. [Testing & Verifikasi](#6-testing--verifikasi)
7. [Troubleshooting Common Issues](#7-troubleshooting-common-issues)
8. [Instalasi di Komputer Lain](#8-instalasi-di-komputer-lain)

---

## 1. 🛠️ PERSIAPAN & REQUIREMENTS

### 📋 Software yang Dibutuhkan:
- **PHP 8.1+** (Recommended: PHP 8.1.10 atau lebih baru)
- **Composer** (PHP Package Manager)
- **XAMPP** atau **Laragon** (untuk Apache + MySQL)
- **Node.js & NPM** (untuk frontend assets)
- **Git** (optional, untuk version control)
- **Text Editor** (VS Code, Sublime, atau lainnya)

### 🖥️ System Requirements:
- **OS:** Windows 10/11, macOS, atau Linux
- **RAM:** Minimum 4GB, Recommended 8GB+
- **Storage:** Minimum 2GB free space
- **Internet:** Untuk download dependencies

---

## 2. 📥 DOWNLOAD PROJECT DARI GOOGLE DRIVE

### Step 1: Akses Google Drive Link
```
🔗 Link Google Drive: [AKAN DISEDIAKAN SETELAH UPLOAD]
```

### Step 2: Download File Project
1. **Klik link Google Drive** yang disediakan
2. **Download file** `desaganten-project.zip` (sekitar 500MB-1GB)
3. **Simpan** di folder yang mudah diakses (contoh: `C:\xampp\htdocs\`)
4. **Extract** file zip ke folder `desaganten`

### Step 3: Struktur Folder Setelah Extract
```
C:\xampp\htdocs\desaganten\
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 3. 🔧 INSTALASI SOFTWARE PENDUKUNG

### A. XAMPP Installation (Windows)

#### Step 1: Download XAMPP
1. **Buka** https://www.apachefriends.org/
2. **Download** XAMPP untuk Windows (PHP 8.1+)
3. **Jalankan installer** `xampp-windows-x64-8.1.x-installer.exe`

#### Step 2: Install XAMPP
```bash
# Pilih komponen yang akan diinstal:
☑️ Apache
☑️ MySQL
☑️ PHP
☑️ phpMyAdmin
☐ FileZilla (optional)
☐ Mercury (optional)
☐ Tomcat (optional)

# Install ke lokasi default: C:\xampp
```

#### Step 3: Start Services
1. **Buka** XAMPP Control Panel
2. **Start** Apache service
3. **Start** MySQL service
4. **Pastikan** tidak ada error (port 80 dan 3306 harus available)

### B. Composer Installation

#### Step 1: Download Composer
1. **Buka** https://getcomposer.org/download/
2. **Download** `Composer-Setup.exe` for Windows
3. **Jalankan installer**

#### Step 2: Verify Installation
```cmd
# Buka Command Prompt/PowerShell
composer --version

# Output expected:
# Composer version 2.x.x
```

### C. Node.js & NPM Installation

#### Step 1: Download Node.js
1. **Buka** https://nodejs.org/
2. **Download** LTS version (Recommended)
3. **Install** dengan default settings

#### Step 2: Verify Installation
```cmd
# Check Node.js version
node --version
# Output: v18.x.x atau v20.x.x

# Check NPM version
npm --version
# Output: 9.x.x atau 10.x.x
```

---

## 4. ⚙️ SETUP PROJECT LARAVEL

### Step 1: Navigate to Project Directory
```cmd
# Buka Command Prompt/PowerShell
cd C:\xampp\htdocs\desaganten
```

### Step 2: Install PHP Dependencies
```cmd
# Install semua package yang dibutuhkan Laravel
composer install

# Jika ada error, coba:
composer install --ignore-platform-reqs
```

#### 🔧 **Jika composer install gagal:**
```cmd
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Retry install
composer install --no-scripts --no-autoloader
composer dump-autoload
```

### Step 3: Install Node.js Dependencies
```cmd
# Install frontend dependencies
npm install

# Jika ada error dengan npm:
npm cache clean --force
npm install --force
```

### Step 4: Environment Configuration
```cmd
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 5: Build Frontend Assets
```cmd
# Build CSS & JS assets
npm run build

# Atau untuk development:
npm run dev
```

---

## 5. 🗄️ KONFIGURASI DATABASE

### Step 1: Buat Database MySQL
1. **Buka browser** dan akses `http://localhost/phpmyadmin`
2. **Login** dengan:
   - Username: `root`
   - Password: (kosong)
3. **Klik** "New" untuk buat database baru
4. **Nama database:** `desaganten`
5. **Collation:** `utf8mb4_unicode_ci`
6. **Klik** "Create"

### Step 2: Konfigurasi .env File
```cmd
# Edit file .env dengan text editor
notepad .env
```

#### Konfigurasi Database (.env):
```env
APP_NAME="Sistem Surat Keterangan Desa Ganten"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desaganten
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

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### Step 3: Run Database Migration
```cmd
# Jalankan migrasi database
php artisan migrate

# Jika berhasil, output akan seperti:
# Migrating: 2014_10_12_000000_create_users_table
# Migrated:  2014_10_12_000000_create_users_table
# ... (dan seterusnya)
```

### Step 4: Seed Database dengan Data Sample
```cmd
# Isi database dengan data contoh
php artisan db:seed

# Atau seed specific seeder:
php artisan db:seed --class=DatabaseSeeder
```

---

## 6. 🚀 TESTING & VERIFIKASI

### Step 1: Start Laravel Development Server
```cmd
# Start server di port 8000
php artisan serve

# Output expected:
# Laravel development server started: http://127.0.0.1:8000
```

### Step 2: Test Akses Website
1. **Buka browser** (Chrome, Firefox, Edge)
2. **Akses** `http://localhost:8000`
3. **Pastikan** halaman utama muncul tanpa error

### Step 3: Test Admin Panel
1. **Akses** `http://localhost:8000/admin`
2. **Login** dengan:
   - Email: `admin@desa.com`
   - Password: `password`
3. **Pastikan** dashboard admin dapat diakses

### Step 4: Test User Registration
1. **Akses** `http://localhost:8000/register`
2. **Daftar** user baru
3. **Login** dan test fitur user dashboard

### Step 5: Test PDF Generation
1. **Login** sebagai admin
2. **Akses** `http://localhost:8000/report/system/pdf`
3. **Pastikan** PDF report dapat didownload

### Step 6: Test Mobile Responsiveness
```cmd
# Start server dengan network access
php artisan serve --host=0.0.0.0 --port=8000
```
1. **Buka** `http://[IP_ADDRESS]:8000` di smartphone
2. **Test** responsiveness di berbagai device

---

## 7. 🔧 TROUBLESHOOTING COMMON ISSUES

### Issue 1: "Class not found" Error
```cmd
# Solution:
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Issue 2: Permission Denied (Storage/Cache)
```cmd
# Windows (as Administrator):
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T

# Or via File Explorer:
# Right-click folder → Properties → Security → Edit → Add "Everyone" with "Full Control"
```

### Issue 3: Database Connection Error
```cmd
# Check MySQL is running:
# XAMPP Control Panel → MySQL → Start

# Verify database exists:
# phpMyAdmin → Check if 'desaganten' database exists

# Check .env configuration:
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desaganten
DB_USERNAME=root
DB_PASSWORD=
```

### Issue 4: NPM/Node.js Issues
```cmd
# Clear npm cache:
npm cache clean --force

# Delete node_modules and reinstall:
rmdir /s node_modules
del package-lock.json
npm install
```

### Issue 5: Port Already in Use
```cmd
# Check what's using port 8000:
netstat -ano | findstr :8000

# Use different port:
php artisan serve --port=8001
```

### Issue 6: PDF Generation Error
```cmd
# Clear views cache:
php artisan view:clear

# Ensure dompdf is installed:
composer require barryvdh/laravel-dompdf

# Publish config:
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 8. 💻 INSTALASI DI KOMPUTER LAIN

### Metode 1: Google Drive Share

#### Step 1: Siapkan Paket Instalasi
```
1. Copy entire folder: C:\xampp\htdocs\desaganten\
2. Zip ke file: desaganten-complete.zip
3. Upload ke Google Drive
4. Set sharing: "Anyone with link can view"
5. Share link ke komputer lain
```

#### Step 2: Di Komputer Tujuan
```cmd
# 1. Install XAMPP, Composer, Node.js (ikuti steps 3A-3C)
# 2. Download & extract project dari Google Drive
# 3. Navigate to project folder
cd C:\xampp\htdocs\desaganten

# 4. Install dependencies
composer install
npm install

# 5. Setup environment
copy .env.example .env
php artisan key:generate

# 6. Setup database (ikuti step 5)
# 7. Run migrations
php artisan migrate
php artisan db:seed

# 8. Build assets
npm run build

# 9. Test server
php artisan serve
```

### Metode 2: GitHub Clone (Alternative)
```cmd
# Clone dari GitHub repository
git clone https://github.com/rigel-sayudha/desaganten.git
cd desaganten

# Ikuti steps 4-6 seperti di atas
```

### Metode 3: Portable Package (All-in-One)

#### Buat Portable Package:
```
📁 desaganten-portable/
├── 📁 project/          # Laravel project files
├── 📁 xampp/           # Portable XAMPP
├── 📁 composer/        # Portable Composer
├── 📁 nodejs/          # Portable Node.js
├── 📄 install.bat      # Auto-installer script
└── 📄 README.txt       # Instructions
```

#### Auto-Installer Script (install.bat):
```batch
@echo off
echo ========================================
echo   SISTEM SURAT KETERANGAN DESA GANTEN
echo   Auto Installer v1.0
echo ========================================

echo [1/6] Starting XAMPP Services...
start xampp\xampp-control.exe

echo [2/6] Setting up environment...
cd project
copy .env.example .env

echo [3/6] Installing Composer dependencies...
..\composer\composer.phar install

echo [4/6] Installing Node.js dependencies...
..\nodejs\npm install

echo [5/6] Building assets...
..\nodejs\npm run build

echo [6/6] Starting Laravel server...
php artisan serve --host=0.0.0.0 --port=8000

pause
```

---

## 9. 📋 CHECKLIST INSTALASI

### ✅ Pre-Installation Checklist
- [ ] XAMPP installed & running (Apache + MySQL)
- [ ] Composer installed & working
- [ ] Node.js & NPM installed
- [ ] Project downloaded & extracted
- [ ] Database 'desaganten' created

### ✅ Installation Checklist
- [ ] `composer install` completed successfully
- [ ] `npm install` completed successfully
- [ ] `.env` file configured properly
- [ ] `php artisan key:generate` executed
- [ ] `php artisan migrate` completed
- [ ] `php artisan db:seed` completed
- [ ] `npm run build` completed

### ✅ Testing Checklist
- [ ] `php artisan serve` starts without error
- [ ] Homepage loads at `http://localhost:8000`
- [ ] Admin login works (`admin@desa.com` / `password`)
- [ ] User registration works
- [ ] PDF generation works
- [ ] Mobile responsiveness tested

### ✅ Production Checklist
- [ ] `.env` APP_DEBUG set to `false`
- [ ] Database credentials updated for production
- [ ] SSL certificate configured (if needed)
- [ ] Backup strategy implemented
- [ ] Error logging configured

---

## 10. 📞 SUPPORT & MAINTENANCE

### 🆘 Jika Mengalami Masalah:

#### Level 1: Self-Help
1. **Check troubleshooting section** di atas
2. **Restart services:** Apache, MySQL, Laravel server
3. **Clear all caches:** Config, route, view, composer
4. **Check log files:** `storage/logs/laravel.log`

#### Level 2: Community Support
1. **Search GitHub Issues:** Check if issue already reported
2. **Laravel Documentation:** https://laravel.com/docs
3. **Stack Overflow:** Search Laravel-related issues

#### Level 3: Direct Support
1. **Create GitHub Issue:** Detail error with steps to reproduce
2. **Contact Developer:** rigel.sayudha@example.com
3. **Include information:**
   - Operating System & version
   - PHP version (`php --version`)
   - Error messages/logs
   - Steps taken before error

### 🔄 Regular Maintenance

#### Weekly Tasks:
- [ ] Check log files for errors
- [ ] Backup database
- [ ] Update composer dependencies (`composer update`)

#### Monthly Tasks:
- [ ] Update Laravel framework (if needed)
- [ ] Update Node.js dependencies (`npm update`)
- [ ] Security scan & updates

---

## 📚 RESOURCE TAMBAHAN

### 🔗 Useful Links
- **Laravel Documentation:** https://laravel.com/docs/10.x
- **Composer Documentation:** https://getcomposer.org/doc/
- **Node.js Documentation:** https://nodejs.org/docs/
- **XAMPP Documentation:** https://www.apachefriends.org/docs/

### 📖 Learning Resources
- **Laravel Bootcamp:** https://bootcamp.laravel.com/
- **Laracasts:** https://laracasts.com/
- **PHP Official Documentation:** https://www.php.net/docs.php

### 🛠️ Development Tools
- **Laravel Debugbar:** For debugging
- **Laravel Telescope:** For application insights
- **PHPMyAdmin:** Database management
- **Postman:** API testing (if needed)

---

## 🎉 SELAMAT!

Jika semua steps di atas sudah diikuti dengan benar, maka:

✅ **Sistem Surat Keterangan Desa Ganten** sudah berjalan sempurna!  
✅ **Semua fitur** dapat diakses dan berfungsi normal  
✅ **Admin panel** & **user dashboard** ready to use  
✅ **PDF generation** working perfectly  
✅ **Mobile responsive** design implemented  

**🚀 SISTEM SIAP DIGUNAKAN UNTUK PRODUKSI!**

---

*Tutorial ini dibuat dengan ❤️ untuk memudahkan instalasi dan deployment Sistem Surat Keterangan Desa Ganten.*

**Last Updated:** September 2, 2025  
**Version:** 2.0  
**Author:** Rigel Sayudha  
**Support:** rigel.sayudha@example.com
