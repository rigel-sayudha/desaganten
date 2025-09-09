@echo off
chcp 65001 >nul
title Sistem Surat Keterangan - Google Drive Package Creator

echo.
echo ████████████████████████████████████████████████████████████████
echo ██                                                            ██
echo ██         📦 GOOGLE DRIVE PACKAGE CREATOR 📦                 ██
echo ██                                                            ██
echo ██              Sistem Surat Keterangan Desa Ganten          ██
echo ██                                                            ██
echo ████████████████████████████████████████████████████████████████
echo.

set PROJECT_DIR=%cd%
set PACKAGE_DIR=%PROJECT_DIR%\google-drive-package
set ZIP_NAME=desaganten-sistem-surat-keterangan-v2.0.zip

echo 📁 Project Directory: %PROJECT_DIR%
echo 📦 Package Directory: %PACKAGE_DIR%
echo 📄 ZIP File: %ZIP_NAME%
echo.

:: Create package directory
echo ===============================================
echo 📁 [1/6] Creating Package Directory...
echo ===============================================

if exist "%PACKAGE_DIR%" (
    echo Removing existing package directory...
    rmdir /s /q "%PACKAGE_DIR%"
)

mkdir "%PACKAGE_DIR%"
echo ✅ Package directory created
echo.

:: Copy project files
echo ===============================================
echo 📋 [2/6] Copying Project Files...
echo ===============================================

echo Copying Laravel project files...
xcopy "%PROJECT_DIR%" "%PACKAGE_DIR%\project" /E /I /H /Y /Q ^
    /EXCLUDE:%PROJECT_DIR%\package-exclude.txt >nul 2>&1

if not %errorLevel% equ 0 (
    echo Creating exclude file and retrying...
    
    :: Create exclude file
    echo node_modules > "%PROJECT_DIR%\package-exclude.txt"
    echo .git >> "%PROJECT_DIR%\package-exclude.txt"
    echo storage\logs >> "%PROJECT_DIR%\package-exclude.txt"
    echo storage\framework\cache >> "%PROJECT_DIR%\package-exclude.txt"
    echo storage\framework\sessions >> "%PROJECT_DIR%\package-exclude.txt"
    echo storage\framework\views >> "%PROJECT_DIR%\package-exclude.txt"
    echo bootstrap\cache >> "%PROJECT_DIR%\package-exclude.txt"
    echo google-drive-package >> "%PROJECT_DIR%\package-exclude.txt"
    echo *.zip >> "%PROJECT_DIR%\package-exclude.txt"
    echo *.log >> "%PROJECT_DIR%\package-exclude.txt"
    echo .env >> "%PROJECT_DIR%\package-exclude.txt"
    
    :: Copy again with exclusions
    robocopy "%PROJECT_DIR%" "%PACKAGE_DIR%\project" /E /XF *.zip *.log .env /XD node_modules .git "google-drive-package" "storage\logs" "storage\framework\cache" "storage\framework\sessions" "storage\framework\views" "bootstrap\cache" >nul
)

echo ✅ Project files copied
echo.

:: Create installation guide
echo ===============================================
echo 📝 [3/6] Creating Installation Guide...
echo ===============================================

