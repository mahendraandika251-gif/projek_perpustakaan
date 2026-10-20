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
    <!-- Google Fonts Inter & Custom Style -->
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
        /* Card Buku Modern */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0 !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .card-img-top {
            border-bottom: 1px solid #f1f5f9;
        }
        /* Aksen Warna Soft Pastel Blue */
        .badge-kategori {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 500;
        }
        .btn-custom-primary {
            background-color: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-custom-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            color: #ffffff;
        }
        .btn-outline-custom {
            border-color: #cbd5e1;
            color: #475569;
            border-radius: 8px;
        }
        .btn-outline-custom:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
        }
        .form-control:focus {
            border-color: #92BDFF;
            box-shadow: 0 0 0 4px rgba(146, 189, 255, 0.25);
        }
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
            <!-- Menu Navigasi Utama -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link active fw-medium px-3" href="v_Dashboard_user.php">Katalog Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium px-3" href="pengembalian_user.php">Pengembalian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-medium px-3" href="history_peminjaman.php">History Peminjaman</a>
                </li>
            </ul>

            <!-- Informasi Nama User & Tombol Logout -->
            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small">
                    Halo, <strong class="text-dark">
                        <?php 
                        if (isset($_SESSION['data']['username'])) {
                            echo htmlspecialchars($_SESSION['data']['username']);
                        } else {
                            echo 'Anggota';
                        }
                        ?>
                    </strong>
                </span>
                <a href="../../controller/logout.php" class="btn btn-outline-danger btn-sm px-3 rounded-pill">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    
    <!-- Bagian Judul & Form Pencarian -->
    <div class="row mb-5 align-items-center g-3">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Katalog Buku Perpustakaan</h3>
            <p class="text-secondary mb-0">Temukan buku favoritmu dan lakukan peminjaman dengan mudah.</p>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="input-group">
                <input type="text" name="cari" class="form-control" placeholder="Cari judul, penulis, atau kategori..." value="<?= htmlspecialchars($keyword); ?>">
                <button class="btn btn-custom-primary px-4" type="submit">Cari</button>
                <?php if ($keyword != ''): ?>
                    <a href="v_Dashboard_user.php" class="btn btn-outline-custom">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Daftar Buku dalam Bentuk Grid Card -->
    <div class="row g-4">
        <?php 
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { 
                
                // Ambil nilai kolom foto dari database
                $nama_file_foto = trim($row['foto']);

                // Validasi apakah nama file benar-benar ada dan berformat gambar (bukan angka/kosong)
                $is_valid_image = (!empty($nama_file_foto) && !is_numeric($nama_file_foto) && (preg_match('/\.(jpg|jpeg|png|webp)$/i', $nama_file_foto)));

                // Path folder sesuai struktur folder "asset" Anda
                $path_relatif = '../../asset/img/' . $nama_file_foto;

                if ($is_valid_image && file_exists($path_relatif)) {
                    $foto = $path_relatif;
                } else {
                    // Fallback jika foto kosong atau tidak ditemukan di folder
                    $foto = 'https://via.placeholder.com/150x220?text=No+Cover';
                }
        ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100">
                    <div style="height: 240px; overflow: hidden; background-color: #f8fafc;" class="text-center position-relative">
                        <img src="<?= htmlspecialchars($foto); ?>" class="w-100 h-100" alt="Cover Buku" style="object-fit: cover;">
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge badge-kategori mb-2 align-self-start px-2.5 py-1.5 rounded-pill small"><?= htmlspecialchars($row['kategori']); ?></span>
                        <h5 class="card-title fs-6 fw-bold text-dark text-truncate mb-2" title="<?= htmlspecialchars($row['judul']); ?>"><?= htmlspecialchars($row['judul']); ?></h5>
                        <p class="card-text text-secondary small mb-1">Penulis: <span class="text-dark fw-medium"><?= htmlspecialchars($row['penulis']); ?></span></p>
                        <p class="card-text text-secondary small mb-3">Penerbit: <?= htmlspecialchars($row['penerbit']); ?> (<?= $row['tahun_terbit']; ?>)</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small text-secondary">Stok Buku:</span>
                            <?php if ($row['stok'] > 0): ?>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 small fw-semibold"><?= $row['stok']; ?> tersedia</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 small fw-semibold">Habis</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-auto pt-2">
                            <?php if ($row['stok'] > 0): ?>
                                <!-- Tombol yang memicu Modal Pop-up Form Pinjam -->
                                <button type="button" 
                                        class="btn btn-custom-primary w-100 btn-sm py-2 shadow-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPinjam<?= $row['id_buku']; ?>">
                                    Pinjam Buku
                                </button>
                            <?php else: ?>
                                <button class="btn btn-light text-muted w-100 btn-sm py-2 border" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Input Peminjaman untuk setiap buku -->
            <div class="modal fade" id="modalPinjam<?= $row['id_buku']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-start p-3">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold text-dark">Form Pinjam Buku</h5>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="proses_pinjam.php" method="POST">
                            <div class="modal-body">
                                <input type="hidden" name="id_buku" value="<?= $row['id_buku']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Judul Buku</label>
                                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($row['judul']); ?>" readonly>
                               </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Tanggal Pinjam</label>
                                    <input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Tanggal Kembali</label>
                                    <input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Jumlah Pinjam</label>
                                    <input type="number" name="jumlah" class="form-control" min="1" max="<?= $row['stok']; ?>" value="1" required>
                                    <small class="text-muted mt-1 d-block">Maksimal pinjam: <?= $row['stok']; ?> buku</small>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-outline-custom px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="simpan_pinjam" class="btn btn-custom-primary px-4">Simpan Peminjaman</button>
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
                <div class="alert alert-light border text-center py-5 text-secondary rounded-4 shadow-sm" role="alert">
                    <h5 class="fw-bold text-dark mb-1">Buku Tidak Ditemukan</h5>
                    <p class="mb-0 small">Maaf, buku yang Anda cari tidak tersedia di katalog.</p>
                </div>
            </div>
        <?php } ?>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>