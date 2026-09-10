<?php
include_once '../../controller/c_pelanggan.php';
include_once '../template/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data User/Pelanggan</title>
  <link rel="stylesheet" href="../template/style.css">
  <link rel="stylesheet" href="../template/dashboard_adm.css">
  
  <style>
    .modal-overlay {
      display: none;
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fff;
      width: 90%;
      max-width: 450px;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }

    .modal-header h3 { margin: 0; color: #333; }

    .close-btn {
      font-size: 28px;
      font-weight: bold;
      color: #777;
      cursor: pointer;
    }

    .form-group { margin-bottom: 15px; }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #555;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 25px;
    }

    .btn-action {
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .btn-add { background-color: #2ecc71; color: white; margin-bottom: 20px; }
    .btn-add:hover { background-color: #27ae60; }

    .btn-update { background-color: #f39c12; color: white; font-size: 13px; }
    .btn-update:hover { background-color: #d35400; }

    .btn-delete { background-color: #e74c3c; color: white; font-size: 13px; }
    .btn-delete:hover { background-color: #c0392b; }

    .btn-batal { background-color: #e0e0e0; color: #333; }
    .btn-simpan { background-color: #3498db; color: white; }
    .btn-simpan:hover { background-color: #2980b9; }
  </style>
</head>
<body>
  
  <div class="main-content">
    <h2>Daftar pengguna</h2>
    <hr>
    
    <button type="button" class="btn-action btn-add" onclick="openTambahModal()">
      + Tambah Data pengguna
    </button>

    <div class="table-container">
      <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
          <tr>
            <th>ID User</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          if (isset($data_pelanggan) && is_array($data_pelanggan)):
            foreach ($data_pelanggan as $data): ?>
          <tr>
            <td><?=$data->id_user?></td>
            <td><?=$data->username?></td>
            <td><?=$data->email?></td>
            <td><?=$data->role?></td>   
            <td style="text-align : center;"> 
              <div style="display: flex; gap: 5px; justify-content: center;">
                <button type="button" class="btn-action btn-update" 
                        onclick="openUpdateModal('<?=$data->id_user?>', '<?=$data->username?>', '<?=$data->email?>', '<?=$data->role?>')">
                  Update
                </button>
                
                <a href="../../controller/c_pelanggan.php?aksi=hapus&id_user=<?= $data->id_user; ?>" 
                   onclick="return confirm('Anda yakin mau menghapus data ini ?')" 
                   class="btn-action btn-delete">Hapus</a>
              </div>
            </td>
          </tr>
          <?php 
            endforeach; 
          else:
          ?>
          <tr>
            <td colspan="5" style="text-align: center;">Tidak ada data pengguna</td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table> 
    </div>
  </div>

  
  <div id="tambahModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Tambah User Baru</h3>
        <span class="close-btn" onclick="closeTambahModal()">&times;</span>
      </div>
      <form action="../../controller/c_pelanggan.php?aksi=tambah" method="POST">
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="Masukkan username" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Masukkan email" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-action btn-batal" onclick="closeTambahModal()">Batal</button>
          <button type="submit" class="btn-action btn-simpan" style="background-color: #2ecc71;">Simpan User</button>
        </div>
      </form>
    </div>
  </div>

  
  <div id="updateModal" class="modal-overlay">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Update Data User</h3>
        <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
      </div>
      <form action="../../controller/c_pelanggan.php?aksi=edit" method="POST">
        <input type="hidden" name="id_user" id="modal_id_user">
        
        <div class="form-group">
          <label>Username</label>
          <input type="text" name="nama" id="modal_username" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" id="modal_email" required>
        </div>
        <div class="form-group">
          <label>Role</label>
          <select name="role" id="modal_role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
            <option value="petugas">Petugas</option>
          </select>
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

    function openUpdateModal(id, username, email, role) {
      document.getElementById('updateModal').style.display = 'flex';
      document.getElementById('modal_id_user').value = id;
      document.getElementById('modal_username').value = username;
      document.getElementById('modal_email').value = email;
      document.getElementById('modal_role').value = role;
    }
    function closeUpdateModal() {
      document.getElementById('updateModal').style.display = 'none';
    }

    window.onclick = function(event) {
      if (event.target.className === 'modal-overlay') {
        event.target.style.display = 'none';
      }
    }
  </script>

</body>
</html>
