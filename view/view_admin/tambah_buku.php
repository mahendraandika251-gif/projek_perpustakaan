<?php
// 1. Mengambil file koneksi database
include_once '../../models/m_koneksi.php';

// Menangkap objek koneksi asli dari m_koneksi.php
$koneksi_raw = isset($koneksi) ? $koneksi : (isset($conn) ? $conn : (isset($db) ? $db : null));
$koneksi_db  = $koneksi_raw;

// Jika $koneksi_db berupa Class Object 'Koneksi' (bukan langsung mysqli), ekstensi/unwrap koneksi mysqli dari dalam propertinya
if (is_object($koneksi_db) && !($koneksi_db instanceof mysqli)) {
    if (isset($koneksi_db->koneksi) && ($koneksi_db->koneksi instanceof mysqli)) {
        $koneksi_db = $koneksi_db->koneksi;
    } elseif (isset($koneksi_db->conn) && ($koneksi_db->conn instanceof mysqli)) {
        $koneksi_db = $koneksi_db->conn;
    } elseif (isset($koneksi_db->link) && ($koneksi_db->link instanceof mysqli)) {
        $koneksi_db = $koneksi_db->link;
    } elseif (method_exists($koneksi_db, 'getKoneksi') && ($koneksi_db->getKoneksi() instanceof mysqli)) {
        $koneksi_db = $koneksi_db->getKoneksi();
    } elseif (method_exists($koneksi_db, 'getConnection') && ($koneksi_db->getConnection() instanceof mysqli)) {
        $koneksi_db = $koneksi_db->getConnection();
    }
}

// Helper sanitasi string aman
function aman_string($conn, $data) {
    $data = trim($data);
    if ($conn instanceof mysqli) {
        return mysqli_real_escape_string($conn, $data);
    } elseif (is_object($conn) && method_exists($conn, 'real_escape_string')) {
        return $conn->real_escape_string($data);
    } elseif (is_object($conn) && method_exists($conn, 'escape')) {
        return $conn->escape($data);
    }
    return addslashes($data);
}

// Helper eksekusi query aman
function eksekusi_query($raw_obj, $conn_obj, $sql) {
    if ($conn_obj instanceof mysqli) {
        return mysqli_query($conn_obj, $sql);
    } elseif (is_object($raw_obj) && method_exists($raw_obj, 'query')) {
        return $raw_obj->query($sql);
    } elseif (is_object($conn_obj) && method_exists($conn_obj, 'query')) {
        return $conn_obj->query($sql);
    }
    return false;
}

// Inisialisasi variabel pesan notifikasi
$pesan = '';
$status_pesan = ''; // 'success' atau 'danger'