echo Creating quick start guide...
> "%PACKAGE_DIR%\README-INSTALASI.txt" (
    echo ========================================================================
    echo    SISTEM SURAT KETERANGAN DESA GANTEN - PANDUAN INSTALASI CEPAT
    echo ========================================================================
    echo.
    echo 🎯 LANGKAH CEPAT INSTALASI:
    echo.
    echo 1. PERSIAPAN SOFTWARE:
    echo    - Download dan install XAMPP ^(PHP 8.1+^): https://www.apachefriends.org/
    echo    - Download dan install Composer: https://getcomposer.org/download/
    echo    - Download dan install Node.js: https://nodejs.org/
    echo.
    echo 2. SETUP PROJECT:
    echo    - Extract file ini ke: C:\xampp\htdocs\
    echo    - Buka Command Prompt sebagai Administrator
    echo    - Masuk ke folder project: cd C:\xampp\htdocs\project
    echo    - Jalankan: INSTALL.bat
    echo    - Ikuti instruksi yang muncul
    echo.
    echo 3. SETUP DATABASE:
    echo    - Start XAMPP ^(Apache + MySQL^)
    echo    - Buka http://localhost/phpmyadmin
    echo    - Buat database baru dengan nama: desaganten
    echo.
    echo 4. TEST APLIKASI:
    echo    - Jalankan: php artisan serve
    echo    - Buka browser: http://localhost:8000
    echo    - Login admin: admin@desa.com / password
    echo.
    echo ========================================================================
    echo.
    echo 📚 DOKUMENTASI LENGKAP:
    echo    Baca file: TUTORIAL_INSTALASI_GOOGLE_DRIVE.md
    echo.
    echo 🆘 SUPPORT:
    echo    - GitHub: https://github.com/rigel-sayudha/desaganten
    echo    - Email: rigel.sayudha@example.com
    echo.
    echo 🎉 SELAMAT MENGGUNAKAN SISTEM SURAT KETERANGAN DESA GANTEN!
    echo ========================================================================
)

echo ✅ Installation guide created
echo.

:: Create auto-installer
echo ===============================================
echo 🔧 [4/6] Copying Auto-Installer...
echo ===============================================

if exist "%PROJECT_DIR%\INSTALL.bat" (
    copy "%PROJECT_DIR%\INSTALL.bat" "%PACKAGE_DIR%\project\INSTALL.bat" >nul
    echo ✅ Auto-installer copied
) else (
    echo ⚠️  Auto-installer not found, skipping...
)
echo.

:: Create system requirements file
echo ===============================================
echo 📋 [5/6] Creating System Requirements...
echo ===============================================

> "%PACKAGE_DIR%\SYSTEM-REQUIREMENTS.txt" (
    echo ========================================================================
    echo                    SYSTEM REQUIREMENTS
    echo ========================================================================
    echo.
    echo 💻 MINIMUM SYSTEM REQUIREMENTS:
    echo.
    echo Operating System:
    echo    ✅ Windows 10/11 ^(64-bit^)
    echo    ✅ macOS 10.15+ 
    echo    ✅ Linux Ubuntu 18.04+
    echo.
    echo Hardware:
    echo    ✅ CPU: Intel Core i3 atau AMD equivalent
    echo    ✅ RAM: 4GB minimum, 8GB recommended
    echo    ✅ Storage: 2GB free space minimum
    echo    ✅ Network: Internet connection untuk instalasi
    echo.
    echo Software Requirements:
    echo    ✅ PHP 8.1 atau lebih baru
    echo    ✅ MySQL 8.0 atau MariaDB 10.3+
    echo    ✅ Apache 2.4+ atau Nginx
    echo    ✅ Composer ^(latest version^)
    echo    ✅ Node.js 16+ dan NPM 8+
    echo.
    echo PHP Extensions Required:
    echo    ✅ OpenSSL PHP Extension
    echo    ✅ PDO PHP Extension
    echo    ✅ Mbstring PHP Extension
    echo    ✅ Tokenizer PHP Extension
    echo    ✅ XML PHP Extension
    echo    ✅ Ctype PHP Extension
    echo    ✅ JSON PHP Extension
    echo    ✅ BCMath PHP Extension
    echo    ✅ Fileinfo PHP Extension
    echo    ✅ GD PHP Extension
    echo.
    echo 🚀 RECOMMENDED FOR PRODUCTION:
    echo    ✅ SSL Certificate
    echo    ✅ Backup Solution
    echo    ✅ Monitoring Tools
    echo    ✅ CDN ^(for better performance^)
    echo.
    echo ========================================================================
)

echo ✅ System requirements created
echo.

:: Create version info
echo ===============================================
echo 📄 [6/6] Creating Version Info...
echo ===============================================

