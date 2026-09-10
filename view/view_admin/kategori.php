<?php 
include_once '../../controller/c_kategori.php';
include_once '../template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori</title>
    <link rel="stylesheet" href="../template/style.css">
    <link rel="stylesheet" href="../template/dashboard_adm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../template/data.css">
</head>
<body>
  <div class="main-content">
    <h2>Daftar Kategori Alat</h2>
    <hr>
    <button type="button" class="btn-action btn-add" onclick="openTambahModal()">
      <i class="fa-solid fa-plus"></i> Tambah Kategori
    </button>

    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th width="10%">No</th>
            <th width="20%">ID Kategori</th>
            <th>Nama Kategori</th>
            <th width="20%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if (!empty($data_kategori)): 
            $no = 1; 
            foreach ($data_kategori as $data): ?>
          <tr>
            <td style="text-align:center;"><?= $no++ ?></td>
            <td style="text-align:center;"><strong>#<?= $data->id_kategori ?></strong></td>
            <td><?= $data->nama_kategori ?></td>
            <td> 
              <div style="display: flex; gap: 5px; justify-content: center;">
                <a href="../../controller/c_kategori.php?aksi=hapus&id_kategori=<?= $data->id_kategori; ?>" 
                   onclick="return confirm('Hapus kategori ini? Semua alat dengan kategori ini mungkin akan terpengaruh.')" 
                   class="btn-action btn-delete">
                   <i class="fa-solid fa-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="4" style="text-align: center;">Belum ada data kategori.</td></tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>
  </div>

  <div id="tambahModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Tambah Kategori Baru</h3>
        <span class="close-btn" onclick="closeTambahModal()">&times;</span>
      </div>
      <form action="../../controller/c_kategori.php?aksi=tambah" method="POST">
        <div class="form-group">
          <label>Nama Kategori</label>
          <input type="text" name="nama_kategori" placeholder="Contoh: Alat Masak, Tenda, Elektronik" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-action btn-batal" onclick="closeTambahModal()">Batal</button>
          <button type="submit" class="btn-action btn-simpan">Simpan Kategori</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openTambahModal() { 
      document.getElementById('tambahModal').style.display = 'flex'; 
    }
    
    function closeTambahModal() { 
      document.getElementById('tambahModal').style.display = 'none'; 
    }

    
    window.onclick = function(event) {
      if (event.target.className === 'modal-overlay') {
        closeTambahModal();
      }
    }
  </script>
</body>
</html>
