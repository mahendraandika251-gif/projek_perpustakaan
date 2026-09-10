<?php

include_once '../../controller/c_peminjaman.php';


if (!isset($_SESSION['data']['id_user'])) {
    header("Location: ../../index.php");
    exit;
}

$nama_user = $_SESSION['data']['nama'] ?? $_SESSION['data']['username'] ?? 'Pelanggan';
$data_peminjam = $data_peminjam ?? []; 

include_once '../template/navbar_adm.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard | langkah rimba</title>
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
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

  <div class="container mx-auto mt-8 px-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Peminjaman Alat</h1>
        <p class="text-gray-500 dark:text-gray-400">Selamat datang kembali, <?= htmlspecialchars($nama_user) ?>!</p>
      </div>
      <button id="themeToggle" class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-800 flex items-center justify-center hover:ring-2 ring-blue-500 transition-all">
        <i class="fas fa-moon dark:hidden"></i>
        <i class="fas fa-sun hidden dark:block text-yellow-400"></i>
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Peminjaman</p>
        <p class="text-2xl font-bold text-blue-600"><?= count($data_peminjam) ?></p>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-xs font-semibold">
              <th class="px-6 py-4">Peminjam</th>
              <th class="px-6 py-4">Nama Alat</th>
              <th class="px-6 py-4 text-center">Tanggal Pinjam</th>
              <th class="px-6 py-4 text-center">Batas Kembali</th>
              <th class="px-6 py-4 text-center">Jumlah</th>
              <th class="px-6 py-4 text-center">Status & Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <?php if (!empty($data_peminjam)): ?>
              <?php foreach ($data_peminjam as $row): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                  <td class="px-6 py-4 font-medium"><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-xs">
                      <?= htmlspecialchars($row['nama_alat']) ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-center"><?= date('d M Y', strtotime($row['tanggal_meminjam'])) ?></td>
                  <td class="px-6 py-4 text-sm text-center text-red-500 font-semibold">
                    <?= date('d M Y', strtotime($row['batas_pengembalian'])) ?>
                  </td>
                  <td class="px-6 py-4 text-center"><?= $row['jumlah'] ?> pcs</td>
                  
                  <td class="px-6 py-4 text-center">
                    <?php 
                    $status = $row['status'];
                    $batas_kembali = new DateTime($row['batas_pengembalian']);
                    $sekarang = new DateTime(); 
                    $interval = $sekarang->diff($batas_kembali);
                    $is_expired = $sekarang > $batas_kembali;

                    if ($status == 'Menunggu') : ?>
                        <span class="text-[10px] font-bold uppercase border px-2 py-1 rounded text-yellow-500 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10 italic">
                            Menunggu Persetujuan
                        </span>
                    <?php elseif ($status == 'Ditolak') : ?>
                        <span class="text-[10px] font-bold uppercase border px-2 py-1 rounded text-red-500 border-red-500 bg-red-50 dark:bg-red-900/10 italic">
                            Ditolak
                        </span>
                    <?php elseif ($status == 'Disetujui' || $status == 'Dipinjam') : 
                        if (!$is_expired) : 
                            // Tampilkan Sisa Waktu
                            $sisa_hari = $interval->days;
                            $sisa_jam = $interval->h;
                            $tampilan_waktu = ($sisa_hari > 0) ? "$sisa_hari Hari, $sisa_jam Jam" : "$sisa_jam Jam Lagi";
                            ?>
                            <div class="flex flex-col items-center">
                                <span class="text-[9px] text-gray-400 uppercase font-bold">Sisa Waktu Sewa:</span>
                                <span class="text-xs font-bold text-blue-500"><?= $tampilan_waktu ?></span>
                            </div>
                        <?php else : ?>
                            <div class="flex flex-col gap-1">
                                <button onclick="aksiUser('<?= $row['kode_pinjam'] ?>', 'kembali')" 
                                        class="text-[9px] bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded shadow-sm transition-all">
                                    KEMBALIKAN
                                </button>
                                <button onclick="aksiUser('<?= $row['kode_pinjam'] ?>', 'hilang')" 
                                        class="text-[9px] bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded shadow-sm transition-all">
                                    LAPOR HILANG
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <span class="text-[10px] font-bold uppercase border px-2 py-1 rounded text-gray-500 border-gray-400 bg-gray-50 dark:bg-gray-800">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">Belum ada data peminjaman alat.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  

  <script>
   
    const root = document.documentElement;
    const themeToggle = document.getElementById('themeToggle');
    
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        root.classList.add('dark');
    }

    themeToggle.addEventListener('click', () => {
      root.classList.toggle('dark');
      localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
    });

    
    function aksiUser(id, jenis) {
      let statusTujuan = (jenis === 'kembali') ? "Barang Dikembalikan" : "Barang Hilang";
      if (confirm("Apakah Anda yakin memproses: " + statusTujuan + "?")) {
        
        window.location.href = "../../controller/c.pengembalian.php?aksi=update_status&id=" + id + "&status=" + statusTujuan;
      }
    }
  </script>
</body>
</html>