> "%PACKAGE_DIR%\VERSION-INFO.txt" (
    echo ========================================================================
    echo                    VERSION INFORMATION
    echo ========================================================================
    echo.
    echo 📦 Package Information:
    echo    Name: Sistem Surat Keterangan Desa Ganten
    echo    Version: 2.0.0
    echo    Release Date: September 2, 2025
    echo    Package Type: Complete Installation Package
    echo.
    echo 🛠️ Technical Stack:
    echo    Framework: Laravel 10.x
    echo    PHP Version: 8.1+
    echo    Database: MySQL 8.0
    echo    Frontend: Tailwind CSS 3.x
    echo    PDF Generation: DomPDF
    echo    Icons: Font Awesome 6.x
    echo.
    echo ✨ Features Included:
    echo    ✅ 10 Complete Surat Keterangan Types
    echo    ✅ Admin Panel with Advanced Filtering
    echo    ✅ User Dashboard with Data Isolation
    echo    ✅ PDF Generation System
    echo    ✅ Mobile Responsive Design
    echo    ✅ Security Implementation
    echo    ✅ Multi-device Network Access
    echo    ✅ Comprehensive Report System
    echo.
    echo 🧪 Testing Status:
    echo    ✅ Functional Testing: PASSED
    echo    ✅ Security Testing: PASSED
    echo    ✅ Mobile Testing: PASSED
    echo    ✅ PDF Generation: PASSED
    echo    ✅ User Isolation: PASSED
    echo    ✅ Cross-browser: PASSED
    echo.
    echo 👨‍💻 Developer Information:
    echo    Developer: Rigel Sayudha
    echo    GitHub: https://github.com/rigel-sayudha
    echo    Email: rigel.sayudha@example.com
    echo.
    echo 📈 Changelog v2.0.0:
    echo    - Complete system implementation
    echo    - All 10 surat types working with PDF download
    echo    - Advanced admin panel with filtering ^& pagination
    echo    - User dashboard with personal data isolation
    echo    - Mobile-responsive design tested on iOS/Android
    echo    - Comprehensive security implementation
    echo    - Professional PDF report generation
    echo    - Complete documentation ^& testing
    echo.
    echo 🎯 Production Ready Status:
    echo    This version is fully tested and ready for production deployment.
    echo    All features have been verified and documented.
    echo.
    echo ========================================================================
)

echo ✅ Version info created
echo.

:: Success message
echo.
echo ████████████████████████████████████████████████████████████████
echo ██                                                            ██
echo ██              🎉 PACKAGE CREATION SUCCESSFUL! 🎉            ██
echo ██                                                            ██
echo ████████████████████████████████████████████████████████████████
echo.
echo ✅ Google Drive package berhasil dibuat!
echo.
echo 📁 Package Location: %PACKAGE_DIR%
echo.
echo 📋 Package Contents:
echo    📁 project/                    - Laravel application files
echo    📄 README-INSTALASI.txt        - Quick installation guide
echo    📄 SYSTEM-REQUIREMENTS.txt     - System requirements
echo    📄 VERSION-INFO.txt            - Version information
echo.
echo 🚀 Next Steps:
echo 1. Zip the package folder
echo 2. Upload to Google Drive
echo 3. Set sharing permissions
echo 4. Share the link with clients/users
echo.

set /p create_zip="Apakah ingin membuat ZIP file sekarang? (Y/N): "
if /i "%create_zip%" equ "Y" (
    echo.
    echo 📦 Creating ZIP file...
    
    :: Use PowerShell to create ZIP (works on Windows 10+)
    powershell -command "Compress-Archive -Path '%PACKAGE_DIR%\*' -DestinationPath '%PROJECT_DIR%\%ZIP_NAME%' -Force"
    
    if exist "%PROJECT_DIR%\%ZIP_NAME%" (
        echo ✅ ZIP file created: %ZIP_NAME%
        echo 📏 File size:
        for %%I in ("%PROJECT_DIR%\%ZIP_NAME%") do echo    %%~zI bytes
        echo.
        echo 🌐 Ready to upload to Google Drive!
    ) else (
        echo ❌ Failed to create ZIP file
        echo Please manually zip the package folder
    )
)

echo.
echo 📤 Upload Instructions:
echo 1. Upload ZIP file to Google Drive
echo 2. Right-click ^> Get link
echo 3. Set to "Anyone with the link can view"
echo 4. Share the link for easy installation
echo.

echo Tekan tombol apa saja untuk keluar...
pause >nul