// 2. Memproses form ketika tombol Tambah Data diklik
if (isset($_POST['tambah_buku'])) {
    // Ambil data dari form dan lakukan sanitasi string yang aman
    $judul        = aman_string($koneksi_db, $_POST['judul'] ?? '');
    $penulis      = aman_string($koneksi_db, $_POST['penulis'] ?? '');
    $penerbit     = aman_string($koneksi_db, $_POST['penerbit'] ?? '');
    $tahun_terbit = aman_string($koneksi_db, $_POST['tahun_terbit'] ?? '');
    $kategori     = aman_string($koneksi_db, $_POST['kategori'] ?? '');
    $stok         = (int)($_POST['stok'] ?? 0);

    // Penanganan unggah foto/cover buku
    $nama_foto     = $_FILES['foto']['name'] ?? '';
    $ukuran_foto   = $_FILES['foto']['size'] ?? 0;
    $tmp_foto      = $_FILES['foto']['tmp_name'] ?? '';
    $error_foto    = $_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE;

    $nama_foto_baru = 'default.jpg'; // Gambar default jika tidak ada file diunggah

    if ($error_foto === 0) {
        $ekstensi_diizinkan = array('png', 'jpg', 'jpeg', 'webp');
        $x = explode('.', $nama_foto);
        $ekstensi = strtolower(end($x));

        // Cek ekstensi file
        if (in_array($ekstensi, $ekstensi_diizinkan) === true) {
            // Cek ukuran file (maksimal 2MB)
            if ($ukuran_foto < 2048000) {
                
                // Set lokasi folder tujuan ke asset/img
                $folder_tujuan = '../../asset/img/';

                // Buat folder asset/img otomatis jika belum ada
                if (!is_dir($folder_tujuan)) {
                    mkdir($folder_tujuan, 0777, true);
                }

                // Generasi nama file unik agar tidak bentrok
                $nama_foto_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9\.]/", "_", $nama_foto);
                $target_dir     = $folder_tujuan . $nama_foto_baru;

                // Pindahkan file foto ke folder asset/img
                move_uploaded_file($tmp_foto, $target_dir);
            } else {
                $pesan = "Ukuran file foto terlalu besar! Maksimal 2MB.";
                $status_pesan = "danger";
            }
        } else {
            $pesan = "Ekstensi file foto tidak diizinkan! Gunakan JPG, PNG, atau WEBP.";
            $status_pesan = "danger";
        }
    }

    // Jalankan query insert jika tidak ada error validasi foto
    if (empty($pesan)) {
        $query = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, kategori, stok, foto) 
                  VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$kategori', '$stok', '$nama_foto_baru')";

        $simpan = eksekusi_query($koneksi_raw, $koneksi_db, $query);

        if ($simpan) {
            $pesan = "Data buku <strong>" . htmlspecialchars($_POST['judul']) . "</strong> berhasil ditambahkan ke database!";
            $status_pesan = "success";
        } else {
            $err_detil = ($koneksi_db instanceof mysqli) ? mysqli_error($koneksi_db) : "Gagal menyimpan data.";
            $pesan = "Gagal menyimpan data ke database: " . $err_detil;
            $status_pesan = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Buku - Sistem Perpustakaan</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #ffffff;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            padding: 18px 25px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
        }
        .img-preview {
            max-height: 140px;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
            object-fit: cover;
            display: none;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <!-- Menampilkan Pesan Notifikasi Sukses / Gagal -->
            <?php if (!empty($pesan)): ?>
                <div class="alert alert-<?= $status_pesan; ?> alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="fa-solid <?= $status_pesan === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?> me-2 fs-4"></i>
                    <div><?= $pesan; ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fs-5"><i class="fa-solid fa-book me-2"></i> Form Tambah Data Buku</h5>
                    <span class="badge bg-light text-primary">Tabel: buku</span>
                </div>
                <div class="card-body p-4">
                    <!-- Form Submit ke Halaman yang Sama (action="") -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul buku lengkap" required maxlength="150">
                            </div>

                            <div class="col-md-6">
                                <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penulis" name="penulis" placeholder="Nama penulis / pengarang" required maxlength="100">
                            </div>

                            <div class="col-md-6">
                                <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penerbit" name="penerbit" placeholder="Nama penerbit" required maxlength="100">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                                <select class="form-select" id="tahun_terbit" name="tahun_terbit" required>
                                    <option value="" selected disabled>-- Pilih Tahun --</option>
                                    <?php 
                                    $tahun_sekarang = date('Y');
                                    for ($i = $tahun_sekarang; $i >= 1950; $i--) {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    <option value="Fiksi">Fiksi</option>
                                    <option value="Non-Fiksi">Non-Fiksi</option>
                                    <option value="Edukasi">Edukasi</option>
                                    <option value="Teknologi">Teknologi</option>
                                    <option value="Sains">Sains</option>
                                    <option value="Sejarah">Sejarah</option>
                                    <option value="Komik">Komik</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok" name="stok" min="0" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto / Sampul Buku</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                            <div class="form-text">Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2MB.</div>
                            
                            <!-- Pratinjau Gambar -->
                            <div class="mt-3 text-center">
                                <img id="preview" class="img-preview shadow-sm" alt="Pratinjau Sampul Buku">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="reset" class="btn btn-light text-secondary px-4" onclick="hidePreview()">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset
                            </button>
                            <button type="submit" name="tambah_buku" class="btn btn-primary px-4">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Data Buku
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>
<script>
    // Fungsi untuk menampilkan preview foto yang dipilih
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById('preview');

        reader.onload = function() {
            if (reader.readyState === 2) {
                imageField.src = reader.result;
                imageField.style.display = 'inline-block';
            }
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function hidePreview() {
        const imageField = document.getElementById('preview');
        imageField.style.display = 'none';
        imageField.src = '';
    }
</script>
</body>
</html>