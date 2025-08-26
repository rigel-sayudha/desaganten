# Role-Based Access Control Testing

## Test Users Created:
- **Admin User**: admin@test.com / password123 (role: admin)
- **Regular User**: user@test.com / password123 (role: user)

## Testing Scenarios:

### 1. Admin Access Tests:
- ✅ Admin dapat login ke system
- ✅ Admin dapat mengakses `/admin/dashboard`
- ✅ Admin dapat mengakses semua route admin (`/admin/*`)
- ✅ Admin redirect ke admin dashboard setelah login
- ❌ Admin tidak dapat mengakses route khusus user (jika ada)

### 2. User Access Tests:
- ✅ User dapat login ke system
- ✅ User redirect ke home page setelah login
- ❌ User tidak dapat mengakses `/admin/dashboard`
- ❌ User tidak dapat mengakses route admin (`/admin/*`)
- ✅ User dapat mengakses profile dan fitur umum

### 3. Middleware Protection Tests:
- ✅ Semua route `/admin/*` dilindungi dengan middleware `role:admin`
- ✅ Akses tanpa authentication redirect ke login
- ✅ Akses dengan role salah redirect dengan pesan error

## Protected Admin Routes:
- `/admin/dashboard`
- `/admin/surat/*`
- `/admin/user/*`
- `/admin/wilayah/*`
- `/admin/settings`
- `/admin/surat/preview-domisili`
- `/admin/surat/preview-data/{jenis}`
- `/admin/surat/detail/{id}`

## Manual Testing Instructions:
1. Buka http://127.0.0.1:8000/login
2. Login dengan user biasa (user@test.com)
3. Coba akses http://127.0.0.1:8000/admin/dashboard
4. Verifikasi redirect ke home dengan error message
5. Logout dan login dengan admin (admin@test.com)
6. Verifikasi admin dapat mengakses semua halaman admin
