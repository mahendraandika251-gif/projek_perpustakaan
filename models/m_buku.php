<?php
include_once 'm_koneksi.php';

class m_buku {

    private $koneksi;

    public function __construct() {
        $db = new koneksi();
        $this->koneksi = $db->koneksi;
    }

    // Tampil semua data buku / Pencarian & Filter
    public function tampil_data($search = '', $kategori = '') {
        $where_clauses = [];
        
        if (!empty($search)) {
            $search_clean = mysqli_real_escape_string($this->koneksi, $search);
            $where_clauses[] = "(judul LIKE '%$search_clean%' OR penulis LIKE '%$search_clean%' OR penerbit LIKE '%$search_clean%')";
        }
        
        if (!empty($kategori)) {
            $kat_clean = mysqli_real_escape_string($this->koneksi, $kategori);
            $where_clauses[] = "kategori = '$kat_clean'";
        }

        $where_sql = !empty($where_clauses) ? "WHERE " . implode(' AND ', $where_clauses) : "";
        $sql = "SELECT * FROM buku $where_sql ORDER BY id_buku DESC";
        $query = mysqli_query($this->koneksi, $sql);

        $result = [];
        if ($query && mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                $result[] = $data;
            }
        }
        return $result;
    }

    // Ambil 1 data buku berdasarkan ID
    public function get_buku_by_id($id_buku) {
        $stmt = $this->koneksi->prepare("SELECT * FROM buku WHERE id_buku = ?");
        $stmt->bind_param("i", $id_buku);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Tambah data buku baru
    public function tambah_data($judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto) {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, kategori, stok, foto) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssssis", $judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto);
        return $stmt->execute();
    }

    // Update data buku
    public function edit($id_buku, $judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto) {
        $stmt = $this->koneksi->prepare(
            "UPDATE buku SET judul = ?, penulis = ?, penerbit = ?, tahun_terbit = ?, kategori = ?, stok = ?, foto = ? WHERE id_buku = ?"
        );
        $stmt->bind_param("sssssisi", $judul, $penulis, $penerbit, $tahun_terbit, $kategori, $stok, $foto, $id_buku);
        return $stmt->execute();
    }

    // Hapus data buku
    public function hapus_data($id) {
        $stmt = $this->koneksi->prepare("DELETE FROM buku WHERE id_buku = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>