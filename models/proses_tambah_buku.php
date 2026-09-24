<?php
// Mengambil skrip koneksi database dari folder models
require_once "../models/m_koneksi.php";

// Memeriksa apakah form telah dikirimkan via metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
    // Mengamankan dan mengambil data inputan form
    $judul        = mysqli_real_escape_string($koneksi, trim($_POST['judul']));
    $penulis      = mysqli_real_escape_string($koneksi, trim($_POST['penulis']));
    $penerbit     = mysqli_real_escape_string($koneksi, trim($_POST['penerbit']));
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $kategori     = mysqli_real_escape_string($koneksi, trim($_POST['kategori']));
    $srok         = (int)$_POST['srok']; // Kolom srok (stok buku) di database

    // Pengolahan upload foto sampul buku
    $foto_name    = $_FILES['foto']['name'];
    $foto_tmp     = $_FILES['foto']['tmp_name'];
    $foto_size    = $_FILES['foto']['size'];
    $foto_error   = $_FILES['foto']['error'];

    // Validasi file foto
    if ($foto_error !== UPLOAD_ERR_OK) {
        echo "<script>
                alert('Gagal mengunggah foto. Silakan coba lagi!');
                window.history.back();
              </script>";
        exit;
    }

    // Maksimal ukuran foto 2MB (2 * 1024 * 1024 bytes)
    if ($foto_size > 2097152) {
        echo "<script>
                alert('Ukuran foto terlalu besar! Maksimal 2MB.');
                window.history.back();
              </script>";
        exit;
    }

    // Cek ekstensi file yang diizinkan
    $ext_allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext_file    = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

    if (!in_array($ext_file, $ext_allowed)) {
        echo "<script>
                alert('Format file foto tidak valid! Gunakan JPG, PNG, atau WEBP.');
                window.history.back();
              </script>";
        exit;
    }

    // Generate nama file unik agar tidak bentrok
    $new_filename = time() . '_' . uniqid() . '.' . $ext_file;
    $target_dir   = "../assets/img/" . $new_filename;

    // Pindahkan file foto ke direktori tujuan
    if (move_uploaded_file($foto_tmp, $target_dir)) {
        
        // Query simpan data ke tabel 'buku' di database 'perpustakaan'
        $query = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, kategori, srok, foto) 
                  VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$kategori', '$srok', '$new_filename')";

        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<script>
                    alert('Buku berhasil disimpan ke database!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menyimpan data ke database: " . mysqli_error($koneksi) . "');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Gagal memindahkan file foto ke folder server!');
                window.history.back();
              </script>";
    }

} else {
    // Jika diakses secara langsung tanpa submit form
    header("Location: tambah_buku.php");
    exit;
}
?>