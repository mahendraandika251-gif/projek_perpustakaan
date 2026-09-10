<?php
include_once '../../controller/c_riwayat_pengembalian.php';

if (!isset($_SESSION['data']['id_user'])) {
    header("Location: ../../index.php");
    exit;
}

include_once '../template/navbar_adm.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Riwayat Peminjaman | LANGKAH RIMBA</title>

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
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Arsip Riwayat Peminjaman
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Daftar alat yang telah Anda kembalikan.
                </p>
            </div>

            <button id="themeToggle"
                class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-800 flex items-center justify-center hover:ring-2 ring-blue-500 transition-all">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block text-yellow-400"></i>
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-xs font-semibold">
                            <th class="px-6 py-4">ID Sewa</th>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4 text-center">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-center">Tgl Kembali</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <?php if (!empty($data_riwayat)): ?>
                            <?php foreach ($data_riwayat as $row): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                 <td class="px-6 py-4">
                                        <span class="font-mono text-sm text-gray-500">
                                            #<?= $row->Id_sewa ?? '0' ?>
                                        </span>
                                    </td>

                                      <td class="px-6 py-4 font-medium">
                                        <?= htmlspecialchars($row->nama_peminjam ?? 'Tanpa Nama') ?>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-center">
                                        <?= isset($row->tanggal_peminjaman) 
                                            ? date('d M Y', strtotime($row->tanggal_peminjaman)) 
                                            : '-' ?>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-center text-green-500 font-semibold">
                                        <?= isset($row->tanggal_pengembalian) 
                                            ? date('d M Y', strtotime($row->tanggal_pengembalian)) 
                                            : '-' ?>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full text-[10px] font-bold uppercase">
                                            Selesai
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                    Belum ada riwayat pengembalian.
                                </td>
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

        if (localStorage.getItem('theme') === 'dark') {
            root.classList.add('dark');
        }

        themeToggle.addEventListener('click', () => {
            root.classList.toggle('dark');
            localStorage.setItem(
                'theme',
                root.classList.contains('dark') ? 'dark' : 'light'
            );
        });
    </script>

</body>
</html>