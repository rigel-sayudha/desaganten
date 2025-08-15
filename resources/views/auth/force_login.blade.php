<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Silakan Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Silakan Login',
                text: 'Anda harus login sebagai penduduk untuk mengakses layanan ini.',
                confirmButtonColor: '#0088cc',
            }).then(() => {
                window.location.href = '/login';
            });
        });
    </script>
</body>
</html>
