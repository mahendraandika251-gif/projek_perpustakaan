<?php
// Memanggil file koneksi database
require_once '../models/m_koneksi.php';

// Membuat instance dari class Koneksi
$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Cek apakah parameter ID transaksi tersedia di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // 1. Ambil data transaksi untuk mendapatkan id_buku dan jumlah pinjam
    $query_cek = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
    $result_cek = $koneksi->query($query_cek);
    
    if ($result_cek && $result_cek->num_rows > 0) {
        $data = $result_cek->fetch_assoc();
        
        $id_buku = $data['id_buku'];
        $jumlah  = $data['jumlah'];
        $tanggal_kembali = date('Y-m-d'); // Tanggal hari ini
        
        // 2. Update status transaksi menjadi 'dikembalikan' (huruf kecil sesuai ENUM database) dan isi tanggal_kembali
        $sql_update = "UPDATE transaksi SET tanggal_kembali = '$tanggal_kembali', status = 'dikembalikan' WHERE id_transaksi = '$id_transaksi'";
        $update = $koneksi->query($sql_update);
        
        if ($update) {
            // 3. Kembalikan/tambahkan stok buku ke tabel buku sejumlah yang dipinjam
            $sql_update_stok = "UPDATE buku SET stok = stok + $jumlah WHERE id_buku = '$id_buku'";
            $koneksi->query($sql_update_stok);
            
            // Redirect kembali ke halaman transaksi admin dengan pesan sukses
            echo "<script>
                    alert('Pengembalian buku berhasil disetujui!');
                    window.location.href = '../view/view_admin/transaksi.php';
                  </script>";
            exit();
        } else {
            echo "<script>
                    alert('Gagal memperbarui database: " . $koneksi->error . "');
                    window.location.href = '../view/view_admin/transaksi.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('Data transaksi tidak ditemukan!');
                window.location.href = '../view/view_admin/transaksi.php';
              </script>";
        exit();
    }
} else {
    header("Location: ../view/view_admin/transaksi.php");
    exit();
}
?>