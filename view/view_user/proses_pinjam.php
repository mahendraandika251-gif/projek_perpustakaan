<?php
// Memulai sesi untuk mengambil data login user (id_anggota)
session_start();
require_once '../../models/m_koneksi.php';

$db = new Koneksi();
$koneksi =$db->koneksi;

// Memeriksa apakah form dikirimkan melalui tombol dengan nama 'simpan_pinjam'
if (isset($_POST['simpan_pinjam'])) {
    
    // Validasi apakah user sudah login atau belum menggunakan session data login
    if (!isset($_SESSION['data']['id_anggota'])) {
        echo "<script>
                alert('Silakan login terlebih dahulu untuk melakukan peminjaman!');
                window.location.href='../index.php';
              </script>";
        exit;
    }

    // Mengambil id_anggota secara dinamis dari session user yang sedang login
    $id_anggota      =$_SESSION['data']['id_anggota'];
    $id_buku         = intval($_POST['id_buku']);
    $tanggal_pinjam  = mysqli_real_escape_string($koneksi, $_POST['tanggal_pinjam']);$tanggal_kembali = mysqli_real_escape_string($koneksi,$_POST['tanggal_kembali']);
    $jumlah          = intval($_POST['jumlah']);
    
    // Status awal diatur menjadi menunggu persetujuan admin (stok belum dikurangi)
    $status          = 'menunggu_persetujuan_pinjam'; 

    // Validasi sederhana tanggal
    if ($tanggal_kembali <$tanggal_pinjam) {
        echo "<script>
                alert('Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');
                window.history.back();
              </script>";
        exit;
    }

    // 1. Cek stok buku saat ini di database
    $query_stok =$koneksi->query("SELECT stok, judul FROM buku WHERE id_buku = '$id_buku'");
    $buku       =$query_stok->fetch_assoc();

    if (!$buku) {
        echo "<script>
                alert('Data buku tidak ditemukan!');
                window.location.href='v_Dashboard_user.php';
              </script>";
        exit;
    }

    // Validasi apakah stok mencukupi
    if ($buku['stok'] <$jumlah) {
        echo "<script>
                alert('Mohon maaf, stok buku \"{$buku['judul']}\" tidak mencukupi. Sisa stok: {$buku['stok']}');
                window.location.href='v_Dashboard_user.php';
              </script>";
        exit;
    }

    // 2. Insert data peminjaman ke dalam tabel `transaksi` dengan status menunggu persetujuan
    $sql_transaksi = "INSERT INTO transaksi (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status, jumlah) 
                      VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', '$status', '$jumlah')";

    if ($koneksi->query($sql_transaksi)) {
        
        // Catatan: Stok buku sengaja belum dikurangi di sini. 
        // Stok baru akan dikurangi nanti setelah admin mengklik "Setujui Peminjaman".

        echo "<script>
                alert('Pengajuan peminjaman berhasil dikirim! Menunggu persetujuan Admin.');
                window.location.href='history_peminjaman.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal melakukan peminjaman: " . $koneksi->error . "');
                window.location.href='v_Dashboard_user.php';
              </script>";
    }

} else {
    // Jika file diakses langsung tanpa lewat form submit
    header("Location: v_Dashboard_user.php");
    exit;
}
?>