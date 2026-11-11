<?php
// 1. Panggil session_start() HANYA SEKALI di baris paling atas
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// 2. Tangani proses POST jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi       = $_POST['id_transaksi'];
    $denda              = $_POST['denda'];
    $status_denda       = $_POST['status_denda'];
    $tanggal_hari_ini   = date('Y-m-d'); // Tanggal aktual pengembalian

    // Update status transaksi, tanggal kembali, denda, dan status denda ke database
    $sql_update = "UPDATE transaksi SET 
                   status = 'dikembalikan', 
                   tanggal_kembali = '$tanggal_hari_ini', 
                   denda = '$denda', 
                   status_denda = '$status_denda' 
                   WHERE id_transaksi = '$id_transaksi'";

    if ($koneksi->query($sql_update) === TRUE) {
        // Redirect langsung ke v_Dashboard_admin.php setelah alert diklik OK
        echo "<script>alert('Pengembalian buku berhasil diproses dan denda telah dicatat!'); window.location='form_kembali.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memproses pengembalian: " . $koneksi->error . "'); window.history.back();</script>";
        exit;
    }
}

// 3. Ambil ID transaksi dari URL untuk ditampilkan di form
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : '';

$sql = "SELECT transaksi.*, anggota.username, buku.judul 
        FROM transaksi 
        JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
        JOIN buku ON transaksi.id_buku = buku.id_buku 
        WHERE transaksi.id_transaksi = '$id_transaksi'";
$result = $koneksi->query($sql);
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data transaksi tidak ditemukan!'); window.location='transaksi.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pengembalian & Denda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-3">Form Pengembalian & Denda Buku</h4>
                <hr class="mb-4">
                
                <!-- Form mengarah ke halaman ini sendiri (METHOD POST) -->
                <form action="" method="POST">
                    <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Peminjam</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['username']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Judul Buku</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Tanggal Pinjam & Tenggat Kembali</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control" value="<?= $data['tanggal_pinjam']; ?>" readonly>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" value="<?= $data['tanggal_kembali']; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal Denda (Rp)</label>
                        <input type="number" name="denda" class="form-control" value="0" min="0" required>
                        <div class="form-text">Masukkan angka nominal denda (contoh: 5000 jika denda Rp 5.000). Kosongkan atau isi 0 jika tidak ada denda.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status Denda</label>
                        <select name="status_denda" class="form-select" required>
                            <option value="lunas">Lunas (Atau tidak ada denda)</option>
                            <option value="belum_lunas">Belum Lunas</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="transaksi.php" class="btn btn-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan & Selesaikan Pengembalian</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>