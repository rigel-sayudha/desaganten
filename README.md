# 🏛️ Sistem Surat Keterangan Desa Ganten

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC.svg)](https://tailwindcss.com)

Sistem Pelayanan Digital untuk pengelolaan surat keterangan di Desa Ganten dengan fitur lengkap admin panel, user dashboard, dan PDF generation.

## 🎯 Fitur Utama

### ✅ **10 Jenis Surat Keterangan Lengkap**
- 📄 **Surat Domisili** - Keterangan tempat tinggal
- 🏠 **Surat Tidak Mampu** - Keterangan ekonomi kurang mampu
- 💍 **Surat Belum Menikah** - Keterangan status belum menikah
- ⚰️ **Surat Kematian** - Keterangan kematian
- 🏢 **Surat Usaha** - Keterangan usaha/SIUP
- 🆔 **Surat SKCK** - Surat Keterangan Catatan Kepolisian
- 🪪 **Surat KTP** - Pengantar pembuatan KTP
- 👶 **Surat Kelahiran** - Keterangan kelahiran
- 👨‍👩‍👧‍👦 **Surat KK** - Pengantar Kartu Keluarga
- 🔍 **Surat Kehilangan** - Keterangan kehilangan dokumen

### 🎛️ **Panel Admin Canggih**
- 📊 Dashboard dengan statistik real-time
- 🔍 Filter dan pencarian advanced
- 📄 Pagination otomatis
- ✅ Workflow approval
- 📈 Chart dan visualisasi data
- 👥 User management
- 📋 Report generation

### 👤 **Dashboard User Personal**
- 🏠 Personal dashboard
- 📝 Form pengajuan surat online
- 📊 Status tracking real-time
- 🔒 Data isolation (hanya data sendiri)
- 📱 Mobile-friendly interface
- 📥 Download PDF surat

### 🔒 **Keamanan & Privacy**
- 🛡️ User authentication
- 🔐 Data isolation per user
- 🚫 Cross-user contamination prevention
- 🔑 Role-based access control
- 🔒 CSRF protection

### 📱 **Mobile & Multi-Device**
- 📱 Responsive design
- 🖥️ Desktop compatibility
- 📲 iPhone/Android tested
- 🌐 Multi-device network access
- 👆 Touch-optimized controls

## 🚀 Instalasi

### Prasyarat
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 8.0+
- Node.js & NPM
- Git

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/rigel-sayudha/desaganten.git
cd desaganten
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desaganten
DB_USERNAME=root
DB_PASSWORD=
```

5. **Database Migration & Seeding**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Development Server**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 📖 Penggunaan

### Akses Admin
- **URL:** `http://localhost:8000/admin`
- **Default Login:** admin@desa.com / password

### Akses User
- **URL:** `http://localhost:8000`
- **Register:** Buat akun baru atau gunakan test user

### Fitur Report
- **HTML Report:** `http://localhost:8000/report/system`
- **PDF Download:** `http://localhost:8000/report/system/pdf`

## 📊 Project Status

**✅ STATUS: PRODUCTION READY**

- ✅ All 10 surat types implemented
- ✅ Admin panel fully functional
- ✅ User dashboard working
- ✅ PDF generation 100% success
- ✅ Mobile responsive
- ✅ Security implemented
- ✅ Testing completed
- ✅ Documentation complete

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# profile-desa
# desaganten
