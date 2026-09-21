<?php
session_start();

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {

    if (isset($_SESSION['data']['role'])) {

        if ($_SESSION['data']['role'] === 'admin') {
            header("Location: view/v_Dashboard_admin.php");
            exit;

        } elseif ($_SESSION['data']['role'] === 'user') {
            header("Location: view/v_Dashboard_user.php");
            exit;
        }
    }
}

// Mengambil pesan error dari session jika proses login gagal
$login_message = "";

if (isset($_SESSION['login_error'])) {
    $login_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistem Autentikasi Website</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        /* Animasi Transisi Halus */
        .fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-[#86b0f3] min-h-screen flex items-center justify-center p-4 selection:bg-black selection:text-white">

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed top-5 right-5 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 ease-in-out bg-gray-900 text-white px-5 py-3 rounded-lg shadow-xl flex items-center gap-3">

        <i id="toast-icon" class="fa-solid fa-circle-check text-green-400"></i>

        <span id="toast-message" class="text-sm font-medium">
            Pesan berhasil dikirim
        </span>

    </div>


    <!-- Wrapper Kartu Utama -->
    <div class="bg-white w-full max-w-[420px] rounded-sm shadow-[0_20px_50px_rgba(0,0,0,0.15)] p-8 sm:p-12 relative overflow-hidden transition-all duration-300">


        <!-- ================= FORM LOGIN ================= -->
        <div id="login-view" class="fade-in">

            <!-- Judul Utama -->
            <h1 class="text-3xl sm:text-[32px] font-black tracking-tight text-black text-center mb-10 uppercase">
                LOGIN
            </h1>


            <!-- FORM LOGIN -->
            <form action="controller/login.php" method="POST" class="space-y-4">


                <!-- Input Username -->
                <div
                    class="relative flex items-center border border-gray-400 bg-[#e0e0e0] focus-within:border-black transition-colors">

                    <span class="pl-4 pr-2 text-gray-700">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>

                    <input
                        type="text"
                        id="login-username"
                        name="username"
                        placeholder="Username"
                        class="w-full py-3 px-2 bg-transparent text-gray-800 placeholder-gray-500 italic text-sm focus:outline-none"
                        required
                        autocomplete="username"
                    />

                </div>


                <!-- Input Password -->
                <div
                    class="relative flex items-center border border-gray-400 bg-[#e0e0e0] focus-within:border-black transition-colors">

                    <span class="pl-4 pr-2 text-gray-700">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>

                    <input
                        type="password"
                        id="login-password"
                        name="password"
                        placeholder="Password"
                        class="w-full py-3 px-2 bg-transparent text-gray-800 placeholder-gray-500 italic text-sm focus:outline-none"
                        required
                        autocomplete="current-password"
                    />

                    <!-- Tombol lihat password -->
                    <button
                        type="button"
                        onclick="togglePasswordVisibility('login-password', 'login-eye-icon')"
                        class="pr-4 text-gray-600 hover:text-black focus:outline-none"
                        aria-label="Tampilkan password"
                    >
                        <i id="login-eye-icon" class="fa-regular fa-eye-slash text-sm"></i>
                    </button>

                </div>


                <!-- Link Lupa Password -->
                <div class="text-right pt-1">

                    <button
                        type="button"
                        onclick="switchView('forgot')"
                        class="text-xs italic text-gray-600 hover:text-black transition-colors focus:outline-none"
                    >
                        Lupa password??
                    </button>

                </div>


                <!-- Tombol Submit Login -->
                <div class="pt-4 flex justify-center">

                    <button
                        type="submit"
                        class="bg-[#121212] hover:bg-black text-white text-xs sm:text-sm font-semibold py-2.5 px-10 transition-all duration-200 shadow hover:shadow-md active:scale-95"
                    >
                        Log in
                    </button>

                </div>


                <!-- Link Pendaftaran -->
                <div class="text-center pt-2">

                    <a
                        href="view/form_pendaftaran.php"
                        class="text-xs text-black font-medium hover:underline focus:outline-none"
                    >
                        Register
                    </a>

                </div>

            </form>

        </div>

    </div>


  >
    <script>

        // Menampilkan / menyembunyikan password
        function togglePasswordVisibility(inputId, iconId) {

            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");

            } else {

                input.type = "password";

                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }


        

    </script>

</body>

</html>
