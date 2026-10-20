<?php
// Memulai session
session_start();

// Mengambil data dari session login (menyesuaikan struktur database Anda)
$username_admin = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
$role_admin = isset($_SESSION['role']) ? $_SESSION['role'] : 'pustakawan';

// Mengambil 2 huruf pertama dari username untuk inisial avatar secara otomatis
$initials = strtoupper(substr($username_admin, 0, 2));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Perpustakaan</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background-color: #f4f6f9;
      color: #333;
      overflow: hidden;
    }

    /* Sidebar Styling */
    .sidebar {
      width: 270px;
      background: linear-gradient(180deg, #0f0c20 0%, #171330 100%);
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 24px 18px;
      flex-shrink: 0;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
      height: 100vh;
    }

    /* Logo Brand Perpustakaan */
    .brand-section {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 4px 8px;
      margin-bottom: 24px;
    }

    .brand-logo {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, #92BDFF 0%, #3b82f6 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 12px rgba(146, 189, 255, 0.3);
    }

    .brand-title {
      font-size: 16px;
      font-weight: 700;
      color: #ffffff;
      letter-spacing: 0.5px;
    }

    .brand-subtitle {
      font-size: 11px;
      color: #92BDFF;
    }

    /* User Profile Section */
    .profile-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      padding: 12px;
      background-color: rgba(255, 255, 255, 0.03);
      border-radius: 14px;
      border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background-color: #92BDFF;
      color: #0f0c20;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 13px;
      position: relative;
    }

    .online-badge {
      width: 9px;
      height: 9px;
      background-color: #2ec4b6;
      border-radius: 50%;
      position: absolute;
      bottom: 0;
      right: 0;
      border: 2px solid #0f0c20;
    }

    .user-details {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 600;
      font-size: 13px;
      color: #ffffff;
      text-transform: capitalize;
    }

    .user-role {
      font-size: 11px;
      color: #92BDFF;
      text-transform: uppercase;
    }

    .accent-dot {
      width: 6px;
      height: 6px;
      background-color: #92BDFF;
      border-radius: 50%;
      box-shadow: 0 0 8px #92BDFF;
    }

    /* Navigation Styling */
    .nav-label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #7b7b95;
      margin-bottom: 10px;
      padding-left: 8px;
      font-weight: 600;
    }

    .nav-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13.5px;
      color: #b0b0c3;
      text-decoration: none;
      transition: all 0.25s ease;
      font-weight: 500;
      cursor: pointer;
    }

    .nav-item:hover {
      background-color: rgba(146, 189, 255, 0.08);
      color: #92BDFF;
      transform: translateX(3px);
    }

    .nav-item.active {
      background: linear-gradient(90deg, rgba(146, 189, 255, 0.15) 0%, rgba(146, 189, 255, 0.03) 100%);
      color: #92BDFF;
      font-weight: 600;
      border-left: 3px solid #92BDFF;
    }

    .dot-blue {
      width: 7px;
      height: 7px;
      background-color: #92BDFF;
      border-radius: 50%;
      box-shadow: 0 0 6px #92BDFF;
    }

    /* Bottom Menu Styling */
    .bottom-menu {
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      padding-top: 14px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .logout-item {
      color: #f87171;
    }

    .logout-item:hover {
      background-color: rgba(239, 68, 68, 0.1);
      color: #ef4444;
    }

    /* Main Content Styling (Area Kanan menggunakan Iframe) */
    .main-content {
      flex: 1;
      padding: 0;
      overflow: hidden;
      height: 100vh;
      background-color: #f4f6f9;
    }

    .content-frame {
      width: 100%;
      height: 100%;
      border: none;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <!-- Logo Perpustakaan -->
      <div class="brand-section">
        <div class="brand-logo">📚</div>
        <div>
          <div class="brand-title">E-Perpus</div>
          <div class="brand-subtitle">Sistem Digital</div>
        </div>
      </div>

      <!-- User Profile -->
      <div class="profile-section">
        <div class="user-info">
          <div class="avatar">
            <?php echo htmlspecialchars($initials); ?>
            <span class="online-badge"></span>
          </div>
          <div class="user-details">
            <span class="user-name"><?php echo htmlspecialchars($username_admin); ?></span>
            <span class="user-role"><?php echo htmlspecialchars($role_admin); ?></span>
          </div>
        </div>
        <span class="accent-dot"></span>
      </div>

      <!-- Navigation Menu (Menggunakan target iframe agar langsung muncul di kanan) -->
      <div class="nav-label">Menu Utama</div>
      <nav class="nav-list">
        <a href="view_admin/dashboard_buku.php" target="contentFrame" class="nav-item active" onclick="setActive(this)">
          <span>Data Buku</span>
          <span class="dot-blue"></span>
        </a>
        <a href="view_admin/data_anggota.php" target="contentFrame" class="nav-item" onclick="setActive(this)">
          <span>Data Anggota</span>
        </a>
        <a href="view_admin/transaksi.php" target="contentFrame" class="nav-item" onclick="setActive(this)">
          <span>Transaksi</span>
        </a>
      </nav>
    </div>

    <!-- Bottom Menu -->
    <div class="bottom-menu">
      <a href="../controller/logout.php" class="nav-item logout-item">
        <span>Keluar (Log Out)</span>
      </a>
    </div>
  </aside>

  <!-- Area Konten Utama di Sebelah Kanan (Iframe) -->
  <main class="main-content">
    <iframe name="contentFrame" src="view_admin/dashboard_buku.php" class="content-frame"></iframe>
  </main>

  <!-- JavaScript Sederhana untuk Mengatur Menu Aktif -->
  <script>
    function setActive(element) {
      document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
        // Hapus dot biru dari menu lain
        const dot = item.querySelector('.dot-blue');
        if (dot) dot.remove();
      });

      element.classList.add('active');
      
      // Tambahkan kembali dot biru ke menu yang sedang aktif
      if (!element.querySelector('.dot-blue')) {
        const dotSpan = document.createElement('span');
        dotSpan.className = 'dot-blue';
        element.appendChild(dotSpan);
      }
    }
  </script>
</body>
</html>