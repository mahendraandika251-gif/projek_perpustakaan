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
      background-color: #ededf0;
      color: #333;
    }

    /* Sidebar Styling */
    .sidebar {
      width: 250px;
      background-color: #0f0c20;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 24px 16px;
      flex-shrink: 0;
    }

    /* User Profile Section */
    .profile-section {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      padding: 0 8px;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #4a4e69;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      position: relative;
    }

    .online-badge {
      width: 10px;
      height: 10px;
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
      font-size: 14px;
      color: #ffffff;
    }

    .user-role {
      font-size: 12px;
      color: #8d8d99;
    }

    .purple-dot {
      width: 8px;
      height: 8px;
      background-color: #8b5cf6;
      border-radius: 50%;
    }

    /* Navigation Styling */
    .nav-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 14px;
      color: #a0a0b0;
      text-decoration: none; /* Menghilangkan garis bawah a href */
      transition: all 0.2s ease;
      font-weight: 500;
    }

    .nav-item:hover {
      background-color: rgba(255, 255, 255, 0.05);
      color: #ffffff;
    }

    .nav-item.active {
      background-color: #1a1633;
      color: #ffffff;
      font-weight: 600;
    }

    /* Status Indicator Dots */
    .dot-pink {
      width: 8px;
      height: 8px;
      background-color: #ec4899;
      border-radius: 50%;
    }

    .dot-yellow {
      width: 8px;
      height: 8px;
      background-color: #eab308;
      border-radius: 50%;
    }

    /* Bottom Menu Styling */
    .bottom-menu {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .logout-item {
      color: #ef4444;
    }

    .logout-item:hover {
      background-color: rgba(239, 68, 68, 0.1);
      color: #f87171;
    }

    /* Main Content Styling */
    .main-content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
    }

    .content-page {
      display: none;
    }

    .content-page.active {
      display: block;
    }

    .content-page h1 {
      font-size: 24px;
      font-weight: 600;
      color: #1f2937;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <!-- User Profile -->
      <div class="profile-section">
        <div class="user-info">
          <div class="avatar">
            JS
            <span class="online-badge"></span>
          </div>
          <div class="user-details">
            <span class="user-name">Juliana Silva</span>
            <span class="user-role">@pustakawan</span>
          </div>
        </div>
        <span class="purple-dot"></span>
      </div>

      <!-- Navigation Menu dengan a href -->
      <nav class="nav-list">
        <a href="view_admin/data_anggota.php" class="nav-item" onclick="switchTab('data-anggota', this)">
          <span>Data Anggota</span>
        </a>
        <a href="view_admin/dashboard_buku.php" class="nav-item active" onclick="switchTab('data-buku', this)">
          <span>Data Buku</span>
        </a>
        <a href="#statistik" class="nav-item" onclick="switchTab('statistik', this)">
          <span>Statistik</span>
          <span class="dot-pink"></span>
        </a>
        <a href="#kategori-rak" class="nav-item" onclick="switchTab('kategori-rak', this)">
          <span>Kategori Rak</span>
        </a>
        <a href="#program-baca" class="nav-item" onclick="switchTab('program-baca', this)">
          <span>Program Baca</span>
          <span class="dot-yellow"></span>
        </a>
        <a href="#anggota" class="nav-item" onclick="switchTab('anggota', this)">
          <span>Anggota</span>
        </a>
      </nav>
    </div>

    <!-- Bottom Menu dengan a href -->
    <div class="bottom-menu">
      <a href="#pengaturan" class="nav-item" onclick="switchTab('pengaturan', this)">
        <span>Pengaturan</span>
      </a>
      <a href="#logout" class="nav-item logout-item" onclick="switchTab('logout', this)">
        <span>Keluar (Log Out)</span>
      </a>
    </div>
  </aside>

  

  <!-- JavaScript untuk Switch Halaman & URL Hash -->
  <script>
    function switchTab(pageId, element) {
      // Sembunyikan semua konten
      const pages = document.querySelectorAll('.content-page');
      pages.forEach(page => page.classList.remove('active'));

      // Tampilkan konten yang dipilih
      const selectedPage = document.getElementById(pageId);
      if (selectedPage) {
        selectedPage.classList.add('active');
      }

      // Hapus kelas 'active' dari semua tombol navigasi
      const navItems = document.querySelectorAll('.nav-item');
      navItems.forEach(item => item.classList.remove('active'));

      // Tambahkan kelas 'active' ke tombol a href yang diklik
      if (element) {
        element.classList.add('active');
      }
    }
  </script>
</body>
</html>