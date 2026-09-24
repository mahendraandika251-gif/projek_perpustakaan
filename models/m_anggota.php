<!-- berfungsi untuk mengelola data user atau akun dalam sistem -->
<?php
include_once 'm_koneksi.php';

class m_anggota {

    private $koneksi;

    // Constructor untuk inisialisasi koneksi sekali pakai
    public function __construct() {
        $db = new koneksi();
        $this->koneksi = $db->koneksi;
    }

    // Fungsi login
    public function login($username)
    {
        $sql = "SELECT * FROM anggota WHERE username = ? OR email = ?";
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();

        $query = $stmt->get_result();

        if ($query && $query->num_rows > 0) {
            return $query->fetch_assoc();
        } else {
            return NULL;
        }
    }

    // Fungsi tampil data
    public function tampil_data()
    {
        $sql = "SELECT * FROM anggota";
        $query = mysqli_query($this->koneksi, $sql);

        $result = [];
        if ($query && mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_object($query)) {
                $result[] = $data;
            }
            return $result;
        } else {
            return false;
        }
    }

    // Fungsi edit data berdasarkan ID
    public function edit($id_anggota, $username, $email, $nisn, $role) {
        $stmt = $this->koneksi->prepare("UPDATE anggota SET username = ?, email = ?, nisn = ?, role = ? WHERE id_anggota = ?");
        $stmt->bind_param("ssssi", $username, $email, $nisn, $role, $id_anggota);
        return $stmt->execute();
    }

    // Fungsi tambah data (Otomatis HASH Password)
    public function tambah_data($username, $email, $password, $nisn, $role)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO anggota (username, email, password, nisn, role) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $nisn, $role);
        return $stmt->execute();
    }

    // Fungsi hapus data
    public function hapus_data($id)
    {
        $sql = "DELETE FROM anggota WHERE id_anggota = ?";
        
        $stmt = $this->koneksi->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>