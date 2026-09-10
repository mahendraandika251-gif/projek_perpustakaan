<!-- proses logout/keluar dari halaman -->
<?php
session_start();

// Hancurkan semua data sesi saat ini
session_unset();
session_destroy();

// Variabel untuk menyimpan pesan
$pesan_logout = "Anda berhasil keluar dari akun. Anda akan diarahkan ke halaman login.";
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil Logout!',
            text: '<?= $pesan_logout; ?>',
            showConfirmButton: false,
            timer: 2000
            
          }).then(() => {
            // Setelah popup ditutup, alihkan pengguna ke halaman index.php
            window.location.href = "../index.php";
            
          });
          
        });
        </script>
       </body>
    </html>
