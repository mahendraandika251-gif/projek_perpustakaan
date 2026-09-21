<?php
// Hubungkan ke file koneksi
require_once '../../models/m_koneksi.php';

// Pastikan variabel koneksi tersedia (mendukung $conn atau $koneksi)
$db = isset($conn) ? $conn : (isset($koneksi) ? $koneksi : null);

if (!$db) {
    die("Koneksi ke database gagal. Harap periksa file ../../models/m_koneksi.php");
}

// Query untuk mengambil seluruh data dari tabel anggota
$sql = "SELECT id_anggota, username, email, nisn, kelas, role FROM anggota ORDER BY id_anggota ASC";
$result = $db->query($sql);

// Hitung statistik ringkas jika query berhasil
$total_anggota = 0;
$total_admin = 0;
$total_user = 0;
$rows = [];

if ($result && $result->num_rows > 0) {
    while ($r = $result->fetch_assoc()) {
        $rows[] = $r;
        $total_anggota++;
        if (strtolower($r['role']) === 'admin') {
            $total_admin++;
        } else {
            $total_user++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Anggota - Perpustakaan</title>
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

    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Data Anggota Perpustakaan</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola dan lihat seluruh informasi anggota terdaftar.</p>
                </div>
            </div>
            <div>
                <a href="tambah_anggota.php" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition-all shadow-sm hover:shadow shadow-blue-500/20">
                    <i class="fa-solid fa-user-plus text-xs"></i> 
                    <span>Tambah Anggota</span>
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Anggota</p>
                    <p class="text-xl font-bold text-slate-900"><?= $total_anggota ?></p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Admin</p>
                    <p class="text-xl font-bold text-purple-700"><?= $total_admin ?></p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Siswa / User</p>
                    <p class="text-xl font-bold text-blue-700"><?= $total_user ?></p>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            
            <!-- Toolbar: Live Search -->
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="relative w-full sm:w-96">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Cari username, email, NISN, atau kelas..." 
                           class="w-full pl-10 pr-4 py-2 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-300 bg-white transition shadow-sm">
                </div>
                <div class="text-xs text-slate-500 font-medium">
                    Menampilkan <span id="visibleCount" class="font-bold text-slate-800"><?= count($rows) ?></span> dari <?= count($rows) ?> anggota
                </div>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="anggotaTable">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-4 text-center w-16">ID</th>
                            <th class="py-3.5 px-4">Pengguna</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">NISN</th>
                            <th class="py-3.5 px-4">Kelas</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (count($rows) > 0): ?>
                            <?php foreach ($rows as $row): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-400 font-medium">
                                        #<?= htmlspecialchars($row['id_anggota']) ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center font-bold text-xs tracking-wider transition-colors shadow-sm">
                                                <?= strtoupper(substr($row['username'], 0, 1)) ?>
                                            </div>
                                            <span class="font-medium text-slate-900">
                                                <?= htmlspecialchars($row['username']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        <?= htmlspecialchars($row['email'] ?: '-') ?>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                                        <?= htmlspecialchars($row['nisn'] ?: '-') ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <?php if ($row['kelas']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                <?= htmlspecialchars($row['kelas']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-xs">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <?php if (strtolower($row['role']) === 'admin'): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> User
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="edit_anggota.php?id=<?= $row['id_anggota'] ?>" 
                                               title="Edit Data" 
                                               class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="hapus_anggota.php?id=<?= $row['id_anggota'] ?>" 
                                               title="Hapus Data" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus anggota <?= htmlspecialchars($row['username']) ?>?');"
                                               class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                                        <i class="fa-regular fa-folder-open"></i>
                                    </div>
                                    <p class="font-medium text-slate-600">Belum ada data anggota</p>
                                    <p class="text-xs text-slate-400 mt-1">Silakan tambahkan anggota baru terlebih dahulu.</p>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <!-- Empty State khusus ketika Live Search tidak menemukan hasil -->
                        <tr id="noSearchResultRow" class="hidden">
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <p class="font-medium text-slate-600">Data tidak ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Live Search Script -->
    <script>
        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("anggotaTable");
            const tr = table.querySelectorAll("tbody tr:not(#noSearchResultRow):not(#emptyRow)");
            const noResultRow = document.getElementById("noSearchResultRow");
            let visibleCount = 0;

            tr.forEach(row => {
                const text = row.textContent || row.innerText;
                if (text.toLowerCase().includes(filter)) {
                    row.style.display = "";
                    visibleCount++;
                } else {
                    row.style.display = "none";
                }
            });

            // Update hitungan data yang terlihat
            const countElem = document.getElementById("visibleCount");
            if (countElem) countElem.innerText = visibleCount;

            // Tampilkan pesan kosong jika pencarian tidak ada hasil
            if (noResultRow) {
                noResultRow.classList.toggle("hidden", visibleCount > 0 || tr.length === 0);
            }
        }
    </script>
</body>
</html>