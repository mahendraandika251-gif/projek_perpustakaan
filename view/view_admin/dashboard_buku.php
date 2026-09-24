<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Buku Perpustakaan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Header Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-sky-200">
                    <i class="fa-solid fa-book-bookmark text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="font-bold text-lg text-slate-800 leading-tight">PerpusHub</h1>
                        <span class="inline-flex items-center gap-1 text-[10px] bg-emerald-100 text-emerald-800 font-mono font-medium px-2 py-0.5 rounded-full border border-emerald-200" title="Path Koneksi Database">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            models/m_koneksi.php
                        </span>
                    </div>
                    <p class="text-xs text-slate-500">Sistem Kelola Buku Perpustakaan (`perpustakaan.buku`)</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 sm:space-x-3">
                <button onclick="openPhpModal()" class="px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-code"></i>
                    <span class="hidden md:inline">Script PHP & Model</span>
                </button>
                <button onclick="resetToDefaultData()" class="px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors flex items-center gap-1.5" title="Reset ke data awal">
                    <i class="fa-solid fa-rotate"></i>
                    <span class="hidden sm:inline">Reset Data</span>
                </button>
                <button onclick="openAddModal()" class="px-4 py-2 text-sm font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-md shadow-sky-100 hover:shadow-sky-200 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Buku</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Koleksi Buku</p>
                    <h3 id="stat-total" class="text-2xl font-bold text-slate-800 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-books"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Buku Tersedia</p>
                    <h3 id="stat-available" class="text-2xl font-bold text-emerald-600 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Sedang Dipinjam</p>
                    <h3 id="stat-borrowed" class="text-2xl font-bold text-amber-600 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Kategori</p>
                    <h3 id="stat-categories" class="text-2xl font-bold text-indigo-600 mt-1">0</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Control Section -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row gap-3 items-center justify-between">
            <!-- Search input -->
            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="search-input" onkeyup="handleSearchFilter()" placeholder="Cari judul, penulis, atau penerbit..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all">
            </div>

            <!-- Filters & Switcher -->
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                <!-- Category Select -->
                <select id="category-filter" onchange="handleSearchFilter()" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    <option value="">Semua Kategori</option>
                    <option value="Teknologi">Teknologi</option>
                    <option value="Fiksi">Fiksi</option>
                    <option value="Sains">Sains</option>
                    <option value="Sejarah">Sejarah</option>
                    <option value="Bisnis">Bisnis</option>
                    <option value="Filsafat">Filsafat</option>
                </select>

                <!-- Status Select -->
                <select id="status-filter" onchange="handleSearchFilter()" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    <option value="">Semua Stok</option>
                    <option value="Tersedia">Tersedia (Stok > 0)</option>
                    <option value="Habis">Habis (Stok = 0)</option>
                </select>

                <!-- View Mode Buttons -->
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <button id="view-table-btn" onclick="setViewMode('table')" class="p-1.5 text-xs font-medium rounded-lg text-slate-700 bg-white shadow-sm transition-all" title="Tampilan Tabel">
                        <i class="fa-solid fa-list px-1"></i>
                    </button>
                    <button id="view-grid-btn" onclick="setViewMode('grid')" class="p-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 rounded-lg transition-all" title="Tampilan Grid Kartu">
                        <i class="fa-solid fa-grip-vertical px-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Books List Container -->
        <div id="books-container" class="space-y-4">
            <!-- Dynamic Content (Table or Grid) will be injected via JS -->
        </div>

        <!-- Empty state placeholder -->
        <div id="empty-state" class="hidden bg-white p-12 rounded-2xl border border-dashed border-slate-300 text-center">
            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Tidak Ada Data Buku</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Tidak ditemukan data buku yang sesuai dengan kata kunci pencarian atau filter Anda.</p>
            <button onclick="clearFilters()" class="mt-4 px-4 py-2 text-xs font-medium text-sky-600 bg-sky-50 hover:bg-sky-100 rounded-xl transition-colors">
                Bersihkan Filter
            </button>
        </div>

    </main>

    <!-- Modal Add/Edit Book -->
    <div id="book-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100 transition-all transform scale-100">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
                <h3 id="modal-title" class="text-base font-bold text-slate-800">Tambah Buku Baru</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="book-form" onsubmit="saveBook(event)" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                <input type="hidden" id="book-id">

                <!-- Judul Buku -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" id="form-judul" required placeholder="Contoh: Laskar Pelangi" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Penulis -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penulis <span class="text-red-500">*</span></label>
                        <input type="text" id="form-penulis" required placeholder="Nama Penulis" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    </div>

            <!-- Modal Form matching Database Columns -->
            <form id="book-form" onsubmit="saveBook(event)" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                <input type="hidden" id="book-id">

                <!-- Judul Buku (judul VARCHAR 150) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Buku (`judul`) <span class="text-red-500">*</span></label>
                    <input type="text" id="form-judul" required maxlength="150" placeholder="Contoh: Pemrograman Web dengan PHP" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Penulis (penulis VARCHAR 100) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penulis (`penulis`) <span class="text-red-500">*</span></label>
                        <input type="text" id="form-penulis" required maxlength="100" placeholder="Nama Penulis" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    </div>

                    <!-- Penerbit (penerbit VARCHAR 100) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penerbit (`penerbit`) <span class="text-red-500">*</span></label>
                        <input type="text" id="form-penerbit" required maxlength="100" placeholder="Nama Penerbit" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Kategori (kategori VARCHAR 20) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori (`kategori`) <span class="text-red-500">*</span></label>
                        <select id="form-kategori" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                            <option value="Teknologi">Teknologi</option>
                            <option value="Fiksi">Fiksi</option>
                            <option value="Sains">Sains</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Bisnis">Bisnis</option>
                            <option value="Filsafat">Filsafat</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>

                    <!-- Tahun Terbit (tahun_terbit YEAR 4) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tahun Terbit (`tahun_terbit`) <span class="text-red-500">*</span></label>
                        <input type="number" id="form-tahun" required min="1900" max="2099" placeholder="2023" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                    </div>
                </div>

                <!-- Stok (srok INT 5) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Stok (`srok`) <span class="text-red-500">*</span></label>
                    <input type="number" id="form-stok" required min="0" max="99999" value="1" placeholder="Jumlah eksemplar" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <!-- Foto (foto VARCHAR 100) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Foto / URL (`foto`) <span class="text-slate-400 font-normal">(Maks. 100 karakter)</span></label>
                    <input type="text" id="form-foto" maxlength="100" placeholder="sampul1.jpg atau https://..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500">
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-2 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-md shadow-sky-100 transition-all">
                        Simpan ke Database
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Buku -->
    <div id="detail-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100">
            <div class="px-6 py-4 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800">Detail Record Database (`buku`)</h3>
                <button onclick="closeDetailModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    <img id="detail-cover" src="" alt="Foto Buku" class="w-28 h-36 object-cover rounded-xl border border-slate-200 shadow-sm mx-auto sm:mx-0 flex-shrink-0">
                    <div class="space-y-2 flex-1 w-full">
                        <div class="flex items-center justify-between">
                            <span id="detail-kategori" class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-sky-50 text-sky-700"></span>
                            <span id="detail-id" class="text-xs font-mono font-bold text-slate-400">ID: #</span>
                        </div>
                        <h2 id="detail-judul" class="text-lg font-bold text-slate-800 leading-snug"></h2>
                        <p id="detail-penulis" class="text-sm text-slate-600"></p>
                        <div class="pt-2 grid grid-cols-2 gap-2 text-xs text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div><strong class="text-slate-700">Penerbit:</strong> <br><span id="detail-penerbit"></span></div>
                            <div><strong class="text-slate-700">Tahun Terbit:</strong> <br><span id="detail-tahun"></span></div>
                            <div><strong class="text-slate-700">Stok (srok):</strong> <br><span id="detail-stok"></span></div>
                            <div><strong class="text-slate-700">Status Stok:</strong> <br><span id="detail-status"></span></div>
                            <div class="col-span-2"><strong class="text-slate-700">File Foto:</strong> <br><span id="detail-foto-file" class="font-mono text-[11px] text-slate-600 break-all"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal PHP Code Integration Guide -->
    <div id="php-modal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="px-6 py-4 bg-slate-800 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <i class="fa-brands fa-php text-2xl text-sky-400"></i>
                    <div>
                        <h3 class="text-sm font-bold leading-tight">Integrasi PHP & Database Connection</h3>
                        <p class="text-[11px] text-slate-400 font-mono">path: ../../models/m_koneksi.php</p>
                    </div>
                </div>
                <button onclick="closePhpModal()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Content & Code Tabs -->
            <div class="p-6 overflow-y-auto space-y-4 text-xs font-mono">
                <div>
                    <label class="block font-sans font-semibold text-slate-700 text-xs mb-1">1. Isi File Koneksi (<code class="text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded">../../models/m_koneksi.php</code>)</label>
                    <pre class="bg-slate-900 text-emerald-400 p-4 rounded-xl overflow-x-auto text-[11px] leading-relaxed border border-slate-800">
