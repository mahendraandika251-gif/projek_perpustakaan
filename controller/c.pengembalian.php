<!-- untuk mengelola data pengembalian alat, baik untuk user maupun petugas -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/m_pengembalian.php';
$pengembalian = new pengembalian();


$data_pengembalian = [];

try {
    // Ambil file yang sedang aktif
    $current_page = basename($_SERVER['PHP_SELF']);

    if ($current_page == 'riwayat_peminjaman.php') {
        // Jika di halaman riwayat user
        $id_session = $_SESSION['data']['id_user'] ?? null;
        if ($id_session) {
            $data_pengembalian = $pengembalian->tampil_riwayat($id_session);
        }
    } else {
        // Jika di halaman manajemen petugas 
        $data_pengembalian = $pengembalian->tampil_data_petugas();
    }
} catch (Exception $e) {
    $data_pengembalian = [];
}

// Logika Update Status
if (isset($_GET['aksi']) && $_GET['aksi'] == 'update_status') {
    $id = $_GET['id'] ?? null;
    $status_baru = $_GET['status'] ?? '';
    
    if ($id) {
        $result = $pengembalian->pindah_data($id, $status_baru);
        if ($result) {
            echo "<script>alert('Berhasil diproses!'); window.location.href = './../view/view_user/riwayat_peminjaman.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal memproses!'); history.back();</script>";
            exit;
        }
    }
}