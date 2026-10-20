<?php
// Memulai sesi untuk mengambil data login user[cite: 6]
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Validasi apakah user sudah login[cite: 6]
if (!isset($_SESSION['data']['id_anggota'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../../index.php';</script>";
    exit;
}

$id_anggota = $_SESSION['data']['id_anggota'];
$nama_user  = $_SESSION['data']['username'];

// Query untuk mengambil seluruh riwayat transaksi (baik dipinjam maupun dikembalikan) milik user yang sedang login[cite: 6]
$sql = "SELECT transaksi.*, buku.judul, buku.penulis 
        FROM transaksi 
        JOIN buku ON transaksi.id_buku = buku.id_buku 
        WHERE transaksi.id_anggota = '$id_anggota' 
        ORDER BY transaksi.id_transaksi DESC";

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Peminjaman - Perpustakaan Digital</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        /* Navbar Putih Clean Minimalis */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
        }
        .navbar-brand {
            color: #0f172a !important;
            letter-spacing: -0.5px;
        }
        .navbar .nav-link {
            color: #64748b !important;
            transition: color 0.2s;
        }
        .navbar .nav-link:hover, .navbar .nav-link.active {
            color: #2563eb !important;
        }
        /* Card Kontainer */
        .card-custom {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        }
        .card-header-custom {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 24px;
        }
        /* Tabel dengan Kotak-Kotak Berwarna & Garis Jelas */
        .table-custom {
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .table-custom thead {
            background-color: #0f172a !important;
            color: #ffffff;
        }
        .table-custom thead th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border: 1px solid #334155 !important;
        }
        .table-custom tbody td {
            padding: 14px 16px;
            border: 1px solid #cbd5e1 !important;
            font-size: 0.925rem;
        }
        /* Warna Selang-seling Kotak Baris Tabel */
        .table-custom tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .table-custom tbody tr:nth-child(even) {
            background-color: #f1f5f9;
        }
        .table-custom tbody tr:hover {
            background-color: #e2e8f0;
        }
        /* Badge Jumlah & Status */
        .badge-jumlah {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            border-radius: 6px;
            padding: 4px 10px;
        }
    </style>
</head>
<body>

<!-- Navbar Putih Elegan -->
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container py-2">
        <a class="navbar-brand fw-bold fs-5" href="v_Dashboard_user.php">
            <span style="color: #2563eb;">Perpus</span>Digital
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link fw-medium px-3" href="v_Dashboard_user.php">Katalog Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium px-3" href="pengembalian_user.php">Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active fw-medium px-3" href="history_peminjaman.php">History Peminjaman</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small">
                    Halo, <strong class="text-dark"><?= htmlspecialchars($nama_user); ?></strong>[cite: 6]
                </span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm px-3 rounded-pill">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="card-custom">
        <div class="card-header-custom">
            <h4 class="fw-bold text-dark mb-1">Riwayat (History) Peminjaman Buku</h4>
            <p class="text-secondary small mb-0">Berikut adalah daftar seluruh riwayat transaksi peminjaman buku yang pernah Anda lakukan[cite: 6].</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-bordered align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Tanggal Kembali</th>
                            <th class="text-center" style="width: 140px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td class="text-center text-muted fw-medium"><?= $no++; ?></td>
                                <td>
                                    <span class="fw-bold text-dark d-block"><?= htmlspecialchars($row['judul']); ?></span>
                                </td>
                                <td class="text-secondary fw-medium"><?= htmlspecialchars($row['penulis']); ?></td>
                                <td class="text-center">
                                    <span class="badge-jumlah"><?= htmlspecialchars($row['jumlah']); ?></span>
                                </td>
                                <td class="text-center text-secondary small fw-medium"><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                <td class="text-center text-secondary small fw-medium">
                                    <?= (!empty($row['tanggal_kembali']) && $row['tanggal_kembali'] != '0000-00-00') ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '<span class="text-muted">-</span>'; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (strtolower($row['status']) == 'dipinjam') : ?>
                                        <span class="badge bg-warning text-dark px-2.5 py-1.5 fw-semibold">Dipinjam</span>
                                    <?php elseif (strtolower($row['status']) == 'dikembalikan') : ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 fw-semibold">Dikembalikan</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary px-2.5 py-1.5 fw-semibold"><?= htmlspecialchars($row['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-5">
                                    <p class="fw-medium mb-0">Belum ada riwayat peminjaman buku[cite: 6].</p>
                                </td>
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