<?php
session_start();
require_once '../models/m_buku.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $buku = new m_buku();
    $action = $_POST['action'];

    $judul        = substr($_POST['judul'], 0, 150);
    $penulis      = substr($_POST['penulis'], 0, 100);
    $penerbit     = substr($_POST['penerbit'], 0, 100);
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori     = substr($_POST['kategori'], 0, 20);
    $stok         = (int)$_POST['stok'];
    $foto_filename= $_POST['foto_lama'] ?? 'default_cover.jpg';

    // Handling File Upload
    if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto_file']['tmp_name'];
        $file_name = $_FILES['foto_file']['name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($ext, $allowed)) {
            $new_filename = time() . '_' . substr(preg_replace('/[^a-zA-Z0-9._-]/', '', $file_name), 0, 80);
            $upload_dir   = '../assets/img/';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
            if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                $foto_filename = $new_filename;
            }
        }
    } elseif (!empty($_POST['foto_url'])) {
        $foto_filename = substr($_POST['foto_url'], 0, 100);
    }

    // Eksekusi Berdasarkan Action
    if ($action === 'add') {
        $simpan = $buku->tambah_data($judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto_filename);
        $pesan = $simpan ? "Buku berhasil ditambahkan!" : "Gagal menambah buku.";
    } elseif ($action === 'edit') {
        $id_buku = (int)$_POST['id_buku'];
        $simpan = $buku->edit($id_buku, $judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto_filename);
        $pesan = $simpan ? "Buku berhasil diperbarui!" : "Gagal memperbarui buku.";
    }

    echo "<script>
            alert('$pesan');
            window.location.href = '../view/view_admin/dashboard_buku.php';
          </script>";
} else {
    header("Location: ../view/view_admin/dashboard_buku.php");
    exit();
}
?>