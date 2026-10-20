<?php
// Memanggil file koneksi database
require_once '../models/m_koneksi.php';

$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Cek apakah parameter ID transaksi tersedia di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_transaksi = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data transaksi
    $query_cek = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
    $result_cek = $koneksi->query($query_cek);
    
    if ($result_cek && $result_cek->num_rows > 0) {
        $data = $result_cek->fetch_assoc();
        
        // Pastikan statusnya benar-benar sedang menunggu persetujuan pinjam
        if ($data['status'] == 'menunggu_persetujuan_pinjam') {
            $id_buku = $data['id_buku'];
            $jumlah  = $data['jumlah'];
            
            // 1. Ubah status transaksi menjadi 'dipinjam'
            $sql_update = "UPDATE transaksi SET status = 'dipinjam' WHERE id_transaksi = '$id_transaksi'";
            $update = $koneksi->query($sql_update);
            
            if ($update) {
                // 2. Kurangi stok buku di tabel buku sekarang setelah disetujui admin
                $sql_update_stok = "UPDATE buku SET stok = stok - $jumlah WHERE id_buku = '$id_buku'";
                $koneksi->query($sql_update_stok);
                
                echo "<script>
                        alert('Peminjaman buku berhasil disetujui!');
                        window.location.href = '../view/view_admin/transaksi.php';
                      </script>";
                exit();
            } else {
                echo "<script>
                        alert('Gagal menyetujui peminjaman: " . $koneksi->error . "');
                        window.location.href = '../view/view_admin/transaksi.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Status transaksi tidak valid untuk disetujui!');
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