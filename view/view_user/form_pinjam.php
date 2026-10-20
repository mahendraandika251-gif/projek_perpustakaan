<?php
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Karena diasumsikan sudah login dari awal atau menggunakan akun default,
// jika $_SESSION['id_anggota'] belum ada, kita set default (misal: ID 1) atau sesuaikan kebutuhan.
if (!isset($_SESSION['id_anggota'])) {
    $_SESSION['id_anggota'] = 1; // Ubah angka ini sesuai ID anggota yang valid di database Anda jika diperlukan
}

// Ambil ID Buku dari parameter URL
if (!isset($_GET['id_buku']) || empty($_GET['id_buku'])) {
    header("Location: ");
    exit();
}

$id_buku = mysqli_real_escape_string($koneksi, $_GET['id_buku']);

// Ambil data buku berdasarkan ID
$query = "SELECT * FROM buku WHERE id_buku = '$id_buku'";
$result = $koneksi->query($query);

if ($result->num_rows == 0) {
    echo "<script>
            alert('Buku tidak ditemukan!');
            window.location.href = '';
          </script>";
    exit();
}

$buku = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peminjaman Buku</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Formulir Peminjaman Buku</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Informasi Ringkas Buku -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <?php 
                            $foto = (!empty($buku['foto']) && $buku['foto'] != 'default.jpg') ? '../../assets/img/' . $buku['foto'] : 'https://via.placeholder.com/150?text=No+Cover';
                            ?>
                            <img src="<?= htmlspecialchars($foto); ?>" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: cover;" alt="Cover Buku">
                        </div>
                        <div class="col-md-8">
                            <h5 class="fw-bold"><?= htmlspecialchars($buku['judul']); ?></h5>
                            <p class="text-muted mb-1">Penulis: <?= htmlspecialchars($buku['penulis']); ?></p>
                            <p class="text-muted mb-1">Kategori: <?= htmlspecialchars($buku['kategori']); ?></p>
                            <p class="fw-bold text-success mb-0">Stok Tersedia: <?= $buku['stok']; ?> Buku</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Form Input Peminjaman -->
                    <form action="proses_pinjam_aksi.php" method="POST">
                        <!-- Kirim ID Buku secara tersembunyi -->
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                        <input type="hidden" name="stok_maksimal" value="<?= $buku['stok']; ?>">

                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label fw-bold">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label fw-bold">Rencana Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" min="<?= date('Y-m-d'); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="form-label fw-bold">Jumlah Buku yang Dipinjam</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="1" min="1" max="<?= $buku['stok']; ?>" required>
                            <div class="form-text text-muted">Maksimal peminjaman sesuai stok yang tersedia (<?= $buku['stok']; ?>).</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="katalog_buku.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>