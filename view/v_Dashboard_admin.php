<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PustakaCare - Dashboard Perpustakaan Modern</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        sidebar: {
                            DEFAULT: '#141226',
                            hover: '#201c38',
                            active: '#f3f4f6',
                        },
                        brand: {
                            purple: '#8b5cf6',
                            darkPurple: '#7c3aed',
                            lightPurple: '#f3e8ff',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #EFEFF4;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="h-screen w-screen overflow-hidden flex bg-[#ECECEE]">

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="w-72 bg-[#141226] text-gray-300 flex flex-col justify-between p-5 select-none transition-all duration-300 z-30 flex-shrink-0">
        <!-- Sidebar Header / Profile -->
        <div>
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
                    <button id="notifBtn" class="relative p-2 text-gray-400 hover:text-white rounded-lg hover:bg-white/5 transition">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full animate-ping"></span>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-purple-500 rounded-full"></span>
                    </button>
                    <button class="text-gray-400 hover:text-white p-1">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
                <!-- Home / Beranda -->
                <div class="relative group">
                    <div id="home-indicator" class="absolute left-[-20px] top-1/2 -translate-y-1/2 w-1.5 h-8 bg-white rounded-r-full"></div>
                    <a href="#" onclick="switchTab('home')" id="nav-home" 
                       class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-semibold bg-white text-[#8b5cf6] shadow-lg shadow-purple-900/20 transition-all">
                        <i data-lucide="home" class="w-5 h-5"></i>
                        <span>Beranda Utama</span>
                    </a>
                </div>

                <!-- Buku & Katalog -->
                <a href="#" onclick="switchTab('catalog')" id="nav-catalog" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Katalog Buku</span>
                    <span class="ml-auto text-xs bg-purple-500/20 text-purple-300 font-semibold px-2 py-0.5 rounded-full">14k</span>
                </a>

                <!-- Peminjaman & Pengembalian -->
                <a href="#" onclick="switchTab('loans')" id="nav-loans" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Peminjaman</span>
                    <span class="ml-auto w-2 h-2 rounded-full bg-purple-400"></span>
                </a>

                <!-- Statistik -->
                <a href="#" onclick="switchTab('stats')" id="nav-stats" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="line-chart" class="w-5 h-5"></i>
                    <span>Statistik</span>
                    <span class="ml-auto w-2 h-2 rounded-full bg-pink-400"></span>
                </a>

                <!-- Kategori & Rak -->
                <a href="#" onclick="switchTab('categories')" id="nav-categories" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="layout-grid" class="w-5 h-5"></i>
                    <span>Kategori Rak</span>
                </a>

                <!-- Program & Promo Baca -->
                <a href="#" onclick="switchTab('promos')" id="nav-promos" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Program Baca</span>
                    <span class="ml-auto w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                </a>

                <!-- Anggota Perpustakaan -->
                <a href="view_admin" onclick="switchTab('members')" id="nav-members" 
                   class="nav-item flex items-center gap-4 px-4 py-3.5 rounded-2xl font-medium text-gray-300 hover:text-white hover:bg-white/5 transition-all">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    <span>Anggota</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="pt-6 border-t border-white/10 space-y-2">
            <a href="#" onclick="openModal('settingsModal')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 rounded-xl transition">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>Pengaturan</span>
            </a>
            <a href="#" onclick="showToast('Anda berhasil keluar dari sistem.', 'info')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Keluar (Log Out)</span>
            </a>
        </div>
    </aside>

   