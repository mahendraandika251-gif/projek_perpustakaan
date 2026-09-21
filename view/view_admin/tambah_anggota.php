<?php
// Hubungkan ke file koneksi
require_once '../../models/m_koneksi.php';

// Pastikan variabel koneksi tersedia (mendukung $conn atau $koneksi)
$db = isset($conn) ? $conn : (isset($koneksi) ? $koneksi : null);

if (!$db) {
    die("Koneksi ke database gagal. Harap periksa file ../../models/m_koneksi.php");
}

$pesan = '';
$tipe_pesan = '';

// Proses Simpan Data Anggota
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $nisn     = trim($_POST['nisn'] ?? '');
    $kelas    = trim($_POST['kelas'] ?? '');
    $role     = trim($_POST['role'] ?? 'user');
    $password = $_POST['password'] ?? '';

    // Validasi Sederhana
    if (empty($username) || empty($password)) {
        $pesan = "Username dan Password wajib diisi!";
        $tipe_pesan = "error";
    } else {
        // Enkripsi Password (rekomendasi password_hash)
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepared statement untuk keamanan dari SQL Injection
        $stmt = $db->prepare("INSERT INTO anggota (username, email, nisn, kelas, role, password) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssss", $username, $email, $nisn, $kelas, $role, $hashed_password);
            if ($stmt->execute()) {
                $pesan = "Anggota baru berhasil ditambahkan!";
                $tipe_pesan = "sukses";
            } else {
                $pesan = "Gagal menyimpan data: " . $stmt->error;
                $tipe_pesan = "error";
            }
            $stmt->close();
        } else {
            $pesan = "Error Query Database: " . $db->error;
            $tipe_pesan = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota Baru - Perpustakaan</title>
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

    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between">
            <a href="data_anggota.php" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Data Anggota
            </a>
        </div>

        <!-- Notification Alert -->
        <?php if (!empty($pesan)): ?>
            <div class="p-4 rounded-xl border flex items-center gap-3 shadow-sm <?= $tipe_pesan === 'sukses' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-rose-50 border-rose-200 text-rose-800' ?>">
                <i class="fa-solid <?= $tipe_pesan === 'sukses' ? 'fa-circle-check text-emerald-600' : 'fa-circle-exclamation text-rose-600' ?> text-lg"></i>
                <div class="text-sm font-medium"><?= htmlspecialchars($pesan) ?></div>
            </div>
        <?php endif; ?>

        <!-- Form Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Card Header -->
            <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Form Tambah Anggota</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Isi detail formulir di bawah ini untuk mendaftarkan anggota baru.</p>
                </div>
            </div>

            <!-- Form Body -->
            <form action="" method="POST" class="p-6 sm:p-8 space-y-6">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    
                    <!-- Username -->
                    <div class="space-y-2">
                        <label for="username" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="username" name="username" required placeholder="Contoh: alex_pratama"
                                   class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-key absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="password" id="password" name="password" required placeholder="••••••••"
                                   class="w-full pl-10 pr-10 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="email" id="email" name="email" placeholder="nama@email.com"
                                   class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                        </div>
                    </div>

                    <!-- NISN -->
                    <div class="space-y-2">
                        <label for="nisn" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            NISN
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-id-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="nisn" name="nisn" placeholder="00xxxxxxxx"
                                   class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div class="space-y-2">
                        <label for="kelas" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Kelas / Rombel
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-graduation-cap absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="kelas" name="kelas" placeholder="Contoh: XII RPL 1"
                                   class="w-full pl-10 pr-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="space-y-2">
                        <label for="role" class="block text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Role Akses <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fa-solid fa-user-shield absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <select id="role" name="role" required
                                    class="w-full pl-10 pr-8 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm appearance-none">
                                <option value="user">User (Siswa)</option>
                                <option value="admin">Admin Perpustakaan</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                </div>

                <!-- Form Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="reset" class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">
                        Reset
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-6 py-2.5 rounded-xl text-sm transition-all shadow-sm hover:shadow shadow-blue-500/20">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan Data</span>
                    </button>
                </div>

            </form>

        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>