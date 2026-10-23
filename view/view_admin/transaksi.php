<?php
// Memanggil file koneksi
require_once '../../models/m_koneksi.php';

// Membuat instance dari class Koneksi
$db = new Koneksi(); 
$koneksi = $db->koneksi; 

// Mengambil nilai filter dari URL (jika ada)
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';

// Menyusun query berdasarkan filter status
if ($filter_status == 'menunggu_pinjam') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'menunggu_persetujuan_pinjam' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'dipinjam') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'dipinjam' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'menunggu_kembali') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'menunggu_persetujuan_kembali' ORDER BY transaksi.id_transaksi DESC";
} elseif ($filter_status == 'dikembalikan') {
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            WHERE transaksi.status = 'dikembalikan' ORDER BY transaksi.id_transaksi DESC";
} else {
    // Default: tampilkan semua
    $sql = "SELECT transaksi.*, anggota.username FROM transaksi 
            JOIN anggota ON transaksi.id_anggota = anggota.id_anggota 
            ORDER BY transaksi.id_transaksi DESC";
}

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Peminjaman & Pengembalian</title>
    <!-- Google Fonts & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #eff6ff;
            --accent: #92BDFF;
            --bg-body: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            padding: 28px 32px;
        }

        /* Header Styling */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title h2 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .page-title p {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Print Button Styling */
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-print:hover {
            background-color: #1e293b;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
        }

        /* Filter Tab Bar */
        .filter-container {
            display: flex;
            gap: 8px;
            background: #f8fafc;
            padding: 6px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            overflow-x: auto;
        }

        .filter-btn {
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            color: var(--text-muted);
            transition: all 0.2s ease;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-btn:hover {
            color: var(--primary);
            background: #ffffff;
        }

        .filter-btn.active {
            background: #ffffff;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Table Card */
        .card-table {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13.5px;
        }

        thead {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            padding: 14px 18px;
            font-weight: 600;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* User & Book Chips */
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .user-avatar-mini {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .code-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
        }

        /* Status Badges */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-acc-pinjam {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-dipinjam {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .badge-acc-kembali {
            background-color: #f3e8ff;
            color: #7c3aed;
        }

        .badge-dikembalikan {
            background-color: #dcfce7;
            color: #16a34a;
        }

        /* Action Buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-acc-pinjam {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .btn-acc-pinjam:hover {
            background-color: #2563eb;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }

        .btn-acc-kembali {
            background-color: #10b981;
            color: #ffffff;
        }

        .btn-acc-kembali:hover {
            background-color: #059669;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-done {
            background-color: #f1f5f9;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 36px;
            margin-bottom: 12px;
            color: #cbd5e1;
        }

        /* Print Specific Styling */
        .print-header {
            display: none;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 10px;
                color: #000000;
            }
            .page-header, .filter-container, .col-action, th:last-child, td:last-child, .btn-print {
                display: none !important;
            }
            .print-header {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }
            .print-header h2 {
                font-size: 18px;
                font-weight: bold;
            }
            .print-header p {
                font-size: 12px;
                color: #555;
            }
            .card-table {
                border: none;
                box-shadow: none;
            }
            table {
                border: 1px solid #cbd5e1;
            }
            th, td {
                border: 1px solid #cbd5e1;
                padding: 8px 10px;
            }
            thead {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="page-header">
        <div class="page-title">
            <h2>Peminjaman & Pengembalian</h2>
            <p>Kelola persetujuan dan riwayat transaksi buku perpustakaan.</p>
        </div>
        <button onclick="window.print()" class="btn-print">
            <i class="fa-solid fa-print"></i> Cetak Laporan
        </button>
    </div>

    <!-- Cetak Header Khusus Print -->
    <div class="print-header">
        <h2>LAPORAN TRANSAKSI PEMINJAMAN & PENGEMBALIAN BUKU</h2>
        <p>Perpustakaan Digital — Dicetak pada: <?= date('d-m-Y H:i'); ?></p>
    </div>

    <!-- Filter Status Navigation -->
    <div class="filter-container">
        <a href="transaksi.php" class="filter-btn <?= ($filter_status == '') ? 'active' : ''; ?>">
            <i class="fa-solid fa-list-ul"></i> Semua
        </a>
        <a href="transaksi.php?status=menunggu_pinjam" class="filter-btn <?= ($filter_status == 'menunggu_pinjam') ? 'active' : ''; ?>">
            <i class="fa-solid fa-clock"></i> Acc Pinjam
        </a>
        <a href="transaksi.php?status=dipinjam" class="filter-btn <?= ($filter_status == 'dipinjam') ? 'active' : ''; ?>">
            <i class="fa-solid fa-book-open"></i> Dipinjam
        </a>
        <a href="transaksi.php?status=menunggu_kembali" class="filter-btn <?= ($filter_status == 'menunggu_kembali') ? 'active' : ''; ?>">
            <i class="fa-solid fa-rotate-left"></i> Acc Kembali
        </a>
        <a href="transaksi.php?status=dikembalikan" class="filter-btn <?= ($filter_status == 'dikembalikan') ? 'active' : ''; ?>">
            <i class="fa-solid fa-circle-check"></i> Dikembalikan
        </a>
    </div>

    <!-- Main Table Card -->
    <div class="card-table">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">ID</th>
                        <th>Peminjam</th>
                        <th style="text-align: center;">ID Buku</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: center;">Tgl Pinjam</th>
                        <th style="text-align: center;">Tgl Kembali</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center; width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { 
                            $initial = strtoupper(substr($row['username'], 0, 1));
                    ?>
                        <tr>
                            <td style="text-align: center;">
                                <span class="code-badge">#<?= htmlspecialchars($row['id_transaksi']); ?></span>
                            </td>
                            <td>
                                <div class="user-chip">
                                    <div class="user-avatar-mini"><?= $initial; ?></div>
                                    <div>
                                        <span><?= htmlspecialchars($row['username']); ?></span>
                                        <div style="font-size: 11px; color: var(--text-muted);">ID: <?= htmlspecialchars($row['id_anggota']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="code-badge">BK-<?= htmlspecialchars($row['id_buku']); ?></span>
                            </td>
                            <td style="text-align: center; font-weight: 600;">
                                <?= htmlspecialchars($row['jumlah']); ?>
                            </td>
                            <td style="text-align: center; color: var(--text-muted);">
                                <?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?>
                            </td>
                            <td style="text-align: center; color: var(--text-muted);">
                                <?= (!empty($row['tanggal_kembali']) && $row['tanggal_kembali'] != '0000-00-00') ? date('d M Y', strtotime($row['tanggal_kembali'])) : '<span style="color: #cbd5e1;">-</span>'; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($row['status'] == 'menunggu_persetujuan_pinjam') : ?>
                                    <span class="badge-status badge-acc-pinjam">
                                        <i class="fa-solid fa-hourglass-half"></i> Wait Acc
                                    </span>
                                <?php elseif ($row['status'] == 'dipinjam') : ?>
                                    <span class="badge-status badge-dipinjam">
                                        <i class="fa-solid fa-book-reader"></i> Dipinjam
                                    </span>
                                <?php elseif ($row['status'] == 'menunggu_persetujuan_kembali') : ?>
                                    <span class="badge-status badge-acc-kembali">
                                        <i class="fa-solid fa-arrow-rotate-left"></i> Wait Kembali
                                    </span>
                                <?php elseif ($row['status'] == 'dikembalikan') : ?>
                                    <span class="badge-status badge-dikembalikan">
                                        <i class="fa-solid fa-check"></i> Selesai
                                    </span>
                                <?php else : ?>
                                    <span class="badge-status" style="background: #f1f5f9; color: #475569;">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($row['status'] == 'menunggu_persetujuan_pinjam') : ?>
                                    <a href="../../controller/proses_acc_pinjam.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="btn-action btn-acc-pinjam" 
                                       onclick="return confirm('Setujui peminjaman buku ini?')">
                                       <i class="fa-solid fa-check"></i> Acc Pinjam
                                    </a>
                                <?php elseif ($row['status'] == 'menunggu_persetujuan_kembali') : ?>
                                    <a href="../../controller/proses_kembali.php?id=<?= $row['id_transaksi']; ?>" 
                                       class="btn-action btn-acc-kembali" 
                                       onclick="return confirm('Setujui pengembalian buku ini?')">
                                       <i class="fa-solid fa-box-archive"></i> Acc Kembali
                                    </a>
                                <?php elseif ($row['status'] == 'dikembalikan') : ?>
                                    <button class="btn-action btn-done" disabled>
                                        <i class="fa-solid fa-lock"></i> Selesai
                                    </button>
                                <?php else : ?>
                                    <span style="font-size: 12px; color: var(--text-muted);">Aktif Dipinjam</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else { 
                    ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fa-regular fa-folder-open"></i>
                                    <p>Tidak ada data transaksi yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>