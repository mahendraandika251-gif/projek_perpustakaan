<?php
// Panggil file model anggota
require_once '../../models/m_anggota.php';

$anggota = new m_anggota();
$message = '';
$error = '';

// 1. Ambil data anggota berdasarkan ID dari URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    
    // Memanggil method untuk mengambil data berdasarkan ID
    // Pastikan di m_anggota.php terdapat method getById() atau sejenisnya
    $db = new koneksi();
    $koneksi = $db->koneksi;
    
    $stmt = $koneksi->prepare("SELECT * FROM anggota WHERE id_anggota = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        header("Location: data_anggota.php");
        exit();
    }
} else {
    header("Location: data_anggota.php");
    exit();
}

// 2. Proses saat Form di-submit (tombol Simpan diklik)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan key POST sesuai dengan name pada tag <input>
    $id_anggota = $_POST['id_anggota']; 
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $nisn       = trim($_POST['nisn']);
    $role       = $_POST['role'];

    if (!empty($username) && !empty($email)) {
        // Panggil fungsi edit() dari model m_anggota.php
        $update = $anggota->edit($id_anggota, $username, $email, $nisn, $role);

        if ($update) {
            echo "<script>
                    alert('Data anggota berhasil diperbarui!');
                    window.location.href = 'data_anggota.php';
                  </script>";
            exit();
        } else {
            $error = "Gagal memperbarui data. Silakan coba lagi.";
        }
    } else {
        $error = "Username dan Email tidak boleh kosong!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota - Perpustakaan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen p-4 sm:p-6 lg:p-8">

    <div class="max-w-2xl mx-auto space-y-6">
        
        <!-- Header Page -->
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Edit Data Anggota</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Ubah informasi anggota terdaftar di sistem.</p>
                </div>
            </div>
            <a href="data_anggota.php" class="p-2.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </a>
        </div>

        <!-- Alert Error jika ada -->
        <?php if (!empty($error)): ?>
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Edit Data -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200/80">
            <form action="" method="POST" class="space-y-5">
                
                <!-- PERBAIKAN: name="id_anggota" disesuaikan dengan $_POST['id_anggota'] -->
                <input type="hidden" name="id_anggota" value="<?= htmlspecialchars($data['id_anggota'] ?? $data['id_user'] ?? '') ?>">

                <!-- Field Username -->
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="username" name="username" required
                               value="<?= htmlspecialchars($data['username'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Field Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Field NISN -->
                <div>
                    <label for="nisn" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        NISN
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="nisn" name="nisn"
                               value="<?= htmlspecialchars($data['nisn'] ?? '') ?>"
                               placeholder="Contoh: 0012345678"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Field Role -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                        Role / Hak Akses <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-user-shield absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <select id="role" name="role" required
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition appearance-none">
                            <option value="user" <?= (isset($data['role']) && strtolower($data['role']) === 'user') ? 'selected' : '' ?>>User / Siswa</option>
                            <option value="admin" <?= (isset($data['role']) && strtolower($data['role']) === 'admin') ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="data_anggota.php" 
                       class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-medium text-sm hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition shadow-sm hover:shadow shadow-blue-500/20">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>
        </div>

    </div>

</body>
</html>