<?php

include_once '../../controller/c_peminjaman.php'; 
include_once '../template/navbar.php';


$data_menunggu = array_filter($data_peminjam, function($item) {
    return $item['status'] == 'Menunggu';
});

$data_selesai = array_filter($data_peminjam, function($item) {
    return $item['status'] != 'Menunggu';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Persetujuan | YOUKCAMP</title>
  <link rel="stylesheet" href="../template/style.css">
  <link rel="stylesheet" href="../template/dashboard_adm.css">
  <style>
    .badge { padding: 5px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
    .status-menunggu { background-color: #f1c40f; color: #fff; }
    .status-disetujui { background-color: #2ecc71; color: #fff; }
    .status-ditolak { background-color: #e74c3c; color: #fff; }
    .section-title { margin-top: 40px; margin-bottom: 15px; color: #333; display: flex; align-items: center; gap: 10px; }
    .count-badge { background: #eee; padding: 2px 8px; border-radius: 10px; font-size: 14px; }
    .btn-setuju { background-color: #2ecc71; color: white; }
    .btn-tolak { background-color: #e74c3c; color: white; }
    table { margin-bottom: 20px; }
    th { background-color: #222; color: white; }
  </style>
</head>
<body>
  
  <div class="main-content">
    <h2>Manajemen Peminjaman Alat</h2>
    <hr>

    <h3 class="section-title">
      <i class="fas fa-clock"></i> Menunggu Persetujuan 
      <span class="count-badge"><?= count($data_menunggu) ?></span>
    </h3>
    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th>Kode</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Tgl Pinjam</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data_menunggu)): ?>
            <?php foreach ($data_menunggu as $row): ?>
            <tr>
              <td style="text-align: center;"><?= $row['kode_pinjam'] ?></td>
              <td><?= $row['nama_peminjam'] ?></td>
              <td><?= $row['nama_alat'] ?></td>
              <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['tanggal_meminjam'])) ?></td>
              <td style="text-align: center;"><?= $row['jumlah'] ?></td>
              <td style="text-align: center;"> 
                <button type="button" class="btn-action btn-setuju" onclick="konfirmasiAksi('<?= $row['kode_pinjam'] ?>', 'setujui')">Setujui</button>
                <button type="button" class="btn-action btn-tolak" onclick="konfirmasiAksi('<?= $row['kode_pinjam'] ?>', 'tolak')">Tolak</button>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align: center; padding: 15px; color: #999;">Tidak ada permintaan baru.</td></tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>

    <h3 class="section-title">
      <i class="fas fa-check-double"></i> Riwayat Proses
      <span class="count-badge"><?= count($data_selesai) ?></span>
    </h3>
    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse; opacity: 0.8;">
        <thead>
          <tr style="background-color: #555;">
            <th>Kode</th>
            <th>Peminjam</th>
            <th>Alat</th>
            <th>Tgl Pinjam</th>
            <th>Status Akhir</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data_selesai)): ?>
            <?php foreach ($data_selesai as $row): ?>
            <tr>
              <td style="text-align: center;"><?= $row['kode_pinjam'] ?></td>
              <td><?= $row['nama_peminjam'] ?></td>
              <td><?= $row['nama_alat'] ?></td>
              <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['tanggal_meminjam'])) ?></td>
              <td style="text-align: center;">
                <span class="badge <?= ($row['status'] == 'Disetujui') ? 'status-disetujui' : 'status-ditolak' ?>">
                  <?= $row['status'] ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align: center; padding: 15px; color: #999;">Belum ada riwayat proses.</td></tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>
  </div>

  <script>
    function konfirmasiAksi(id, aksi) {
      let pesan = (aksi === 'setujui') ? "Setujui peminjaman ini?" : "Tolak peminjaman ini?";
      if (confirm(pesan)) {
        window.location.href = "../../controller/c_peminjaman.php?aksi=update_status&id=" + id + "&status=" + aksi;
      }
    }
  </script>
</body>
</html>
