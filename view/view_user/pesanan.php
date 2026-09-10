<?php
session_start();


// CEK LOGIN

if(!isset($_SESSION['data'])){
    header("Location: ../../index.php");
    exit;
}


// KONEKSI DATABASE

$conn = mysqli_connect("", "root", "", "peminjaman_alat");


// AMBIL ID

$id_target = $_GET['id'] ?? null;

if(!$id_target){
    echo "<h2 style='text-align:center;color:white;margin-top:50px;'>ID tidak ditemukan!</h2>";
    exit;
}

// AMBIL DATA ALAT

$query = "SELECT peralatan.*, kategori.nama_kategori 
          FROM peralatan 
          INNER JOIN kategori ON peralatan.id_kategori = kategori.id_kategori 
          WHERE peralatan.id_alat = '$id_target'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_object($result);


// CEK DATA

if(!$data){
    echo "<h2 style='text-align:center;color:white;margin-top:50px;'>Data tidak ditemukan 😢</h2>";
    exit;
}


// AMBIL NAMA USER

$nama_user = $_SESSION['data']['nama'] 
    ?? $_SESSION['data']['username'] 
    ?? 'User';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sewa Alat | YOUKCAMP</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          container: { center: true, padding: '1rem' },
          fontFamily: {
            sans: ["Inter", "sans-serif"],
          },
        },
      },
    }
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body { background-color: #111827; color: #f3f4f6; }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=date]::-webkit-calendar-picker-indicator {
      filter: invert(1);
    }
  </style>
</head>

<body class="antialiased min-h-screen">

  <main class="container py-10">
    <div class="max-w-5xl mx-auto">
      
      <a href="../v_Dashboard_user.php" class="inline-flex items-center text-sm font-bold text-amber-500 mb-8 hover:text-amber-400 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> KEMBALI KE KATALOG
      </a>

      <div class="bg-[#1f2937] rounded-[2.5rem] overflow-hidden shadow-2xl border border-gray-700">
        <div class="flex flex-col md:flex-row">
          
          <div class="md:w-1/2 p-8 bg-[#111827]/50">
            <div class="sticky top-6">
              <div class="aspect-square bg-white flex items-center justify-center overflow-hidden rounded-[2rem] shadow-inner border-4 border-gray-700">
                <img src="../../asset/<?= $data->foto_alat ?>" 
                     alt="<?= $data->nama_alat ?>" 
                     class="w-full h-full object-cover">
              </div>
            </div>
          </div>

          <div class="md:w-1/2 p-8 lg:p-12">
            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-500">
              <?= $data->nama_kategori ?>
            </span>
            
            <h2 class="text-4xl font-black text-white mt-2 mb-6 uppercase tracking-tighter">
              <?= $data->nama_alat ?>
            </h2>

            <div class="space-y-6">
              <div class="flex justify-between items-end pb-6 border-b border-gray-700">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Harga Sewa / Hari</span>
                <span class="text-2xl font-black text-white">
                  Rp <?= number_format((int)$data->harga, 0, ',', '.') ?>
                </span>
              </div>

              <form action="../../controller/c_peminjaman.php?aksi=peminjaman" method="POST" class="space-y-5">
                
                <input type="hidden" name="id_alat" value="<?= $data->id_alat ?>">

                <div>
                  <label class="text-[10px] font-bold uppercase text-gray-500 mb-2 block">Nama Peminjam</label>
                  <input type="text" name="nama_peminjam" value="<?= htmlspecialchars($nama_user) ?>" required
                         class="w-full px-4 py-3 bg-[#374151] border border-gray-600 rounded-xl text-white focus:outline-none focus:border-amber-500 transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
  <label class="text-[10px] font-bold uppercase text-gray-500 mb-2 block">Tgl Pinjam</label>
  <input type="date" 
         name="tgl_pinjam" 
         id="tgl_pinjam"
         required
         class="w-full px-4 py-3 bg-[#374151] border border-gray-600 text-white rounded-xl focus:outline-none focus:border-amber-500 transition-colors">
</div>
                  <div>
  <label class="text-[10px] font-bold uppercase text-gray-500 mb-2 block">Tgl Pinjam</label>
  <input type="date" 
         name="tgl_pinjam" 
         id="tgl_pinjam"
         required
         class="w-full px-4 py-3 bg-[#374151] border border-gray-600 text-white rounded-xl focus:outline-none focus:border-amber-500 transition-colors">
</div>

                  <!-- <div>
                    <label class="text-[10px] font-bold uppercase text-gray-500 mb-2 block">Tgl Kembali</label>
                    <input type="date" name="tgl_kembali" required
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                           class="w-full px-4 py-3 bg-[#374151] border border-gray-600 text-white rounded-xl focus:outline-none focus:border-amber-500">
                  </div> -->
                </div>

                <div>
                  <label class="text-[10px] font-bold uppercase text-gray-500 mb-2 block">Jumlah Unit</label>
                  <div class="flex items-center bg-[#374151] border border-gray-600 rounded-xl w-fit overflow-hidden">
                    <button type="button" onclick="changeQty(-1)" class="px-5 py-3 hover:bg-gray-600 text-white transition-colors">-</button>
                    <input type="number" name="jumlah" id="qtyInput" value="1" readonly 
                           class="w-12 text-center bg-transparent font-bold text-white outline-none">
                    <button type="button" onclick="changeQty(1)" class="px-5 py-3 hover:bg-gray-600 text-white transition-colors">+</button>
                  </div>
                </div>

                <div class="bg-[#111827] p-5 rounded-2xl border border-gray-700 flex justify-between items-center">
                  <span class="text-sm font-bold text-gray-400 uppercase">Estimasi Total</span>
                  <span id="totalDisplay" class="text-xl text-amber-500 font-black">
                    Rp <?= number_format((int)$data->harga, 0, ',', '.') ?>
                  </span>
                </div>

                <button type="submit" class="w-full py-4 bg-amber-600 hover:bg-amber-500 text-white font-black uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-amber-900/20 active:scale-[0.98]">
                  Konfirmasi Sewa
                </button>

              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <footer class="mt-12 py-10 bg-[#030712] text-center border-t border-gray-800">
    <p class="text-[10px] text-gray-600 tracking-[0.3em] uppercase">© 2026 YOUKCAMP Adventure.</p>
  </footer>

  <script>
    const hargaSatuan = <?= (int)$data->harga ?>;
    const qtyInput = document.getElementById('qtyInput');
    const totalDisplay = document.getElementById('totalDisplay');

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            maximumFractionDigits: 0 
        }).format(number).replace("Rp", "Rp ");
    }

    function changeQty(val) {
        let current = parseInt(qtyInput.value);
        let newVal = current + val;
        if (newVal >= 1) {
            qtyInput.value = newVal;
            totalDisplay.innerText = formatRupiah(newVal * hargaSatuan);
        }
    }
  </script>

</body>
</html>
