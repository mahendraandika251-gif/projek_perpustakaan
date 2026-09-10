<!-- berfungsi untuk memproses pengembalian alat dan menyimpan ke dalam riwayat -->
<?php 
include_once 'm_koneksi.php';

class pengembalian {
    private $conn;

    public function __construct() {
        $db = new koneksi();
        $this->conn = $db->koneksi;
    }

    // Fungsi untuk memindahkan data ke riwayat
    function A($id, $status_req) {
        $sql_cari = "SELECT id_user, kode_pinjam, nama_peminjam, tanggal_meminjam FROM peminjaman_alat WHERE kode_pinjam = ?";
        $stmt = mysqli_prepare($this->conn, $sql_cari);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $data = mysqli_stmt_get_result($stmt)->fetch_assoc();

        if ($data) {
            $tgl_kembali = date('Y-m-d');
            
            $sql_ins = "INSERT INTO riwayat_peminjaman (id_user, id_sewa, nama_peminjam, tanggal_peminjaman, tanggal_pengembalian) VALUES (?, ?, ?, ?, ?)";
            $stmt_ins = mysqli_prepare($this->conn, $sql_ins);
            mysqli_stmt_bind_param($stmt_ins, "iisss", $data['id_user'], $data['kode_pinjam'], $data['nama_peminjam'], $data['tanggal_meminjam'], $tgl_kembali);
            
            if (mysqli_stmt_execute($stmt_ins)) {
                mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS=0");
                mysqli_query($this->conn, "DELETE FROM pengembalian WHERE kode_pinjam = '$id'");
                $del = mysqli_query($this->conn, "DELETE FROM peminjaman_alat WHERE kode_pinjam = '$id'");
                mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS=1");
                return $del;
            }
        }
        return false;
    }

    // Menampilkan data sebagai OBJECT agar cocok dengan View
    function tampil_riwayat($id_user) {
        $sql = "SELECT * FROM riwayat_peminjaman WHERE id_user = ? ORDER BY tanggal_pengembalian DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $data = [];
        // Menggunakan fetch_object agar bisa dipanggil dengan $row->nama_kolom
        while ($row = mysqli_fetch_object($result)) {
            $data[] = $row;
        }
        return $data;
    }
}