&lt;?php
// File: models/m_koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?&gt;</pre>
                </div>

                <div>
                    <label class="block font-sans font-semibold text-slate-700 text-xs mb-1">2. Contoh Query CRUD Buku dengan <code class="text-indigo-600 bg-indigo-50 px-1 py-0.5 rounded">m_koneksi.php</code></label>
                    <pre class="bg-slate-900 text-sky-300 p-4 rounded-xl overflow-x-auto text-[11px] leading-relaxed border border-slate-800">
&lt;?php
// Menghubungkan ke file koneksi
require_once '../../models/m_koneksi.php';

// READ: Ambil Semua Buku
$query = "SELECT * FROM buku ORDER BY id_buku DESC";
$result = mysqli_query($koneksi, $query);

// CREATE: Tambah Buku Baru
if (isset($_POST['tambah'])) {
    $judul        = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $srok         = (int)$_POST['srok'];
    $foto         = mysqli_real_escape_string($koneksi, $_POST['foto']);

    $sql = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, kategori, srok, foto) 
            VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$kategori', '$srok', '$foto')";
    mysqli_query($koneksi, $sql);
}

// UPDATE: Edit Buku
if (isset($_POST['update'])) {
    $id_buku      = (int)$_POST['id_buku'];
    $judul        = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = (int)$_POST['tahun_terbit'];
    $kategori     = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $srok         = (int)$_POST['srok'];
    $foto         = mysqli_real_escape_string($koneksi, $_POST['foto']);

    $sql = "UPDATE buku SET 
            judul='$judul', penulis='$penulis', penerbit='$penerbit', 
            tahun_terbit='$tahun_terbit', kategori='$kategori', 
            srok='$srok', foto='$foto' 
            WHERE id_buku='$id_buku'";
    mysqli_query($koneksi, $sql);
}

