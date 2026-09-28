<?php
require_once '../../models/m_buku.php';

$id_buku = $_GET['id'] ?? 0;
$model_buku = new m_buku();
$data_buku = $model_buku->get_buku_by_id((int)$id_buku);

if (!$data_buku) {
    echo "<script>alert('Data buku tidak ditemukan!'); window.location.href='dashboard_buku.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Buku - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .card-header { background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: #ffffff; border-top-left-radius: 12px !important; border-top-right-radius: 12px !important; padding: 18px 25px; }
        .form-label { font-weight: 600; color: #495057; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 14px; border: 1px solid #ced4da; }
        .img-preview { max-height: 140px; border-radius: 8px; border: 2px dashed #dee2e6; object-fit: cover; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fs-5"><i class="fa-solid fa-pen-to-square me-2"></i> Form Edit Data Buku</h5>
                    <span class="badge bg-light text-primary">ID: #<?= htmlspecialchars($data_buku['id_buku']); ?></span>
                </div>
                <div class="card-body p-4">

                    <!-- Action diarahkan ke Controller c_proses_buku.php -->
                    <form action="../../controller/c_proses_buku.php" method="POST" enctype="multipart/form-data">
                        
                        <!-- Input Hidden untuk Action, ID Buku & Foto Lama -->
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id_buku" value="<?= htmlspecialchars($data_buku['id_buku']); ?>">
                        <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data_buku['foto'] ?? 'default.jpg'); ?>">

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="judul" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($data_buku['judul']); ?>" required maxlength="150">
                            </div>

                            <div class="col-md-6">
                                <label for="penulis" class="form-label">Penulis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($data_buku['penulis']); ?>" required maxlength="100">
                            </div>

                            <div class="col-md-6">
                                <label for="penerbit" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= htmlspecialchars($data_buku['penerbit']); ?>" required maxlength="100">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="tahun_terbit" class="form-label">Tahun Terbit <span class="text-danger">*</span></label>
                                <select class="form-select" id="tahun_terbit" name="tahun_terbit" required>
                                    <option value="" disabled>-- Pilih Tahun --</option>
                                    <?php 
                                    $tahun_sekarang = date('Y');
                                    for ($i = $tahun_sekarang; $i >= 1950; $i--) {
                                        $selected = ($data_buku['tahun_terbit'] == $i) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <?php
                                    $list_kategori = ['Fiksi', 'Non-Fiksi', 'Edukasi', 'Teknologi', 'Sains', 'Sejarah', 'Komik', 'Lainnya'];
                                    foreach ($list_kategori as $kat) {
                                        $selected = ($data_buku['kategori'] == $kat) ? 'selected' : '';
                                        echo "<option value='$kat' $selected>$kat</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="stok" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="stok" name="stok" min="0" value="<?= (int)$data_buku['stok']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="foto" class="form-label">Foto / Sampul Buku (Opsional)</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewImage(event)">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto sampul.</div>
                            
                            <div class="mt-3 text-center">
                                <?php 
                                    $foto_curr = $data_buku['foto'] ?? '';
                                    if (empty($foto_curr)) {
                                        $src_curr = 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60';
                                    } elseif (filter_var($foto_curr, FILTER_VALIDATE_URL)) {
                                        $src_curr = htmlspecialchars($foto_curr);
                                    } else {
                                        $src_curr = '../../asset/img/' . htmlspecialchars($foto_curr);
                                    }
                                ?>
                                <img id="preview" src="<?= $src_curr; ?>" class="img-preview shadow-sm" alt="Sampul Buku">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="dashboard_buku.php" class="btn btn-light text-secondary px-4">
                                <i class="fa-solid fa-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
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
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById('preview');
        reader.onload = function() {
            if (reader.readyState === 2) {
                imageField.src = reader.result;
            }
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
</body>
</html>