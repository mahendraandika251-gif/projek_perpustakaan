<?php
// Mulai session jika Anda menggunakan fitur login/notifikasi (opsional)
session_start();

// Panggil file model anggota
// Pastikan path ke m_anggota.php sudah benar sesuai struktur folder Anda
require_once '../models/m_anggota.php'; 

// Cek apakah parameter id ada di URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    
    // Instansiasi class m_anggota
    $anggota = new m_anggota();
    
    // Panggil fungsi hapus_data
    $hapus = $anggota->hapus_data($id_user);
    
    if ($hapus) {
        // Jika berhasil, redirect kembali ke halaman data_anggota.php
        // Tambahkan parameter alert/pesan sukses jika diperlukan
        echo "<script>
                alert('Data anggota berhasil dihapus!');
                window.location.href = '../view/view_admin/data_anggota.php';
              </script>";
    } else {
        // Jika gagal
        echo "<script>
                alert('Gagal menghapus data anggota. Silakan coba lagi.');
                window.location.href = '../view/view_admin/data_anggota.php';
              </script>";
    }
} else {
    // Jika tidak ada ID di URL, kembalikan ke halaman awal
    header("Location: ../view/view_admin/data_anggota.php");
    exit();
}
?>