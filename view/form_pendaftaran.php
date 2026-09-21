<?php
require_once "../models/m_koneksi.php";

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama     = trim($_POST["nama"]);
    $nisn     = trim($_POST["nisn"]);
    $email    = trim($_POST["email"]);
    $kelas     = trim($_POST["kelas"]);
    $password = $_POST["password"];

    // mengambil koneksi dari MySQLi
    $conn = $koneksi->koneksi;

    // Cek username atau NISN apakah sudah terdaftar atau belum
    $cek = $conn->prepare("
        SELECT id_anggota 
        FROM anggota 
        WHERE username = ? OR nisn = ?
    ");

    $cek->bind_param("ss", $nama, $nisn);
    $cek->execute();

    $hasil = $cek->get_result();

    if ($hasil->num_rows > 0) {

        $pesan = "Nama atau NISN sudah terdaftar!";

    } else {

        // Enkripsi password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Role otomatis user
        $role = "user";

        // Masukkan data ke database
        $query = $conn->prepare("
            INSERT INTO anggota 
            (username, password, email, kelas, nisn, role)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $query->bind_param(
            "ssssss",
            $nama,
            $passwordHash,
            $email,
            $kelas,
            $nisn,
            $role
        );

        if ($query->execute()) {

            // Berhasil membuat akun
            header("Location: ../index.php");
            exit;

        } else {

            $pesan = "Registrasi gagal: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form Registrasi</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#8BB2FC] min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-sm shadow-2xl w-full max-w-md p-8 sm:p-12 text-center">

        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-wider uppercase mb-8">
            REGISTRASI
        </h1>

        <?php if (!empty($pesan)): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-5 text-sm">
                <?= htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5 text-left">

            <!-- Nama -->
            <div>
                <label for="nama"
                    class="block text-xs font-semibold text-gray-700 mb-1">
                    Nama
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Daniel Gallego"
                    value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                    class="w-full px-3 py-2 bg-[#EAEAEA] border border-gray-400 focus:border-black focus:bg-white text-gray-700 placeholder-gray-400 italic text-sm outline-none transition-colors"
                    required>
            </div>


            <!-- NISN -->
            <div>
                <label for="nisn"
                    class="block text-xs font-semibold text-gray-700 mb-1">
                    NISN
                </label>

                <input
                    type="text"
                    id="nisn"
                    name="nisn"
                    placeholder="NISN"
                    value="<?= isset($_POST['nisn']) ? htmlspecialchars($_POST['nisn']) : '' ?>"
                    class="w-full px-3 py-2 bg-[#EAEAEA] border border-gray-400 focus:border-black focus:bg-white text-gray-700 placeholder-gray-400 italic text-sm outline-none transition-colors"
                    required>
            </div>


            <!-- Email -->
            <div>
                <label for="email"
                    class="block text-xs font-semibold text-gray-700 mb-1">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="hello@reallygreatsite.com"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                    class="w-full px-3 py-2 bg-[#EAEAEA] border border-transparent focus:border-black focus:bg-white text-gray-700 placeholder-gray-400 italic text-sm outline-none transition-colors"
                    required>
            </div>

            <!-- KELAS -->
            <div>
                <label for="nisn"
                    class="block text-xs font-semibold text-gray-700 mb-1">
                    KELAS
                </label>

                <input
                    type="text"
                    id="kelas"
                    name="kelas"
                    placeholder="KELAS"
                    value="<?= isset($_POST['kelas']) ? htmlspecialchars($_POST['kelas']) : '' ?>"
                    class="w-full px-3 py-2 bg-[#EAEAEA] border border-gray-400 focus:border-black focus:bg-white text-gray-700 placeholder-gray-400 italic text-sm outline-none transition-colors"
                    required>
            </div>


            <!-- Password -->
            <div>
                <label for="password"
                    class="block text-xs font-semibold text-gray-700 mb-1">
                    Password
                </label>

                <div class="relative">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••••"
                        class="w-full pl-3 pr-10 py-2 bg-[#EAEAEA] border border-transparent focus:border-black focus:bg-white text-gray-700 placeholder-gray-400 text-sm outline-none transition-colors"
                        required>

                    <button
                        type="button"
                        onclick="togglePasswordVisibility()"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-black p-1 text-xs">

                        <i id="eye-icon"
                            class="fa-solid fa-eye-slash"></i>

                    </button>

                </div>
            </div>


            <!-- Tombol -->
            <div class="pt-4 flex flex-col items-center">

                <button
                    type="submit"
                    class="w-36 py-2.5 bg-[#121212] hover:bg-black text-white text-xs font-semibold tracking-wide transition-all shadow-md active:scale-95">

                    Buat Akun

                </button>

                <span class="text-xs text-gray-600 mt-3 font-normal">

                    or
                    <a href="../index.php"
                        class="hover:underline font-medium text-black">
                        Log in
                    </a>

                </span>

            </div>

        </form>

    </div>


    <script>

        function togglePasswordVisibility() {

            const password = document.getElementById("password");
            const icon = document.getElementById("eye-icon");

            if (password.type === "password") {

                password.type = "text";

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            } else {

                password.type = "password";

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");

            }
        }

    </script>

</body>

</html>