<!-- controller koneksi ke dabase -->
<?php
// untuk memanggil m_koneksi dan m_data
require_once __DIR__ . '/../models/m_koneksi.php';
require_once __DIR__ . '/../models/m_data.p.php';

// koneksi ke database mysql
$database = new mysqli("localhost", "root", "", "peminjaman_alat");

if ($database->connect_error) {
    die("Koneksi gagal: " . $database->connect_error);
}

$model = new ModelPengembalian($database);
// getMenunggu -> untuk mengambil data pengembalian yang masih menunggu proses
// getAllRiwayat -> untuk mengambil seluruh data riwayat pengembalian
// kemudian disimpan ke dalam variabel $data_menunggu dan $data_riwayat_all, yang nantinya akan ditampilkan di halaman view.
$data_menunggu = $model->getMenunggu();
$data_riwayat_all = $model->getAllRiwayat();
?>