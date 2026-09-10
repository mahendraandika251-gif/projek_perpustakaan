<!-- controller untuk proses tambah dan hapus kategori alat -->
<?php
include_once __DIR__ . '/../models/m_kategori.php';


// Object ini digunakan untuk mengakses method seperti tambah dan hapus.
$kategori = new kategori(); 

try { 
    // program mengecek apakah ada parameter aksi dari URL
    if (isset($_GET['aksi'])) {
        $aksi = $_GET['aksi'];

// Jika aksinya adalah tambah maka program akan memanggil fungsi tambah_kategori
        if ($aksi == 'tambah') {
            $nama_kategori = $_POST['nama_kategori'];
            $result = $kategori->tambah_kategori($nama_kategori);

            if ($result) {
                echo "<script>alert('Kategori Berhasil Ditambahkan'); window.location='../view/view_admin/kategori.php';</script>";
            } else {
                echo "<script>alert('Gagal Menambahkan Kategori'); window.location='../view/view_admin/kategori.php';</script>";
            }
        } 
        
        // Jika aksinya adalah hapus maka program akan memanggil hapus_kategori
        elseif ($aksi == 'hapus') {
            $id = $_GET['id_kategori'];
            $kategori->hapus_kategori($id);
            header("Location: ../view/view_admin/kategori.php");
            exit;
        }
    }

    // data yang sudah diproses akan tampil di view
    $data_kategori = $kategori->tampil_data();

    // berfungsi untuk menangani error, jika terjadi kesalahan akan menampilkan pesan error
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>