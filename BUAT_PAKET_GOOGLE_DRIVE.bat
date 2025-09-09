@echo off
chcp 65001 >nul
cls
echo ===============================================================================
echo                     CREATOR PAKET GOOGLE DRIVE
echo                    SISTEM SURAT KETERANGAN DESA GANTEN
echo ===============================================================================
echo.
echo Script ini akan membuat paket lengkap untuk didistribusi via Google Drive
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
echo                        STEP 1: PERSIAPAN FOLDER
echo ===============================================================================

:: Set lokasi project dan output
set PROJECT_DIR=%~dp0
set OUTPUT_DIR=%PROJECT_DIR%google-drive-package
set PACKAGE_NAME=desaganten-project-lengkap

echo [INFO] Project directory: %PROJECT_DIR%
echo [INFO] Output directory: %OUTPUT_DIR%

:: Buat folder output jika belum ada
if exist "%OUTPUT_DIR%" (
    echo [INFO] Menghapus folder output lama...
    rmdir /s /q "%OUTPUT_DIR%"
)

mkdir "%OUTPUT_DIR%"
mkdir "%OUTPUT_DIR%\%PACKAGE_NAME%"
echo [✓] Folder output dibuat

echo.
echo ===============================================================================
echo                        STEP 2: COPY FILES PROJECT
echo ===============================================================================

echo [INFO] Menyalin files project...

:: Copy semua file project kecuali yang tidak perlu
robocopy "%PROJECT_DIR%" "%OUTPUT_DIR%\%PACKAGE_NAME%" /E /XD node_modules vendor .git storage\logs storage\framework\cache storage\framework\sessions storage\framework\views bootstrap\cache google-drive-package /XF "*.log" ".DS_Store" "Thumbs.db" "*.tmp" /NFL /NDL /NJH /NJS

if %errorLevel% LEQ 3 (
    echo [✓] Files project berhasil disalin
) else (
    echo [✗] Error saat menyalin files project
    pause
    exit
)

echo.
echo ===============================================================================
echo                        STEP 3: BUAT DOKUMENTASI
echo ===============================================================================

:: Copy tutorial yang sudah ada
copy "%PROJECT_DIR%TUTORIAL_INSTALASI_INDONESIA.txt" "%OUTPUT_DIR%\" >nul
copy "%PROJECT_DIR%INSTALL_OTOMATIS.bat" "%OUTPUT_DIR%\%PACKAGE_NAME%\" >nul

echo [✓] Tutorial instalasi disalin

:: Buat README untuk Google Drive
echo [INFO] Membuat README untuk Google Drive...

(
echo ===============================================================================
echo                    PAKET SISTEM SURAT KETERANGAN DESA GANTEN
echo ===============================================================================
echo.
echo CARA INSTALASI CEPAT:
echo 1. Extract file desaganten-project-lengkap.zip
echo 2. Pindahkan folder 'desaganten' ke C:\xampp\htdocs\
echo 3. Jalankan INSTALL_OTOMATIS.bat sebagai Administrator
echo 4. Ikuti petunjuk di layar
echo.
echo CARA INSTALASI MANUAL:
echo 1. Baca file: TUTORIAL_INSTALASI_INDONESIA.txt
echo 2. Ikuti langkah demi langkah
echo.
echo PERSYARATAN SISTEM:
echo - Windows 10/11 ^(64-bit^)
echo - RAM minimum 4GB
echo - Storage kosong minimal 3GB
echo - Koneksi internet untuk download software
echo.
echo SOFTWARE YANG DIBUTUHKAN:
echo 1. XAMPP ^(Apache + MySQL + PHP^)
echo    Download: https://www.apachefriends.org/
echo.
echo 2. Composer ^(PHP Package Manager^)
echo    Download: https://getcomposer.org/
echo.
echo 3. Node.js ^(JavaScript Runtime^)
echo    Download: https://nodejs.org/
echo.
echo AKUN DEFAULT SETELAH INSTALASI:
echo - Admin: admin@desa.com / password
echo - User: Daftar sendiri melalui /register
echo.
echo URL APLIKASI:
echo - Homepage: http://localhost:8000
echo - Admin Panel: http://localhost:8000/admin
echo.
echo SUPPORT:
echo - Email: rigel.sayudha@example.com
echo - GitHub: https://github.com/rigel-sayudha/desaganten
echo.
echo Dibuat oleh: Rigel Sayudha
echo Tanggal: 2 September 2025
echo Versi: 2.0
echo ===============================================================================
) > "%OUTPUT_DIR%\README_GOOGLE_DRIVE.txt"

echo [✓] README Google Drive dibuat

