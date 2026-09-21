<!-- controller untuk proses register dan tambah data pengguna -->
<?php
include_once __DIR__ . '/../models/m_pelanggan.php';

$pelanggan = new m_pelanggan();

try {
    if (!empty($_GET['aksi'])) {

        //  aksi register dan tambah data pengguna 
        if ($_GET['aksi'] == 'register' || $_GET['aksi'] == 'tambah') {
            $username    = $_POST['username'];
            $email       = $_POST['email'];
            $password    = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role        = $_POST['role'];

            $result = $pelanggan->tambah_data($username, $email, $password, $role);
            if ($_GET['aksi'] == 'register') {
                $success_path = '../index.php';
                $fail_path    = '../view/form_pendaftaran.php';
            } else {
                $success_path = '../view/view_admin/data_user.php';
                $fail_path    = '../view/view_admin/data_user.php';
            }

            if ($result) {
                echo "<script>
                        alert('Data Berhasil Ditambahkan');
                        window.location='$success_path';
                      </script>";
            } else {
                echo "<script>
                        alert('Data Gagal Ditambahkan!');
                        window.location='$fail_path';
                      </script>";
            }
        }

        // aksin hapus data
        elseif ($_GET['aksi'] == 'hapus') {
            $id = $_GET['id_user'];
            $result = $pelanggan->hapus_data($id);

            if ($result) {
                echo "<script>
                        alert('Data Berhasil Dihapus');
                        window.location='../view/view_admin/data_user.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Data Gagal Dihapus');
                        window.location='../view/view_admin/data_user.php';
                      </script>";
            }
        }

        //  aksi edit data 
        elseif ($_GET['aksi'] == 'edit') {
            $id_user  = $_POST['id_user'];
            $username = $_POST['nama'];
            $email    = $_POST['email'];
            $role     = $_POST['role'];

            $result = $pelanggan->edit($id_user, $username, $email, $role);

            if ($result) {
                echo "<script>
                        alert('Data Berhasil Diubah'); 
                        window.location='../view/view_admin/data_user.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Data Gagal Diubah'); 
                        window.location='../view/view_admin/data_user.php';
                      </script>";
            }
        }

    } else {
        $data_pelanggan = $pelanggan->tampil_data();
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
