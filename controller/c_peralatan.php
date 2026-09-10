<!-- proses untuk menambahkan, edit atau hapus untuk admin -->

<?php
include_once __DIR__ . '/../models/m_peralatan.php';
$alat = new alat();

try { 
    if (isset($_GET['aksi'])) {
        $aksi = $_GET['aksi'];

        if ($aksi == 'tambah') {
            $nama_alat   = $_POST['nama_alat'];
            $jumlah      = $_POST['jumlah']; 
            $id_kategori = $_POST['id_kategori']; 
            $foto_alat   = $_POST['foto_alat'];
            $harga       = $_POST['harga'];

            $result = $alat->tambah_data_alat($nama_alat, $jumlah, $foto_alat, $id_kategori, $harga);
            $msg = $result ? "Data Berhasil Ditambahkan" : "Data Gagal Ditambahkan";
            echo "<script>alert('$msg');window.location='../view/view_admin/data_alat.php'</script>";
        }
        
        elseif ($aksi == 'edit') {
            $id_alat     = $_POST['id_alat'];
            $nama_alat   = $_POST['nama_alat'];
            $jumlah      = $_POST['jumlah'];
            $id_kategori = $_POST['id_kategori']; 
            $foto_alat   = $_POST['foto_alat'];
            $harga       = $_POST['harga'];

            $result = $alat->edit_alat($id_alat, $nama_alat, $jumlah, $foto_alat, $id_kategori, $harga);
            $msg = $result ? "Data Berhasil Diubah" : "Data Gagal Diubah";
            echo "<script>alert('$msg');window.location='../view/view_admin/data_alat.php'</script>";
        }
        
        elseif ($aksi == 'hapus') {
            $id_alat = $_GET['id_alat'];
            $result = $alat->hapus_data_alat($id_alat); 
            header("Location: ../view/view_admin/data_alat.php");
        }
    }
    
    // setelah selesai data akan ditampilkan di view
    $data_alat = $alat->tampil_data_alat();
    $data_kategori = $alat->tampil_kategori();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}