<?php
session_start();
require_once '../models/m_buku.php'; 

if (isset($_GET['id'])) {
    $id_buku = $_GET['id'];
    $buku = new m_buku();
    
    $hapus = $buku->hapus_data($id_buku);
    
    if ($hapus) {
        echo "<script>
                alert('Data buku berhasil dihapus!');
                window.location.href = '../view/view_admin/dashboard_buku.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data buku.');
                window.location.href = '../view/view_admin/dashboard_buku.php';
              </script>";
    }
} else {
    header("Location: ../view/view_admin/dashboard_buku.php");
    exit();
}
?>