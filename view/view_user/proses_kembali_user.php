<?php
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Validasi apakah user sudah login[cite: 14]
if (!isset($_SESSION['data']['id_anggota'])) {
    header("location: ../../index.php");
    exit;
}

// Menangkap id_transaksi dari parameter URL[cite: 14]
if (isset($_GET['id'])) {
    $id_transaksi = intval($_GET['id']);
    $id_anggota   = $_SESSION['data']['id_anggota'];

    // 1. Ambil data transaksi milik user ini yang statusnya 'dipinjam'
    $query_cek = $koneksi->query("SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi' AND id_anggota = '$id_anggota' AND status = 'dipinjam'");
    $transaksi = $query_cek->fetch_assoc();

    if (!$transaksi) {
        echo "<script>
                alert('Data transaksi tidak ditemukan atau sudah diajukan pengembaliannya!');
                window.location.href='pengembalian_user.php';
              </script>";
        exit;
    }

    $tanggal_hari_ini = date('Y-m-d');

    // 2. Ubah status menjadi 'menunggu_persetujuan_kembali' (Menunggu Acc Admin)
    // Stok buku BELUM dikembalikan sekarang (stok bertambah nanti setelah admin menyetujui)
    $sql_update_transaksi = "UPDATE transaksi SET status = 'menunggu_persetujuan_kembali', tanggal_kembali = '$tanggal_hari_ini' WHERE id_transaksi = '$id_transaksi'";

    if ($koneksi->query($sql_update_transaksi)) {
        echo "<script>
                alert('Permintaan pengembalian berhasil dikirim! Menunggu persetujuan Admin.');
                window.location.href='pengembalian_user.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memproses pengembalian: " . $koneksi->error . "');
                window.location.href='pengembalian_user.php';
              </script>";
    }

} else {
    header("location: pengembalian_user.php");
    exit;
}
?>