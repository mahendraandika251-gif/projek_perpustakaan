<?php
// Memulai sesi
session_start();

// Menghapus semua variabel sesi
$_SESSION = array();

// Jika ingin menghapus cookie sesi juga (opsional, untuk keamanan ekstra)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Menghancurkan sesi
session_destroy();

// Mengarahkan kembali ke halaman login (sesuaikan path relatif halaman login Anda)
header("Location: ../index.php"); // Contoh jika halaman login ada di luar folder view atau sesuaikan path-nya
exit();
?>