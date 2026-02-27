# 🏛️ Sistem Surat Keterangan Desa Ganten v3.1

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-4.4+-FF6384.svg)](https://chartjs.org)
[![3D Charts](https://img.shields.io/badge/3D%20Charts-Interactive-success.svg)](https://github.com)

Sistem Pelayanan Digital untuk pengelolaan surat keterangan di Desa Ganten dengan fitur lengkap admin panel, user dashboard, PDF generation, **Statistik Kependudukan 3D Interactive**, dan **Sistem Rekap Surat Keluar**.

## 🎯 Fitur Utama

### 🆕 **Sistem Rekap Surat Keluar (NEW v3.1!)**
- 📋 **CRUD Lengkap** - Create, Read, Update, Delete rekap surat
- 🔄 **Auto Sync** - Sinkronisasi otomatis dari 11 tabel surat
- 📊 **Dashboard Analytics** - Statistik per status dan trend bulanan
- 🔍 **Advanced Filter** - Filter berdasarkan status, nama, jenis surat
- 📱 **Public View** - Transparansi data untuk masyarakat
- ⚡ **Artisan Commands** - `sync:rekap-surat` dan `check:tables`

### 🆕 **Statistik Kependudukan 3D (v3.0)**
- 📊 **3D Bar Charts** - Visualisasi data dengan efek kedalaman
- 🥧 **3D Pie Charts** - Chart interaktif dengan animasi 3D
- 📈 **3 Kategori Statistik**: Pekerjaan, Umur, Pendidikan
- 🎮 **Interactive Elements** - Hover effects dan smooth animations
- 📱 **Mobile Responsive** - Charts optimal di semua device
- ⚡ **Real-time Data** - Sinkronisasi dengan database
- 🔧 **CRUD Operations** - Full admin management

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
- 📊 Dashboard dengan statistik real-time dan 3D charts
- 🔍 Filter dan pencarian advanced
- 📄 Pagination otomatis
- ✅ Workflow approval
- 📈 Chart dan visualisasi data 3D interactive
- 👥 User management
- 📋 Report generation
- 🛠️ **CRUD Statistik** - Kelola data pekerjaan, umur, pendidikan
- 📊 **Export PDF** - Laporan statistik dalam format PDF
- 🎨 **Modern UI** - Interface yang konsisten dan user-friendly

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
- 📱 Responsive design dengan 3D charts mobile-optimized
- 🖥️ Desktop compatibility
- 📲 iPhone/Android tested
- 🌐 Multi-device network access
- 👆 Touch-optimized controls
- 🎮 **Touch Interactions** - Swipe dan touch gestures untuk 3D charts

## 🛠️ Teknologi Stack

### Backend
- **Laravel 10.x** - PHP Framework terbaru
- **MySQL 8.0+** - Database relational
- **Eloquent ORM** - Database management
- **Laravel Sanctum** - Authentication

### Frontend
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Chart.js 4.4+** - Data visualization library
- **chartjs-plugin-3d** - 3D effects untuk charts
- **Blade Templates** - Laravel templating engine

### Development Tools
- **Composer** - PHP dependency manager
- **NPM/Node.js** - Frontend package manager
- **Vite** - Frontend build tool
- **Git** - Version control

## 🚀 Instalasi Lengkap

> 📖 **Tutorial Lengkap**: Lihat file `TUTORIAL_INSTALASI.txt` untuk panduan instalasi step-by-step yang detail

### Prasyarat
- PHP 8.1 atau lebih tinggi
- Composer 2.x
- MySQL 8.0+
- Node.js 18+ & NPM
- XAMPP (recommended untuk Windows)
- Browser modern (Chrome/Firefox untuk fitur 3D)

### Quick Start

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
php artisan db:seed --class=StatistikSeeder
php artisan db:seed --class=DummySuratSeeder  # NEW v3.1!
php artisan sync:rekap-surat                  # NEW v3.1!
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Development Server**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

8. **Test New Features** 🆕
```
# 3D Statistics
http://localhost:8000/admin/statistik          # Admin CRUD
http://localhost:8000/statistik                # Public 3D Charts

# Rekap Surat Keluar (NEW v3.1!)
http://localhost:8000/admin/laporan/rekap-surat-keluar  # Admin CRUD
http://localhost:8000/rekap-surat-keluar               # Public View
```

## 📊 Demo URLs

### Admin Panel
- **Dashboard**: `http://localhost:8000/admin`
- **Statistik Pekerjaan**: `http://localhost:8000/admin/statistik/pekerjaan`
- **Statistik Umur**: `http://localhost:8000/admin/statistik/umur`
- **Statistik Pendidikan**: `http://localhost:8000/admin/statistik/pendidikan`
- **Rekap Surat Keluar**: `http://localhost:8000/admin/laporan/rekap-surat-keluar` 🆕

### Public Pages  
- **3D Charts Pekerjaan**: `http://localhost:8000/statistik`
- **3D Charts Umur**: `http://localhost:8000/statistik/umur`
- **3D Charts Pendidikan**: `http://localhost:8000/statistik/pendidikan`
- **Rekap Surat Keluar**: `http://localhost:8000/rekap-surat-keluar` 🆕

### Default Login
- **Admin**: `admin@desa.com` / `password`
- **User**: Register di `http://localhost:8000/register`

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

**✅ STATUS: PRODUCTION READY v3.1**

### Core Features
- ✅ All 10 surat types implemented
- ✅ Admin panel fully functional
- ✅ User dashboard working
- ✅ PDF generation 100% success
- ✅ Mobile responsive
- ✅ Security implemented
- ✅ Testing completed
- ✅ Documentation complete

### 🆕 New Features v3.1
- ✅ **Rekap Surat Keluar System** - Complete CRUD management
- ✅ **Auto Data Sync** - Sinkronisasi dari 11 tabel surat
- ✅ **Analytics Dashboard** - Statistik per status dan trend
- ✅ **Public Transparency** - View rekap untuk masyarakat
- ✅ **Database Optimization** - Table naming consistency
- ✅ **Artisan Commands** - sync:rekap-surat & check:tables

### 🆕 Features v3.0
- ✅ **3D Interactive Charts** - Bar & Pie charts dengan efek 3D
- ✅ **Statistics CRUD** - Full admin management untuk data statistik
- ✅ **Real-time Data** - Sinkronisasi database dengan chart
- ✅ **Mobile 3D Support** - Charts responsive di semua device
- ✅ **Chart.js 4.4+** - Library chart terbaru dengan plugin 3D
- ✅ **Smooth Animations** - Transisi dan hover effects yang halus
- ✅ **Auto-calculation** - Total otomatis untuk data input

## 🎯 Roadmap

### 🔮 Coming Soon
- [ ] Export Excel untuk statistik
- [ ] Email notifications system
- [ ] API REST untuk mobile app
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] Dark mode theme

## 🤝 Contributing

Kontribusi sangat diterima! Silakan:

1. Fork project ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Project ini menggunakan [MIT License](LICENSE).

## 📧 Contact

**Developer:** Rigel Sayudha  
**Email:** rigel.sayudha@example.com  
**GitHub:** [@rigel-sayudha](https://github.com/rigel-sayudha)  
**Project Link:** [https://github.com/rigel-sayudha/desaganten](https://github.com/rigel-sayudha/desaganten)

---

⭐ **Jangan lupa berikan star jika project ini membantu!**

## 📚 Acknowledgments

- [Laravel Framework](https://laravel.com) - The best PHP framework
- [Chart.js](https://chartjs.org) - Beautiful 3D charts
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF generation

**🏛️ Sistem Surat Keterangan Desa Ganten v3.0**  
*Bringing digital transformation to village administration with 3D data visualization*
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
