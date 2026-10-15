<?php
// Memanggil file koneksi
require_once '../../models/m_koneksi.php';

// Membuat instance dari class Koneksi
$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Mengambil nilai filter dari URL (jika ada)
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Menyusun query berdasarkan filter status
if ($filter_status == 'menunggu_pinjam') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'menunggu_persetujuan_pinjam' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'dipinjam') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'dipinjam' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'menunggu_kembali') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'menunggu_persetujuan_kembali' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'dikembalikan') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'dikembalikan' ORDER BY transaksi.id_transaksi DESC";
} else {
    // Default: tampilkan semua
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            ORDER BY transaksi.id_transaksi DESC";
}

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Peminjaman & Pengembalian</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Data Transaksi Peminjaman & Pengembalian Buku</h4>
        </div>
        <div class="card-body">
            
            <!-- Form / Tombol Filter Status -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Filter Berdasarkan Status:</label>
                    <div class="btn-group w-100 flex-wrap" role="group">
                        <a href="transaksi.php" class="btn btn-outline-primary <?= ($filter_status == '') ? 'active' : ''; ?>">Semua</a>
                        <a href="transaksi.php?status=menunggu_pinjam" class="btn btn-outline-info <?= ($filter_status == 'menunggu_pinjam') ? 'active' : ''; ?>">Menunggu Acc Pinjam</a>
                        <a href="transaksi.php?status=dipinjam" class="btn btn-outline-warning text-dark <?= ($filter_status == 'dipinjam') ? 'active' : ''; ?>">Dipinjam</a>
                        <a href="transaksi.php?status=menunggu_kembali" class="btn btn-outline-secondary <?= ($filter_status == 'menunggu_kembali') ? 'active' : ''; ?>">Menunggu Acc Kembali</a>
                        <a href="transaksi.php?status=dikembalikan" class="btn btn-outline-success <?= ($filter_status == 'dikembalikan') ? 'active' : ''; ?>">Dikembalikan</a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID Transaksi</th>
                            <th>ID Anggota</th>
                            <th>Nama Peminjam</th>
                            <th>ID Buku</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { 
                        ?>
                            <tr>
                                <td class="text-center"><?= htmlspecialchars($row['id_transaksi']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['id_anggota']); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['id_buku']); ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['jumlah']); ?></td>
                                <td class="text-center"><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                <td class="text-center">
                                    <?= (!empty($row['tanggal_kembali']) && $row['tanggal_kembali'] != '0000-00-00') ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '<span class="text-muted">-</span>'; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 'menunggu_persetujuan_pinjam') : ?>
                                        <span class="badge bg-info text-dark">Menunggu Acc Pinjam</span>
                                    <?php elseif ($row['status'] == 'dipinjam') : ?>
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    <?php elseif ($row['status'] == 'menunggu_persetujuan_kembali') : ?>
                                        <span class="badge bg-secondary">Menunggu Acc Kembali</span>
                                    <?php elseif ($row['status'] == 'dikembalikan') : ?>
                                        <span class="badge bg-success">Dikembalikan</span>
                                    <?php else : ?>
                                        <span class="badge bg-dark"><?= htmlspecialchars($row['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['status'] == 'menunggu_persetujuan_pinjam') : ?>
                                        <!-- Tombol untuk menyetujui peminjaman -->
                                        <a href="proses_acc_pinjam.php?id=<?= $row['id_transaksi']; ?>" 
                                           class="btn btn-sm btn-primary" 
                                           onclick="return confirm('Setujui peminjaman buku ini?')">
                                           Setujui Pinjam
                                        </a>
                                    <?php elseif ($row['status'] == 'menunggu_persetujuan_kembali') : ?>
                                        <!-- Tombol untuk menyetujui pengembalian -->
                                        <a href="proses_kembali.php?id=<?= $row['id_transaksi']; ?>" 
                                           class="btn btn-sm btn-success" 
                                           onclick="return confirm('Setujui pengembalian buku ini?')">
                                           Setujui Pengembalian
                                        </a>
                                    <?php elseif ($row['status'] == 'dikembalikan') : ?>
                                        <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                                    <?php else : ?>
                                        <span class="text-muted small">Sedang dipinjam</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">Tidak ada data transaksi untuk status ini.</td>
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