<?php
// Pastikan variabel $content_view sudah diset sebelum file ini di-include
if (!isset($content_view) || !file_exists($content_view)) {
    die("Error: File konten tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'PustakaCare - Admin' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons & Font Awesome -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        sidebar: { DEFAULT: '#141226', hover: '#201c38', active: '#f3f4f6' },
                        brand: { purple: '#8b5cf6', darkPurple: '#7c3aed', lightPurple: '#f3e8ff' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #EFEFF4; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex bg-[#ECECEE]">

    <!-- SIDEBAR NAVIGATION (KIRI) -->
    <aside id="sidebar" class="w-72 bg-[#141226] text-gray-300 flex flex-col justify-between p-5 select-none transition-all duration-300 z-30 flex-shrink-0 h-full">
        <div>
            <!-- Profile / Header Sidebar -->
            <div class="flex items-center justify-between mb-8 px-2 pt-2">
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" 
                             alt="Juliana Silva" 
                             class="w-11 h-11 rounded-full object-cover border-2 border-brand-purple/50 group-hover:border-brand-purple transition">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-[#141226] rounded-full"></span>
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="font-semibold text-white text-sm leading-tight truncate group-hover:text-brand-purple transition">Juliana Silva</h4>
                        <p class="text-xs text-gray-400 truncate">@pustakawan</p>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <button class="relative p-2 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full animate-ping"></span>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full"></span>
                    </button>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
                <a href="data_anggota.php" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Data Anggota</span>
                </a>
                <a href="dashboard_buku.php" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Data Buku</span>
                </a>
                <a href="#" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="line-chart" class="w-5 h-5"></i>
                    <span>Statistik</span>
                    <span class="ml-auto w-2 h-2 rounded-full bg-pink-400"></span>
                </a>
                <a href="#" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    <span>Kategori Rak</span>
                </a>
                <a href="#" class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Program Baca</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="pt-6 border-t border-white/10 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>Pengaturan</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Keluar (Log Out)</span>
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN KANAN DYNAMIC -->
    <main class="flex-1 h-full overflow-y-auto p-4 sm:p-6 lg:p-8 bg-[#ECECEE]">
        <?php include $content_view; ?>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>