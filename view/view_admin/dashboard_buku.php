<?php
require_once '../../models/m_buku.php';

$model_buku = new m_buku();

// Ambil parameter search & filter
$search = $_GET['search'] ?? '';
$filter_kategori = $_GET['kategori'] ?? '';

// Ambil list data dari Model
$buku_list = $model_buku->tampil_data($search, $filter_kategori);

// Hitung statistik
$total_buku = count($buku_list);
$total_stok = 0;
$kategori_set = [];

foreach ($buku_list as $buku) {
    $total_stok += $buku['stok'];
    $kategori_set[$buku['kategori']] = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Data Buku - Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Stat Cards Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Judul Buku</p>
                    <h3 class="text-2xl font-extrabold text-slate-900"><?php echo number_format($total_buku); ?></h3>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Stok Fisik</p>
                    <h3 class="text-2xl font-extrabold text-slate-900"><?php echo number_format($total_stok); ?></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kategori Buku</p>
                    <h3 class="text-2xl font-extrabold text-slate-900"><?php echo count($kategori_set); ?></h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>

        <!-- Controls Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <form method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-72">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               placeholder="Cari Judul, Penulis, Penerbit..." 
                               class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <select name="kategori" class="py-2 px-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">Semua Kategori</option>
                        <?php foreach (array_keys($kategori_set) as $kat): ?>
                            <option value="<?php echo htmlspecialchars($kat); ?>" <?php echo $filter_kategori === $kat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-filter text-xs"></i> Filter
                    </button>
                </form>

                <!-- Tombol Berpindah ke Halaman Form Tambah Buku -->
                <a href="tambah_buku.php" class="w-full md:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-indigo-500/20 transition flex items-center justify-center gap-2 inline-flex">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Buku Baru
                </a>
            </div>
        </div>

        <!-- Book Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Daftar Koleksi Buku</h2>
                <span class="text-xs font-semibold bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                    <?php echo count($buku_list); ?> Data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <th class="py-3.5 px-4"># ID</th>
                            <th class="py-3.5 px-4">Sampul</th>
                            <th class="py-3.5 px-4">Judul Buku</th>
                            <th class="py-3.5 px-4">Penulis</th>
                            <th class="py-3.5 px-4">Penerbit & Tahun</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4 text-center">Stok</th>
                            <th class="py-3.5 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($buku_list)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    <p class="font-medium text-slate-600">Tidak ada data buku ditemukan.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($buku_list as $buku): ?>
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-3.5 px-4 font-mono text-xs text-slate-500 font-bold">#<?php echo htmlspecialchars($buku['id_buku']); ?></td>
                                    <td class="py-3.5 px-4">
                                        <div class="w-10 h-14 bg-slate-100 rounded-md overflow-hidden border border-slate-200 shadow-sm flex items-center justify-center">
                                            <?php 
                                                $foto_val = trim($buku['foto'] ?? '');
                                                $fallback_img = 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200&auto=format&fit=crop&q=60';
                                                if (empty($foto_val)) {
                                                    $image_src = $fallback_img;
                                                } elseif (filter_var($foto_val, FILTER_VALIDATE_URL)) {
                                                    $image_src = htmlspecialchars($foto_val);
                                                } else {
                                                    $image_src = '../../asset/img/' . htmlspecialchars($foto_val);
                                                }
                                            ?>
                                            <img src="<?php echo $image_src; ?>" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='<?php echo $fallback_img; ?>';">
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-900"><?php echo htmlspecialchars($buku['judul']); ?></td>
                                    <td class="py-3.5 px-4 text-slate-600"><?php echo htmlspecialchars($buku['penulis']); ?></td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        <div class="font-medium"><?php echo htmlspecialchars($buku['penerbit']); ?></div>
                                        <div class="text-xs text-slate-400">Tahun: <?php echo htmlspecialchars($buku['tahun_terbit']); ?></div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                            <?php echo htmlspecialchars($buku['kategori']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold"><?php echo (int)$buku['stok']; ?></td>

                                    <!-- Action Buttons -->
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Link Edit Buku ke Halaman Form Edit -->
                                            <a href="edit_buku.php?id=<?php echo $buku['id_buku']; ?>" 
                                               class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" 
                                               title="Edit Buku">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <!-- Link Hapus ke Controller Hapus -->
                                            <a href="../../controller/c_hapus_buku.php?id=<?php echo $buku['id_buku']; ?>" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')"
                                               class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition" 
                                               title="Hapus Buku">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>