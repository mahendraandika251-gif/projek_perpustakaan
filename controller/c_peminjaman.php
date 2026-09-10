<!-- controller untuk proses peminjaman alat -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    // session_start dijalankan untuk menyimpan data user, id dan role
    session_start();
}
include_once __DIR__ . '/../models/m_peminjaman.php';

$peminjaman = new peminjaman();

$id_session = $_SESSION['data']['id_user'] ?? null;
$role_session = $_SESSION['data']['role'] ?? 'user';

// aksi peminjaman
try { 
    if (isset($_GET['aksi'])) {
        $aksi = $_GET['aksi'];

        if ($aksi == 'peminjaman') {
            $id_user = $id_session; 
            $id_alat = $_POST['id_alat'];
            $nama_peminjam = $_POST['nama_peminjam'];
            $tgl_pinjam = $_POST['tgl_pinjam'];
            $tgl_kembali = $_POST['tgl_kembali'];
            $jumlah = $_POST['jumlah'];

            $result = $peminjaman->tambah_data_peminjam($id_user, $id_alat, $nama_peminjam, $tgl_pinjam, $tgl_kembali, $jumlah);
            if ($result) {
                echo "<script>alert('Berhasil!'); window.location='../view/view_user/daftar_peminjaman.php'</script>";
            } else {
                echo "<script>alert('Gagal tambah data!'); history.back();</script>";
            }
            exit;
        } 

        // aksi update status, apakah peminjaman disetujui atau tidak pesanan nya

        if ($aksi == 'update_status') {
    $id = $_GET['id'] ?? null; 
    $status_req = $_GET['status'] ?? null;

    if ($id && $status_req) {
        $result = $peminjaman->update_status_peminjaman($id, $status_req);
        
        if ($result) {
            
            $redirect_url = '../View/view_petugas/pengembalian.php'; 

            if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                $redirect_url = '../View/view_admin/pengembalian.php';
            }

            echo "<script>
                    alert('Data berhasil diproses!'); 
                    window.location.href = '$redirect_url';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal memproses data! Periksa relasi database.'); 
                    history.back();
                  </script>";
        }
    }
    exit;
}
    } else {
        // Ambil data semua data dan ditampilkan di view
        $data_peminjam = ($role_session == 'admin' || $role_session == 'petugas') 
                         ? $peminjaman->tampil_data_peminjam() 
                         : $peminjaman->tampil_data_peminjam($id_session);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
