<?php
// Memulai sesi untuk mengambil data login user
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Menangkap kata kunci pencarian jika ada
$keyword = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';

// Query untuk menampilkan buku dengan fitur pencarian
if ($keyword != '') {
    $sql = "SELECT * FROM buku WHERE judul LIKE '%$keyword%' OR penulis LIKE '%$keyword%' OR kategori LIKE '%$keyword%' ORDER BY id_buku DESC";
} else {
    $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
}

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog & Peminjaman Buku</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar dengan Menu Peminjaman, Pengembalian, & History -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="v_Dashboard_user.php">Perpustakaan Digital</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu Navigasi Utama -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active fw-semibold" href="v_Dashboard_user.php">Katalog Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="pengembalian_user.php">Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="history_peminjaman.php">History Peminjaman</a>
                </li>
            </ul>

            <!-- Informasi Nama User & Tombol Logout -->
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    Halo, <strong>
                        <?php 
                        // Memanggil username yang tersimpan di $_SESSION['data'] dari proses login
                        if (isset($_SESSION['data']['username'])) {
                            echo htmlspecialchars($_SESSION['data']['username']);
                        } else {
                            echo 'Anggota';
                        }
                        ?>
                    </strong>
                </span>
                <a href="../../controller/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4 mb-5">
    
    <!-- Bagian Judul & Form Pencarian -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold mb-0">Katalog Buku Perpustakaan</h3>
            <p class="text-muted">Cari buku favoritmu dan lakukan peminjaman.</p>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="input-group">
                <input type="text" name="cari" class="form-control" placeholder="Cari judul, penulis, atau kategori..." value="<?= htmlspecialchars($keyword); ?>">
                <button class="btn btn-primary" type="submit">Cari</button>
                <?php if ($keyword != ''): ?>
                    <a href="v_Dashboard_user.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Daftar Buku dalam Bentuk Grid Card -->
    <div class="row">
        <?php 
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                // Mengatur path foto (jika kosong atau default.jpg)
                $foto = (!empty($row['foto']) && $row['foto'] != 'default.jpg') ? '../../assets/img/' . $row['foto'] : 'https://via.placeholder.com/150?text=No+Cover';
        ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($foto); ?>" class="card-img-top" alt="Cover Buku" style="height: 220px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 align-self-start"><?= htmlspecialchars($row['kategori']); ?></span>
                        <h5 class="card-title text-truncate" title="<?= htmlspecialchars($row['judul']); ?>"><?= htmlspecialchars($row['judul']); ?></h5>
                        <p class="card-text text-muted small mb-1">Penulis: <?= htmlspecialchars($row['penulis']); ?></p>
                        <p class="card-text text-muted small mb-1">Penerbit: <?= htmlspecialchars($row['penerbit']); ?> (<?= $row['tahun_terbit']; ?>)</p>
                        <p class="card-text small fw-bold mb-3">
                            Stok: 
                            <?php if ($row['stok'] > 0): ?>
                                <span class="text-success"><?= $row['stok']; ?> tersedia</span>
                            <?php else: ?>
                                <span class="text-danger">Habis</span>
                            <?php endif; ?>
                        </p>
                        
                        <div class="mt-auto">
                            <?php if ($row['stok'] > 0): ?>
                                <!-- Tombol yang memicu Modal Pop-up Form Pinjam -->
                                <button type="button" 
                                        class="btn btn-primary w-100 btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPinjam<?= $row['id_buku']; ?>">
                                    Pinjam Buku
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary w-100 btn-sm" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Input Peminjaman untuk setiap buku -->
            <div class="modal fade" id="modalPinjam<?= $row['id_buku']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content text-start">
                        <div class="modal-header">
                            <h5 class="modal-title">Form Pinjam Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="proses_pinjam.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Judul Buku</label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars($row['judul']); ?>" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jumlah Pinjam</label>
                                    <input type="number" name="jumlah" class="form-control" min="1" max="<?= $row['stok']; ?>" value="1" required>
                                    <small class="text-muted">Maksimal pinjam: <?= $row['stok']; ?></small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="simpan_pinjam" class="btn btn-primary">Simpan Peminjaman</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal -->

        <?php 
            }
        } else { 
        ?>
            <div class="col-12">
                <div class="alert alert-warning text-center" role="alert">
                    Buku yang Anda cari tidak ditemukan.
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>