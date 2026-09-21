<!-- berfungsi untuk mengelola data user atau akun dalam sistem -->
<?php
include_once 'm_koneksi.php';

class m_anggota {

    // Fungsi login
    function login($username)
    {
        $db = new koneksi();
        $koneksi = $db->koneksi;

        $sql = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = $koneksi->prepare($sql);
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
    function tampil_data()
    {
        $db = new koneksi();
        $koneksi = $db->koneksi;
        $sql = "SELECT * FROM user";
        $query = mysqli_query($koneksi, $sql);

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

    // Fungsi edit (Termasuk update NISN)
    function edit($id_user, $username, $email, $nisn, $role)
    {
        $db = new koneksi();
        $koneksi = $db->koneksi;
        $sql = "UPDATE user SET username = ?, email = ?, nisn = ?, role = ? WHERE id_user = ?";
        
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $nisn, $role, $id_user);
        return $stmt->execute();
    }

    // Fungsi tambah data (Termasuk simpan NISN & otomatis HASH Password)
    function tambah_data($username, $email, $password, $nisn, $role)
    {
        $db = new koneksi();
        $koneksi = $db->koneksi;

        // Keamanan: Otomatis hash password sebelum masuk ke database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (username, email, password, nisn, role) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $kelas, $hashed_password, $nisn, $role);
        return $stmt->execute();
    }

    // Fungsi hapus data
    function hapus_data($id)
    {
        $db = new koneksi();
        $koneksi = $db->koneksi;
        $sql = "DELETE FROM anggota WHERE id_anggota = ?";
        
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>