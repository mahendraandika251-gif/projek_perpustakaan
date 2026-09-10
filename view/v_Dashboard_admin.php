<?php
$label = ['Login', 'Tambah Alat', 'Peminjaman', 'Pengembalian'];
$data  = [12, 6, 9, 4];

session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sigmar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="template/dashboard_adm.css">
</head>

<body>

<div class="navbar">
    <h1>Welcome Admin</h1>
    <h1 class="brand">CampingYuk!</h1>

    <div class="hamburger" id="hamburgerBtn">
        <i class="fa-solid fa-bars"></i>
        <div class="dropdown" id="menuDropdown">
            <a href="../controller/logout.php">
                <i class="fa-duotone fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<script>
const hamburgerBtn = document.getElementById("hamburgerBtn");
const menuDropdown = document.getElementById("menuDropdown");
//  Toggle tampil/sembunyi saat icon diklik
hamburgerBtn.addEventListener("click", function(e) {
    e.stopPropagation(); 
    menuDropdown.classList.toggle("show");
});

document.addEventListener("click", function(e) {
    if (!hamburgerBtn.contains(e.target)) {
        menuDropdown.classList.remove("show");
    }
});

menuDropdown.addEventListener("click", function(e) {
    e.stopPropagation();
});
</script>

<div class="wrapper">
    <aside class="sidebar">
        <a href="dashboard_adm.php" class="menu-box">Dashboard</a>
        <a href="view_admin/data_user.php" class="menu-box">Data User</a>
        <a href="view_admin/data_alat.php" class="menu-box">Data Alat</a>
        <a href="view_admin/peminjaman.php" class="menu-box">Peminjaman</a>
        <a href="view_admin/pengembalian.php" class="menu-box">Pengembalian</a>
        <a href="view_admin/kategori.php" class="menu-box">Kategori</a>
        <a href="view_admin/log_aktivitas.php" class="menu-box">Log Aktivitas</a>
        
    </aside>

    <main class="content">
        <div class="card">
            <h3>Statistik Log Aktivitas</h3>
            <canvas id="logChart"></canvas>
        </div>
        <div class="card">
            <h3>Peminjaman Barang</h3>
            <canvas id="chartBarang"></canvas>
        </div>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ===== PIE CHART =====
    new Chart(document.getElementById('logChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($label) ?>,
            datasets: [{
                data: <?= json_encode($data) ?>,
                backgroundColor: [
                    '#3498db',
                    '#2ecc71',
                    '#f1c40f',
                    '#e74c3c'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            radius: '70%',
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
   
    new Chart(document.getElementById('chartBarang'), {
        type: 'bar',
        data: {
            labels: ['Tenda', 'matras', 'sleeping bag', ''],
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: [25, 18, 12, 9],
                backgroundColor: '#3498db'
            }]
        },
        options: {
    responsive: true,
    maintainAspectRatio: false,

    layout: {
        padding: {
            top: 2,
            bottom: 2,
            left: 2,
            right: 2
        }
    },

    plugins: {
        legend: {
            position: 'top',
            labels: {
                padding: 4,      
                font: { size: 11 }
            }
        }
    },

    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                padding: 2,     
                font: { size: 10 }
            }
        },
        x: {
            ticks: {
                padding: 2,
                font: { size: 10 }
            }
        }
    }
}

    });

});
</script>

</body>
</html>
