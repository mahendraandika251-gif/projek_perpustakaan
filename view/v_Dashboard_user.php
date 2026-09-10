<?php
session_start();
include_once '../controller/c_peralatan.php';

// CEK LOGIN
if(!isset($_SESSION['data'])){
    header("Location: ../index.php");
    exit;
}

// AMBIL NAMA USER
$nama_user = $_SESSION['data']['nama'] 
    ?? $_SESSION['data']['username'] 
    ?? 'Pelanggan';

include_once 'template/navbar_adm.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard | LANGKAH RIMBA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          container: { center: true, padding: '1rem' },
          fontFamily: { sans: ["Inter", "sans-serif"] },
        },
      },
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .img-box { width: 100%; height: 100%; object-fit: cover; }
  </style>
</head>

<body class="antialiased min-h-screen bg-slate-50 text-gray-900 dark:bg-gray-900">

  <main class="container py-6">
    <div class="max-w-7xl mx-auto">
      
      <div class="relative w-full h-40 md:h-56 mb-10 overflow-hidden rounded-3xl shadow-xl border border-white dark:border-gray-700">
        <img src="../asset/banner.jpeg" alt="Banner" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-[#ab6821]/40 to-transparent"></div>
      </div>

      <div class="flex items-end justify-between mb-6">
        <div>
          <h2 class="text-2xl font-black tracking-tight text-gray-800 dark:text-white uppercase">Katalog Alat</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">Halo, <?= $nama_user ?>! Pilih alat campingmu.</p>
        </div>
        <div class="h-1 flex-grow mx-6 mb-2 bg-gray-200 dark:bg-gray-700 rounded-full hidden md:block"></div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        
        <?php if (!empty($data_alat)): ?>
          <?php foreach ($data_alat as $data): ?>
          <div class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            
            <div class="p-3">
              <div class="aspect-square bg-gray-50 dark:bg-gray-900/50 flex items-center justify-center overflow-hidden rounded-xl shadow-inner">
                <img src="../asset/<?= $data->foto_alat ?>" 
                     alt="<?= $data->nama_alat ?>" 
                     class="img-box group-hover:scale-110 transition-transform duration-500 rounded-xl">
              </div>
            </div>
            
            <div class="px-4 pb-4">
              <span class="text-[10px] font-bold uppercase tracking-wider text-[#ab6821] dark:text-amber-400">
                <?= $data->nama_kategori ?>
              </span>
              
              <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 truncate mt-1">
                <?= $data->nama_alat ?>
              </h3>
              
              <p class="mt-2 text-gray-900 dark:text-white font-extrabold text-sm">
                Rp <?= number_format((int)$data->harga, 0, ',', '.') ?>
              </p>

              <a href="view_user/pesanan.php?id=<?= $data->id_alat ?>" 
                 class="mt-4 block text-center w-full py-2 bg-gray-100 dark:bg-gray-700 hover:bg-[#ab6821] hover:text-white dark:hover:bg-[#ab6821] text-gray-700 dark:text-gray-200 text-[10px] font-black uppercase tracking-wider rounded-xl transition-all duration-200">
                Sewa Alat
              </a>
             
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full py-20 text-center uppercase tracking-widest font-bold text-gray-400">
            Tidak ada data alat tersedia.
          </div>
        <?php endif; ?>

      </div>
    </div>
  </main>

  <script>
    const root = document.documentElement;
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        root.classList.add('dark');
    }
  </script>

</body>
</html>