// DELETE: Hapus Buku
if (isset($_GET['hapus'])) {
    $id_buku = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM buku WHERE id_buku='$id_buku'");
}
?&gt;</pre>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button onclick="closePhpModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center border border-slate-100">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Hapus Data Buku?</h3>
            <p class="text-xs text-slate-500 mt-1">Apakah Anda yakin ingin menghapus buku <strong id="delete-book-title" class="text-slate-700"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-6 flex items-center justify-center space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Batal
                </button>
                <button id="confirm-delete-btn" class="px-4 py-2 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-md shadow-red-100 transition-all">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2 pointer-events-none"></div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-4">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; 2026 Sistem Informasi Perpustakaan | CRUD Buku Perpustakaan
        </div>
    </footer>

    <script>
        function openPhpModal() {
            document.getElementById('php-modal').classList.remove('hidden');
        }

        function closePhpModal() {
            document.getElementById('php-modal').classList.add('hidden');
        }

        // Sample Initial Data based on MySQL 'buku' table
        const initialBooks = [
            {
                id_buku: 1,
                judul: 'Clean Code: A Handbook of Agile Software Craftsmanship',
                penulis: 'Robert C. Martin',
                penerbit: 'Prentice Hall',
                tahun_terbit: 2008,
                kategori: 'Teknologi',
                srok: 5,
                foto: 'https://images.unsplash.com/photo-1532012197267-da84d127e765?w=300&auto=format&fit=crop&q=80'
            },
            {
                id_buku: 2,
                judul: 'Laskar Pelangi',
                penulis: 'Andrea Hirata',
                penerbit: 'Bentang Pustaka',
                tahun_terbit: 2005,
                kategori: 'Fiksi',
                srok: 2,
                foto: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&auto=format&fit=crop&q=80'
            },
            {
                id_buku: 3,
                judul: 'Sapiens: Riwayat Singkat Umat Manusia',
                penulis: 'Yuval Noah Harari',
                penerbit: 'Kepustakaan Populer Gramedia',
                tahun_terbit: 2014,
                kategori: 'Sejarah',
                srok: 0,
                foto: 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&auto=format&fit=crop&q=80'
            },
            {
                id_buku: 4,
                judul: 'Filosofi Teras',
                penulis: 'Henry Manampiring',
                penerbit: 'Penerbit Buku Kompas',
                tahun_terbit: 2018,
                kategori: 'Filsafat',
                srok: 4,
                foto: 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=300&auto=format&fit=crop&q=80'
            }
        ];

        // State Management
        let books = JSON.parse(localStorage.getItem('perpus_db_buku')) || initialBooks;
        let viewMode = 'table';
        let selectedDeleteId = null;

        // Initialize App
        document.addEventListener('DOMContentLoaded', () => {
            saveToLocalStorage();
            renderStats();
            renderBooks();
        });

        function saveToLocalStorage() {
            localStorage.setItem('perpus_db_buku', JSON.stringify(books));
        }

        function resetToDefaultData() {
            books = [...initialBooks];
            saveToLocalStorage();
            renderStats();
            renderBooks();
            showToast('Data di-reset sesuai contoh database phpMyAdmin', 'info');
        }

        function setViewMode(mode) {
            viewMode = mode;
            const tableBtn = document.getElementById('view-table-btn');
            const gridBtn = document.getElementById('view-grid-btn');

            if (mode === 'table') {
                tableBtn.className = "p-1.5 text-xs font-medium rounded-lg text-slate-700 bg-white shadow-sm transition-all";
                gridBtn.className = "p-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 rounded-lg transition-all";
            } else {
                gridBtn.className = "p-1.5 text-xs font-medium rounded-lg text-slate-700 bg-white shadow-sm transition-all";
                tableBtn.className = "p-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 rounded-lg transition-all";
            }
            renderBooks();
        }

        function renderStats() {
            const total = books.length;
            const available = books.filter(b => Number(b.srok) > 0).length;
            const borrowed = books.filter(b => Number(b.srok) === 0).length;
            const categories = new Set(books.map(b => b.kategori)).size;

            document.getElementById('stat-total').innerText = total;
            document.getElementById('stat-available').innerText = available;
            document.getElementById('stat-borrowed').innerText = borrowed;
            document.getElementById('stat-categories').innerText = categories;
        }

        function getFilteredBooks() {
            const searchVal = document.getElementById('search-input').value.toLowerCase().trim();
            const categoryVal = document.getElementById('category-filter').value;
            const statusVal = document.getElementById('status-filter').value;

            return books.filter(book => {
                const matchSearch = book.judul.toLowerCase().includes(searchVal) ||
                                    book.penulis.toLowerCase().includes(searchVal) ||
                                    book.penerbit.toLowerCase().includes(searchVal);
                
                const matchCategory = categoryVal === '' || book.kategori === categoryVal;
                
                let matchStatus = true;
                if (statusVal === 'Tersedia') matchStatus = Number(book.srok) > 0;
                if (statusVal === 'Habis') matchStatus = Number(book.srok) === 0;

                return matchSearch && matchCategory && matchStatus;
            });
        }

        function handleSearchFilter() {
            renderBooks();
        }

        function clearFilters() {
            document.getElementById('search-input').value = '';
            document.getElementById('category-filter').value = '';
            document.getElementById('status-filter').value = '';
            renderBooks();
        }

        function renderBooks() {
            const filteredBooks = getFilteredBooks();
            const container = document.getElementById('books-container');
            const emptyState = document.getElementById('empty-state');

            if (filteredBooks.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
            }

            if (viewMode === 'table') {
                renderTableView(filteredBooks, container);
            } else {
                renderGridView(filteredBooks, container);
            }
        }

        function renderTableView(data, container) {
            let html = `
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3.5 px-4 w-12 text-center">ID</th>
                                    <th class="py-3.5 px-4">Info Buku (`judul` & `penulis`)</th>
                                    <th class="py-3.5 px-4">Penerbit & Tahun</th>
                                    <th class="py-3.5 px-4">Kategori</th>
                                    <th class="py-3.5 px-4">Stok (`srok`)</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
            `;

            data.forEach(book => {
                const isAvailable = Number(book.srok) > 0;
                const statusBadge = isAvailable
                    ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Tersedia</span>`
                    : `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200/60"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Habis</span>`;

                const coverSrc = (book.foto && book.foto.startsWith('http')) 
                    ? book.foto 
                    : 'https://placehold.co/100x140/f1f5f9/64748b?text=' + encodeURIComponent(book.judul.substring(0, 10));

                html += `
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3.5 px-4 text-center font-mono text-xs font-bold text-slate-400">
                            #${book.id_buku}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="${coverSrc}" alt="Foto" class="w-10 h-14 object-cover rounded-lg border border-slate-200 flex-shrink-0" onerror="this.src='https://placehold.co/100x140/f1f5f9/64748b?text=Buku'">
                                <div>
                                    <h4 class="font-semibold text-slate-800 hover:text-sky-600 transition-colors cursor-pointer" onclick="openDetailModal(${book.id_buku})">${escapeHtml(book.judul)}</h4>
                                    <p class="text-xs text-slate-500"><i class="fa-regular fa-user mr-1"></i>${escapeHtml(book.penulis)}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">
                            <div class="font-medium text-slate-700">${escapeHtml(book.penerbit)}</div>
                            <div class="text-slate-400 text-[11px]">Tahun: ${book.tahun_terbit}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600 border border-slate-200">${escapeHtml(book.kategori)}</span>
                        </td>
                        <td class="py-3.5 px-4 text-xs font-bold text-slate-700">
                            ${book.srok} eksemplar
                        </td>
                        <td class="py-3.5 px-4">
                            ${statusBadge}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end space-x-1">
                                <button onclick="openDetailModal(${book.id_buku})" class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button onclick="openEditModal(${book.id_buku})" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Buku">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button onclick="openDeleteModal(${book.id_buku})" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Buku">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            container.innerHTML = html;
        }

        function renderGridView(data, container) {
            let html = `<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">`;

            data.forEach(book => {
                const coverSrc = (book.foto && book.foto.startsWith('http')) 
                    ? book.foto 
                    : 'https://placehold.co/100x140/f1f5f9/64748b?text=' + encodeURIComponent(book.judul.substring(0, 10));

                const isAvailable = Number(book.srok) > 0;
                const statusClass = isAvailable ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700';

                html += `
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                        <div class="p-4">
                            <div class="flex gap-3 items-start">
                                <img src="${coverSrc}" alt="Foto" class="w-20 h-28 object-cover rounded-xl border border-slate-200 shadow-xs flex-shrink-0" onerror="this.src='https://placehold.co/100x140/f1f5f9/64748b?text=Buku'">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-slate-100 text-slate-600">${escapeHtml(book.kategori)}</span>
                                        <span class="text-[10px] font-mono font-bold text-slate-400">#${book.id_buku}</span>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm line-clamp-2 hover:text-sky-600 cursor-pointer transition-colors" onclick="openDetailModal(${book.id_buku})">${escapeHtml(book.judul)}</h4>
                                    <p class="text-xs text-slate-500 mt-1 truncate"><i class="fa-regular fa-user mr-1"></i>${escapeHtml(book.penulis)}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 truncate"><i class="fa-solid fa-building-columns mr-1"></i>${escapeHtml(book.penerbit)} (${book.tahun_terbit})</p>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="px-2 py-0.5 rounded-md font-semibold ${statusClass}">${isAvailable ? 'Tersedia' : 'Habis'} (${book.srok})</span>
                            <div class="flex items-center space-x-1">
                                <button onclick="openDetailModal(${book.id_buku})" class="p-1.5 text-slate-500 hover:text-sky-600 rounded-lg hover:bg-white transition-colors" title="Detail">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button onclick="openEditModal(${book.id_buku})" class="p-1.5 text-slate-500 hover:text-amber-600 rounded-lg hover:bg-white transition-colors" title="Edit">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </button>
                                <button onclick="openDeleteModal(${book.id_buku})" class="p-1.5 text-slate-500 hover:text-red-600 rounded-lg hover:bg-white transition-colors" title="Hapus">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
            container.innerHTML = html;
        }

        function openAddModal() {
            document.getElementById('modal-title').innerText = 'Tambah Buku Baru (`buku`)';
            document.getElementById('book-form').reset();
            document.getElementById('book-id').value = '';
            document.getElementById('book-modal').classList.remove('hidden');
        }

        function openEditModal(id) {
            const book = books.find(b => Number(b.id_buku) === Number(id));
            if (!book) return;

            document.getElementById('modal-title').innerText = 'Edit Data Buku ID: #' + book.id_buku;
            document.getElementById('book-id').value = book.id_buku;
            document.getElementById('form-judul').value = book.judul;
            document.getElementById('form-penulis').value = book.penulis;
            document.getElementById('form-penerbit').value = book.penerbit;
            document.getElementById('form-tahun').value = book.tahun_terbit;
            document.getElementById('form-kategori').value = book.kategori;
            document.getElementById('form-stok').value = book.srok;
            document.getElementById('form-foto').value = book.foto || '';

            document.getElementById('book-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('book-modal').classList.add('hidden');
        }

        function saveBook(e) {
            e.preventDefault();

            const id = document.getElementById('book-id').value;
            const judul = document.getElementById('form-judul').value.trim();
            const penulis = document.getElementById('form-penulis').value.trim();
            const penerbit = document.getElementById('form-penerbit').value.trim();
            const tahun_terbit = parseInt(document.getElementById('form-tahun').value) || new Date().getFullYear();
            const kategori = document.getElementById('form-kategori').value;
            const srok = parseInt(document.getElementById('form-stok').value) || 0;
            const foto = document.getElementById('form-foto').value.trim();

            if (!judul || !penulis || !penerbit) {
                showToast('Mohon isi field judul, penulis, dan penerbit', 'error');
                return;
            }

            if (id) {
                // Update Existing Record
                const index = books.findIndex(b => Number(b.id_buku) === Number(id));
                if (index !== -1) {
                    books[index] = { 
                        id_buku: Number(id), 
                        judul, 
                        penulis, 
                        penerbit, 
                        tahun_terbit, 
                        kategori, 
                        srok, 
                        foto 
                    };
                    showToast('Data buku #' + id + ' berhasil diperbarui!', 'success');
                }
            } else {
                // Create New Record (AUTO_INCREMENT Simulation)
                const maxId = books.reduce((max, b) => Number(b.id_buku) > max ? Number(b.id_buku) : max, 0);
                const newBook = {
                    id_buku: maxId + 1,
                    judul, 
                    penulis, 
                    penerbit, 
                    tahun_terbit, 
                    kategori, 
                    srok, 
                    foto
                };
                books.unshift(newBook);
                showToast('Buku baru ID #' + newBook.id_buku + ' berhasil ditambahkan!', 'success');
            }

            saveToLocalStorage();
            renderStats();
            renderBooks();
            closeModal();
        }

        function openDetailModal(id) {
            const book = books.find(b => Number(b.id_buku) === Number(id));
            if (!book) return;

            const isAvailable = Number(book.srok) > 0;

            document.getElementById('detail-id').innerText = 'ID: #' + book.id_buku;
            document.getElementById('detail-judul').innerText = book.judul;
            document.getElementById('detail-penulis').innerText = 'Oleh: ' + book.penulis;
            document.getElementById('detail-penerbit').innerText = book.penerbit;
            document.getElementById('detail-kategori').innerText = book.kategori;
            document.getElementById('detail-tahun').innerText = book.tahun_terbit;
            document.getElementById('detail-stok').innerText = book.srok + ' Eksemplar';
            document.getElementById('detail-status').innerText = isAvailable ? 'Tersedia' : 'Habis';
            document.getElementById('detail-foto-file').innerText = book.foto || '-';
            
            const coverImg = document.getElementById('detail-cover');
            const coverSrc = (book.foto && book.foto.startsWith('http')) 
                ? book.foto 
                : 'https://placehold.co/100x140/f1f5f9/64748b?text=' + encodeURIComponent(book.judul.substring(0, 10));
                
            coverImg.src = coverSrc;
            coverImg.onerror = function() {
                this.src = 'https://placehold.co/100x140/f1f5f9/64748b?text=Buku';
            };

            document.getElementById('detail-modal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }

        function openDeleteModal(id) {
            const book = books.find(b => Number(b.id_buku) === Number(id));
            if (!book) return;

            selectedDeleteId = id;
            document.getElementById('delete-book-title').innerText = `"#${book.id_buku} - ${book.judul}"`;
            document.getElementById('confirm-delete-btn').onclick = () => deleteBook(id);
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            selectedDeleteId = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function deleteBook(id) {
            books = books.filter(b => Number(b.id_buku) !== Number(id));
            saveToLocalStorage();
            renderStats();
            renderBooks();
            closeDeleteModal();
            showToast('Record buku #' + id + ' berhasil dihapus', 'success');
        }

        // Toast Notification Function
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            let bgClass = 'bg-slate-800 text-white';
            let icon = 'fa-circle-info';

            if (type === 'success') {
                bgClass = 'bg-emerald-600 text-white';
                icon = 'fa-circle-check';
            } else if (type === 'error') {
                bgClass = 'bg-red-600 text-white';
                icon = 'fa-circle-xmark';
            }

            toast.className = `flex items-center gap-2.5 px-4 py-3 rounded-xl shadow-lg text-xs font-medium ${bgClass} transition-all transform translate-y-2 opacity-0 pointer-events-auto`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${escapeHtml(message)}</span>`;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
</body>
</html>