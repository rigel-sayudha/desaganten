# 🏛️ TUTORIAL INSTALASI SISTEM DESA GANTEN v3.1

**Sistem Surat Keterangan Digital dengan Fitur Statistik 3D Interactive & Rekap Surat Keluar**

---

## 📋 Daftar Isi

1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Persiapan Awal](#persiapan-awal)
3. [Instalasi Software](#instalasi-software)
4. [Setup Project](#setup-project)
5. [Testing Fitur](#testing-fitur)
6. [Fitur Baru v3.1](#fitur-baru-v31)
7. [Troubleshooting](#troubleshooting)

---

## 🖥️ Persyaratan Sistem

### Minimum Requirements
- **OS**: Windows 10/11, macOS 10.15+, Ubuntu 20.04+
- **RAM**: 4GB (Disarankan 8GB untuk Chart 3D)
- **Storage**: 5GB kosong
- **Processor**: Intel i3/AMD Ryzen 3 atau setara
- **Internet**: Diperlukan untuk CDN Chart.js

### Software Yang Dibutuhkan
- ✅ **XAMPP** - Apache + MySQL + PHP
- ✅ **Composer** - PHP Package Manager
- ✅ **Node.js & NPM** - JavaScript Runtime
- ✅ **Browser Modern** - Chrome/Firefox (untuk fitur 3D)

---

## 🚀 Persiapan Awal

### 1. Download Software

#### A. XAMPP
```
🌐 Website: https://www.apachefriends.org/
📦 File: xampp-windows-x64-8.1.x-installer.exe
💾 Size: ~150MB
✅ Pilih versi PHP 8.1+
```

#### B. Composer
```
🌐 Website: https://getcomposer.org/download/
📦 File: Composer-Setup.exe
💾 Size: ~5MB
```

#### C. Node.js
```
🌐 Website: https://nodejs.org/
📦 File: node-v20.x.x-x64.msi
💾 Size: ~30MB
✅ Pilih versi LTS
```

### 2. Buat Struktur Folder
```
📁 C:\xampp\htdocs\desaganten\
```

---

## ⚙️ Instalasi Software

### 1. Install XAMPP

```bash
# 1. Jalankan installer sebagai Administrator
# 2. Pilih komponen:
[✓] Apache
[✓] MySQL  
[✓] PHP
[✓] phpMyAdmin
[ ] FileZilla (skip)
[ ] Mercury (skip)

# 3. Install di: C:\xampp
# 4. Start Apache & MySQL di Control Panel
```

### 2. Install Composer

```bash
# 1. Jalankan Composer-Setup.exe
# 2. Pilih PHP executable: C:\xampp\php\php.exe
# 3. Install dengan setting default
# 4. Test di Command Prompt:
composer --version
```

### 3. Install Node.js

```bash
# 1. Jalankan installer node-v20.x.x-x64.msi
# 2. Install dengan setting default
# 3. Test di Command Prompt:
node --version
npm --version
```

---

## 📁 Setup Project

### 1. Download Project
```bash
# Jika dari Git:
git clone https://github.com/rigel-sayudha/desaganten.git
cd desaganten

# Jika dari zip:
# Extract ke C:\xampp\htdocs\desaganten\
```

### 2. Install Dependencies

```bash
# Masuk ke folder project
cd C:\xampp\htdocs\desaganten

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desaganten
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database

1. Buka **phpMyAdmin**: `http://localhost/phpmyadmin`
2. Klik **"New"**
3. Database name: `desaganten`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

### 6. Migration & Seeding

```bash
# Run migrations
php artisan migrate

# Seed basic data
php artisan db:seed

# Seed statistik data (NEW!)
php artisan db:seed --class=StatistikSeeder

# Seed dummy surat data (NEW v3.1!)
php artisan db:seed --class=DummySuratSeeder

# Sync rekap surat keluar (NEW v3.1!)
php artisan sync:rekap-surat
```

### 7. Build Frontend Assets

```bash
# Build for production
npm run build

# Or for development with watch
npm run dev
```

### 8. Start Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🧪 Testing Fitur

### 1. Test Basic Features

#### A. Akses Homepage
```
🌐 URL: http://localhost:8000
✅ Cek tampilan homepage
✅ Test responsive mobile
```

#### B. Admin Login
```
🌐 URL: http://localhost:8000/admin
👤 Email: admin@desa.com
🔑 Password: password
✅ Masuk ke dashboard admin
```

#### C. User Registration
```
🌐 URL: http://localhost:8000/register
✅ Daftar user baru
✅ Login dengan akun baru
✅ Test user dashboard
```

### 2. Test Fitur Statistik 3D (NEW!)

#### A. Admin CRUD Statistik
```bash
# Test 3 modul statistik:
📊 Pekerjaan: http://localhost:8000/admin/statistik/pekerjaan
👥 Umur: http://localhost:8000/admin/statistik/umur
🎓 Pendidikan: http://localhost:8000/admin/statistik/pendidikan

# Test CRUD operations:
✅ Create - Tambah data baru
✅ Read - Lihat daftar data
✅ Update - Edit data existing
✅ Delete - Hapus data
```

#### B. Public 3D Charts
```bash
# Test halaman publik:
📊 Statistik Pekerjaan: http://localhost:8000/statistik
👥 Statistik Umur: http://localhost:8000/statistik/umur
🎓 Statistik Pendidikan: http://localhost:8000/statistik/pendidikan

# Test 3D features:
✅ Klik "3D Bar Graph" - Chart batang 3D
✅ Klik "3D Pie Graph" - Chart pie 3D
✅ Hover effects - Animasi saat hover
✅ Mobile responsive - Test di device mobile
```

### 3. Test Performance 3D

```bash
# Browser Developer Tools (F12):
✅ Network tab - CDN Chart.js load < 2 detik
✅ Performance tab - Chart render < 1 detik
✅ Console - No JavaScript errors
✅ Mobile device mode - Responsive OK
```

---

## 🔧 Troubleshooting

### 1. Chart 3D Tidak Muncul

**Problem**: 3D charts tidak tampil atau error

**Solutions**:
```bash
# ✅ Check internet connection (CDN required)
# ✅ Clear browser cache (Ctrl+Shift+Delete)
# ✅ Test di browser lain (Chrome recommended)
# ✅ Check Developer Console (F12) untuk error
# ✅ Pastikan JavaScript enabled
```

### 2. XAMPP Port Conflict

**Problem**: Port 80 atau 3306 sudah digunakan

**Solutions**:
```bash
# Port 80 (Apache):
1. Stop service yang menggunakan port 80
2. Atau ubah port Apache di httpd.conf

# Port 3306 (MySQL):
1. Stop MySQL services lain
2. Restart komputer
3. Start ulang XAMPP
```

### 3. Composer Install Failed

**Problem**: `composer install` gagal

**Solutions**:
```bash
composer clear-cache
composer install --ignore-platform-reqs
composer install --no-scripts
```

### 4. NPM Install Failed

**Problem**: `npm install` error

**Solutions**:
```bash
npm cache clean --force
rm -rf node_modules (delete folder)
npm install --force
```

### 5. Database Connection Error

**Problem**: Cannot connect to database

**Solutions**:
```bash
# ✅ Check MySQL running di XAMPP
# ✅ Check .env database settings
# ✅ Create database 'desaganten' di phpMyAdmin
# ✅ Test connection: php artisan tinker
```

### 6. 3D Charts Slow Performance

**Problem**: Charts loading lambat

**Solutions**:
```bash
# Optimize Laravel:
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Use local Chart.js instead of CDN:
# Download Chart.js to public/js/
# Update script src in blade files
```

---

## 🆕 Fitur Baru v3.1

### 📊 Sistem Rekap Surat Keluar
**FITUR TERBARU**: Sistem manajemen rekap surat keluar yang terintegrasi

#### A. Admin Panel Rekap Surat
```bash
# URL Admin:
📋 Index Rekap: http://localhost:8000/admin/laporan/rekap-surat-keluar
➕ Tambah Rekap: http://localhost:8000/admin/laporan/rekap-surat-keluar/create
🔄 Sync Data: POST /admin/laporan/rekap-surat-keluar/sync

# Fitur Admin:
✅ CRUD lengkap (Create, Read, Update, Delete)
✅ Filter berdasarkan status (pending, diproses, selesai, ditolak)
✅ Pagination otomatis
✅ Statistik dashboard
✅ Sync otomatis dari tabel surat
```

#### B. Public View Rekap Surat
```bash
# URL Public:
📖 Lihat Rekap: http://localhost:8000/rekap-surat-keluar

# Fitur Public:
✅ View-only untuk transparansi
✅ Filter berdasarkan status
✅ Search berdasarkan nama/jenis surat
✅ Responsive design
```

### 🔄 Sistem Sinkronisasi Data
**OTOMATIS**: Data dari berbagai tabel surat disinkronkan ke rekap

#### A. Artisan Command
```bash
# Sync manual:
php artisan sync:rekap-surat

# Check tabel status:
php artisan check:tables
```

#### B. Tabel Yang Disinkronkan
```bash
✅ domisili - Surat Keterangan Domisili
✅ tidak_mampu - Surat Keterangan Tidak Mampu
✅ surat_usaha - Surat Keterangan Usaha
✅ surat - Surat Umum/Pengantar
✅ surat_ktp - Surat Pengantar KTP
✅ surat_kematian - Surat Keterangan Kematian
✅ surat_kelahiran - Surat Keterangan Kelahiran
✅ surat_skck - Surat Pengantar SKCK
✅ surat_kk - Surat Pengantar KK
✅ surat_kehilangan - Surat Keterangan Kehilangan
✅ belum_menikah - Surat Keterangan Belum Menikah
```

### 📊 Dashboard Statistik Rekap
**ANALYTICS**: Statistik lengkap untuk data rekap surat

#### A. Statistik Status
```bash
📊 Total Pending: Jumlah surat menunggu
📈 Total Diproses: Surat dalam proses
✅ Total Selesai: Surat yang sudah selesai
❌ Total Ditolak: Surat yang ditolak
```

#### B. Grafik Bulanan
```bash
📈 Chart per bulan berdasarkan status
🎯 Trend analysis untuk performa
```

### 🗄️ Database Improvement
**OPTIMIZED**: Struktur database yang lebih baik

#### A. Tabel Renamed
```bash
# Old → New
statistik_pendidikans → statistik_pendidikan
statistik_pekeraans → statistik_pekerjaan
statistik_umurs → statistik_umur
rekap_surat_keluars → rekap_surat_keluar
```

#### B. Migration Cleanup
```bash
✅ File migration duplikat dibersihkan
✅ Struktur tabel konsisten
✅ Naming convention yang benar
```

---

## 🧪 Testing Fitur v3.1

### 1. Test Rekap Surat Keluar

#### A. Test Admin Panel
```bash
# Login sebagai admin
👤 Email: admin@desa.com
🔑 Password: password

# Test CRUD operations:
📋 Index: http://localhost:8000/admin/laporan/rekap-surat-keluar
✅ Lihat daftar rekap surat
✅ Filter berdasarkan status
✅ Create rekap baru
✅ Edit rekap existing
✅ Delete rekap

# Test sync function:
🔄 Klik tombol "Sync Data"
✅ Verifikasi data tersinkronkan
```

#### B. Test Public View
```bash
# Akses tanpa login:
📖 URL: http://localhost:8000/rekap-surat-keluar
✅ Lihat data rekap (read-only)
✅ Filter berdasarkan status
✅ Search nama pemohon
✅ Test responsive mobile
```

### 2. Test Sinkronisasi Data

#### A. Test Artisan Commands
```bash
# Check status tabel:
php artisan check:tables
✅ Verifikasi jumlah record per tabel

# Sync manual:
php artisan sync:rekap-surat
✅ Cek output: "Total synced: X records"

# Verifikasi di database:
✅ Cek tabel rekap_surat_keluar
✅ Pastikan data tersinkronkan
```

#### B. Test Dummy Data
```bash
# Generate dummy data:
php artisan db:seed --class=DummySuratSeeder
✅ Verifikasi data dummy terisi

# Sync setelah seeding:
php artisan sync:rekap-surat
✅ Pastikan rekap terupdate
```

---

## 📊 Fitur Unggulan v3.0

### 🆕 Statistik 3D Interactive
- **3D Bar Charts** dengan efek kedalaman
- **3D Pie Charts** dengan animasi smooth
- **Hover Effects** yang responsif
- **Mobile-Optimized** untuk semua device

### 🎛️ Admin Panel
- **CRUD Lengkap** untuk 3 kategori statistik
- **Auto-calculation** total data
- **Form validation** dan error handling
- **Export PDF** laporan statistik

### 📱 User Experience
- **Responsive Design** di semua screen
- **Breadcrumb Navigation** yang jelas
- **Flash Messages** untuk feedback
- **Touch-friendly** interface mobile

---

## 🔗 URL Penting

### Admin URLs
```bash
📊 Dashboard: http://localhost:8000/admin
📈 Statistik Index: http://localhost:8000/admin/statistik
👔 Data Pekerjaan: http://localhost:8000/admin/statistik/pekerjaan
👥 Data Umur: http://localhost:8000/admin/statistik/umur
🎓 Data Pendidikan: http://localhost:8000/admin/statistik/pendidikan
📋 Rekap Surat Keluar: http://localhost:8000/admin/laporan/rekap-surat-keluar
```

### Public URLs
```bash
🏠 Homepage: http://localhost:8000
📊 Statistik Pekerjaan: http://localhost:8000/statistik
👥 Statistik Umur: http://localhost:8000/statistik/umur
🎓 Statistik Pendidikan: http://localhost:8000/statistik/pendidikan
📋 Rekap Surat Keluar: http://localhost:8000/rekap-surat-keluar
📝 Register: http://localhost:8000/register
```

---

## 👨‍💻 Developer Info

**Project**: Sistem Surat Keterangan Desa Ganten v3.1  
**Developer**: Rigel Sayudha  
**Email**: rigel.sayudha@example.com  
**GitHub**: https://github.com/rigel-sayudha/desaganten  
**Last Update**: 8 September 2025  
**New Features**: Rekap Surat Keluar System, Database Optimization  

---

## 🎯 Roadmap

### Coming Soon
- [ ] Export Excel untuk statistik
- [ ] Email notification system
- [ ] API REST untuk mobile app
- [ ] Advanced analytics dashboard
- [ ] Multi-language support

---

**🎉 Selamat! Sistem Desa Ganten v3.0 berhasil diinstall!**

*Nikmati fitur statistik 3D interactive dan CRUD lengkap untuk manajemen data kependudukan yang modern dan visual.*
