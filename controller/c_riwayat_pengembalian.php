<!-- mengelola proses riwayat pengembalian alat.” -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../models/m_riwayat_pengembalian.php';
$pengembalian = new pengembalian();

$id_session = $_SESSION['data']['id_user'] ?? null;
$data_riwayat = []; // Inisialisasi agar tidak undefined di view

try {
    // Aksi Update Status
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'update_status') {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $result = $pengembalian->A($id, $_GET['status'] ?? '');
            if ($result) {
                echo "<script>alert('Berhasil dikembalikan!'); window.location.href = '../view/view_user/riwayat_peminjaman.php';</script>";
            } else {
                echo "<script>alert('Gagal memproses!'); history.back();</script>";
            }
        }
        exit;
    }

    // data ditampilkan
    if ($id_session) {
        $data_riwayat = $pengembalian->tampil_riwayat($id_session);
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $data_riwayat = [];
}