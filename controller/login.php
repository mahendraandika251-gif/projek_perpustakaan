<!-- proses login pengguna -->
<?php
include_once '../models/m_pelanggan.php'; 
session_start();
$_SESSION['data'] = $data_pelanggan;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
  
    $user = new m_pelanggan(); 
    
    $input_user = $_POST['username'];
    $input_password = $_POST['password']; 

    $result = $user->login($input_user); 

    if ($result && password_verify($input_password, $result['password'])) {
        $_SESSION['data'] = $result;
        
        if ($result['role'] === 'admin') {
            header("location: ../view/v_Dashboard_admin.php");
            exit;
        } elseif ($result['role'] === 'user') {
            header("location: ../view/v_Dashboard_user.php");
            exit;
        } elseif ($result['role'] == 'petugas') {
          header("location: ../view/v_Dashboard_petugas.php");
            exit;
        }else {
            echo "<script>alert('Role tidak terdefinisi.'); window.location='../index.php'</script>";
        }
    } else {
        echo "<script>alert('Username atau Password Salah'); window.location='../index.php'</script>";
    }
} else {
    header("location: ../index.php");
    exit;
}
?>