:: Buat file info sistem
(
echo ===============================================================================
echo                            INFORMASI SISTEM
echo ===============================================================================
echo.
echo NAMA SISTEM: Sistem Surat Keterangan Desa Ganten
echo VERSI: 2.0
echo FRAMEWORK: Laravel 10.x
echo BAHASA: PHP 8.1+, JavaScript, HTML, CSS
echo DATABASE: MySQL
echo FRONTEND: Tailwind CSS, Alpine.js
echo BACKEND: Laravel, Livewire
echo.
echo FITUR UTAMA:
echo - 10 Jenis Surat Keterangan
echo - Dashboard Admin dan User
echo - Upload Foto KTP
echo - Generate PDF Report
echo - Statistik Real-time
echo - Mobile Responsive
echo - Multi-user Management
echo.
echo JENIS SURAT YANG TERSEDIA:
echo 1. Surat Keterangan Domisili
echo 2. Surat Keterangan Kelahiran
echo 3. Surat Keterangan Kematian
echo 4. Surat Keterangan Nikah
echo 5. Surat Keterangan Cerai
echo 6. Surat Keterangan Usaha
echo 7. Surat Keterangan Penghasilan
echo 8. Surat Keterangan Tidak Mampu
echo 9. Surat Keterangan Kelakuan Baik
echo 10. Surat Keterangan Belum Menikah
echo.
echo ===============================================================================
) > "%OUTPUT_DIR%\INFO_SISTEM.txt"

echo [✓] Info sistem dibuat

echo.
echo ===============================================================================
echo                        STEP 4: BUAT FILE ZIP
echo ===============================================================================

echo [INFO] Membuat file ZIP untuk Google Drive...

:: Gunakan PowerShell untuk compress
powershell -command "& {Add-Type -AssemblyName System.IO.Compression.FileSystem; [System.IO.Compression.ZipFile]::CreateFromDirectory('%OUTPUT_DIR%\%PACKAGE_NAME%', '%OUTPUT_DIR%\%PACKAGE_NAME%.zip')}"

if exist "%OUTPUT_DIR%\%PACKAGE_NAME%.zip" (
    echo [✓] File ZIP berhasil dibuat: %PACKAGE_NAME%.zip
) else (
    echo [✗] Gagal membuat file ZIP
    pause
    exit
)

:: Hitung ukuran file
for %%A in ("%OUTPUT_DIR%\%PACKAGE_NAME%.zip") do (
    set SIZE=%%~zA
    set /a SIZE_MB=!SIZE!/1024/1024
)

echo [INFO] Ukuran file ZIP: %SIZE_MB% MB

echo.
echo ===============================================================================
echo                        STEP 5: CLEANUP
echo ===============================================================================

echo [INFO] Membersihkan file temporary...

:: Hapus folder yang sudah di-zip
rmdir /s /q "%OUTPUT_DIR%\%PACKAGE_NAME%"

echo [✓] Cleanup selesai

echo.
echo ===============================================================================
echo                            PAKET SELESAI!
echo ===============================================================================
echo.
echo [✓] Paket Google Drive berhasil dibuat!
echo.
echo LOKASI FILES:
echo - Folder: %OUTPUT_DIR%
echo - ZIP file: %PACKAGE_NAME%.zip
echo - Tutorial: TUTORIAL_INSTALASI_INDONESIA.txt
echo - README: README_GOOGLE_DRIVE.txt
echo - Info: INFO_SISTEM.txt
echo.
echo LANGKAH SELANJUTNYA:
echo 1. Upload file %PACKAGE_NAME%.zip ke Google Drive
echo 2. Set sharing ke "Anyone with the link can view"
echo 3. Copy link sharing dan bagikan
echo 4. Sertakan tutorial instalasi
echo.
echo CARA UPLOAD KE GOOGLE DRIVE:
echo 1. Buka https://drive.google.com
echo 2. Login dengan akun Google
echo 3. Klik "New" > "File upload"
echo 4. Pilih file: %PACKAGE_NAME%.zip
echo 5. Tunggu upload selesai
echo 6. Klik kanan pada file > "Get link"
echo 7. Set ke "Anyone with the link can view"
echo 8. Copy link dan bagikan
echo.
echo TEMPLATE SHARING MESSAGE:
echo "Silakan download Sistem Surat Keterangan Desa Ganten v2.0
echo Link download: [PASTE_LINK_DISINI]
echo Baca file README_GOOGLE_DRIVE.txt untuk cara instalasi"
echo.
echo Apakah ingin membuka folder output? (Y/N)
set /p choice=Pilihan: 

if /i "%choice%"=="Y" (
    explorer "%OUTPUT_DIR%"
)

echo.
echo Terima kasih telah menggunakan Creator Paket Google Drive!
echo Dibuat oleh: Rigel Sayudha
echo.
pause
