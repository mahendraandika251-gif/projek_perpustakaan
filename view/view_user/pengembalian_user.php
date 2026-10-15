<?php
// Memulai sesi untuk mengambil data login user[cite: 15]
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Validasi apakah user sudah login[cite: 15]
if (!isset($_SESSION['data']['id_anggota'])) {
    header("location: ../../index.php");
    exit;
}

$id_anggota = $_SESSION['data']['id_anggota'];
$nama_user  = $_SESSION['data']['username'];

// Query untuk mengambil data transaksi yang statusnya 'dipinjam' khusus untuk user yang sedang login[cite: 15]
$sql = "SELECT transaksi.*, buku.judul, buku.penulis 
        FROM transaksi 
        JOIN buku ON transaksi.id_buku = buku.id_buku 
        WHERE transaksi.id_anggota = '$id_anggota' AND transaksi.status = 'dipinjam' 
        ORDER BY transaksi.id_transaksi DESC";

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku - Perpustakaan Digital</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="v_Dashboard_user.php">Perpustakaan Digital</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="v_Dashboard_user.php">Katalog Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-semibold" href="pengembalian_user.php">Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="history_peminjaman.php">History Peminjaman</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    Halo, <strong><?= htmlspecialchars($nama_user); ?></strong>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daftar Buku yang Sedang Anda Pinjam</h4>
        </div>
        <div class="card-body">
            <p class="text-muted">Berikut adalah buku yang sedang Anda pegang. Klik tombol <strong>"Kembalikan Buku"</strong> untuk mengajukan pengembalian ke Admin.</p>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Batas Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['penulis']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['jumlah']); ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_kembali'])); ?></td>
                                <td class="text-center">
                                    <a href="proses_kembali_user.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="btn btn-success btn-sm" 
                                       onclick="return confirm('Ajukan pengembalian buku ini kepada Admin?')">
                                       Kembalikan Buku
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada buku yang sedang Anda pinjam saat ini.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>