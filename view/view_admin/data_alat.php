<?php 
include_once '../../controller/c_peralatan.php';
include_once '../template/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Alat</title>
    <link rel="stylesheet" href="../template/style.css">
    <link rel="stylesheet" href="../template/dashboard_adm.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../template/data.css">
</head>
<body>
  <div class="main-content">
    <h2>Daftar Alat</h2>
    <hr>
    <button type="button" class="btn-action btn-add" onclick="openTambahModal()">
      <i class="fa-solid fa-plus"></i> Tambah Data Alat
    </button>

    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Alat</th>
            <th>Harga Sewa</th> 
            <th>Jumlah</th>
            <th>Kategori</th> <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if (!empty($data_alat)): 
            $no = 1; 
            foreach ($data_alat as $data): ?>
          <tr>
            <td style="text-align:center;"><?= $no++ ?></td>
            <td style="text-align:center;">
                <img src="../../asset/<?=$data->foto_alat?>" alt="Alat" class="img-box">
            </td>
            <td><strong><?=$data->nama_alat?></strong></td>
            <td>Rp <?= number_format((int)$data->harga, 0, ',', '.') ?></td> 
            <td style="text-align:center;"><?=$data->jumlah?> Pcs</td>
            <td style="text-align:center;">
                <span class="category-badge"><?=$data->nama_kategori?></span>
            </td>   
            <td> 
              <div style="display: flex; gap: 5px; justify-content: center;">
                <button type="button" class="btn-action btn-update" 
                        onclick="openUpdateModal('<?=$data->id_alat?>', '<?=$data->nama_alat?>', '<?=$data->jumlah?>', '<?=$data->id_kategori?>', '<?=$data->foto_alat?>', '<?=$data->harga?>')">
                  <i class="fa-solid fa-pen"></i> Update
                </button>
                <a href="../../controller/c_peralatan.php?aksi=hapus&id_alat=<?= $data->id_alat; ?>" onclick="return confirm('Hapus alat ini?')" class="btn-action btn-delete">
                   <i class="fa-solid fa-trash"></i> Hapus
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="7" style="text-align: center;">Tidak ada data alat</td></tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>
  </div>

  <div id="tambahModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Tambah Alat Baru</h3>
        <span class="close-btn" onclick="closeTambahModal()">&times;</span>
      </div>
      <form action="../../controller/c_peralatan.php?aksi=tambah" method="POST">
        <div class="form-group">
          <label>Nama Alat</label>
          <input type="text" name="nama_alat" required>
        </div>
        <div class="form-group">
          <label>Harga Sewa (Rp)</label>
          <input type="number" name="harga" required>
        </div>
        <div class="form-group">
          <label>Jumlah (Stok)</label>
          <input type="number" name="jumlah" required>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php if (!empty($data_kategori)): ?>
                <?php foreach ($data_kategori as $kat): ?>
                    <option value="<?= $kat->id_kategori ?>"><?= $kat->nama_kategori ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
           <label>Nama File Foto</label>
           <input type="text" name="foto_alat" required placeholder="nama_file.jpg">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-action btn-batal" onclick="closeTambahModal()">Batal</button>
          <button type="submit" class="btn-action btn-simpan">Simpan Alat</button>
        </div>
      </form>
    </div>
  </div>

  <div id="updateModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Update Data Alat</h3>
        <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
      </div>
      <form action="../../controller/c_peralatan.php?aksi=edit" method="POST">
        <input type="hidden" name="id_alat" id="modal_id_alat">
        <div class="form-group">
          <label>Nama Alat</label>
          <input type="text" name="nama_alat" id="modal_nama_alat" required>
        </div>
        <div class="form-group">
          <label>Harga Sewa (Rp)</label>
          <input type="number" name="harga" id="modal_harga" required>
        </div>
        <div class="form-group">
          <label>Jumlah (Stok)</label>
          <input type="number" name="jumlah" id="modal_jumlah" required>
        </div>
        <div class="form-group">
          <label>Kategori</label>
          <select name="id_kategori" id="modal_id_kategori" required>
            <?php if (!empty($data_kategori)): ?>
                <?php foreach ($data_kategori as $kat): ?>
                    <option value="<?= $kat->id_kategori ?>"><?= $kat->nama_kategori ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div class="form-group">
           <label>Nama File Foto</label>
           <input type="text" name="foto_alat" id="modal_foto_alat" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-action btn-batal" onclick="closeUpdateModal()">Batal</button>
          <button type="submit" class="btn-action btn-simpan">Simpan Perubahan</button>
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
    
    
    function openUpdateModal(id, nama, jumlah, id_kat, foto, harga) {
      document.getElementById('updateModal').style.display = 'flex';
      document.getElementById('modal_id_alat').value = id;
      document.getElementById('modal_nama_alat').value = nama;
      document.getElementById('modal_jumlah').value = jumlah;
      document.getElementById('modal_id_kategori').value = id_kat; 
      document.getElementById('modal_foto_alat').value = foto;
      document.getElementById('modal_harga').value = harga;
    }
    
    function closeUpdateModal() { 
      document.getElementById('updateModal').style.display = 'none'; 
    }

    window.onclick = function(event) {
      if (event.target.className === 'modal-overlay') {
        closeTambahModal();
        closeUpdateModal();
      }
    }
  </script>
</body>
</html>
