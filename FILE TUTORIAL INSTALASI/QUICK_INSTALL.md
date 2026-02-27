# 🚀 QUICK INSTALLATION GUIDE v3.1

## Prerequisites
- XAMPP with PHP 8.1+
- Composer 2.x
- Node.js 18+ & NPM

## Quick Steps

```bash
# 1. Clone & Enter Directory
git clone https://github.com/rigel-sayudha/desaganten.git
cd desaganten

# 2. Install Dependencies
composer install
npm install

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Configure Database (.env file)
DB_DATABASE=desaganten
DB_USERNAME=root
DB_PASSWORD=

# 5. Create Database in phpMyAdmin
# Database name: desaganten

# 6. Run Migrations & Seeders
php artisan migrate
php artisan db:seed
php artisan db:seed --class=StatistikSeeder
php artisan db:seed --class=DummySuratSeeder
php artisan sync:rekap-surat

# 7. Build Assets
npm run build

# 8. Start Server
php artisan serve --host=0.0.0.0 --port=8000
```

## Test URLs

### Admin (admin@desa.com / password)
- Dashboard: http://localhost:8000/admin
- Rekap Surat: http://localhost:8000/admin/laporan/rekap-surat-keluar
- Statistik: http://localhost:8000/admin/statistik

### Public
- Homepage: http://localhost:8000
- Rekap Surat: http://localhost:8000/rekap-surat-keluar
- 3D Charts: http://localhost:8000/statistik

## 📖 Complete Tutorial
See `TUTORIAL_INSTALASI_INDONESIA_V3.md` for detailed installation guide.

## 🆕 New Features v3.1
- ✅ Rekap Surat Keluar System
- ✅ Auto Data Synchronization  
- ✅ Analytics Dashboard
- ✅ Database Optimization
