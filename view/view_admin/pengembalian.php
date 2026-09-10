<?php
include_once '../../controller/c_data.p.php'; 
include_once '../template/navbar_admin.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Pengembalian Alat</title>
  <link rel="stylesheet" href="../template/style.css">
  <link rel="stylesheet" href="../template/dashboard_adm.css">
  <link rel="stylesheet" href="../template/petugas.css">
</head>
<body>
  
  <div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h2>Manajemen Pengembalian Alat</h2>
        <button onclick="window.print()" class="btn-cetak">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
    </div>
    

    <h3 class="section-title">Semua Riwayat Peminjaman <span class="count-badge"><?= count($data_riwayat_all) ?></span></h3>
    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
          <tr style="background-color: #222; color: white;">
            <th>Nama Peminjam</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data_riwayat_all)): ?>
            <?php foreach ($data_riwayat_all as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row->nama_peminjam) ?></td>
                <td align="center"><?= $row->tanggal_peminjaman ?></td>
                <td align="center"><?= $row->tanggal_pengembalian ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" align="center" style="padding: 15px;">Belum ada riwayat data.</td></tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>
  </div>
</body>
</html>