<?php
$koneksi = mysqli_connect("localhost", "root", "", "peminjaman_alat");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = mysqli_query($koneksi, "SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komponen Log Aktivitas User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bg-gradient-start: #d9c1a3;
            --bg-gradient-end: #b99a75;
            --header-dark: #1a1a1a;
            --title-green: #2d5a27;
            --border-color: #a68b6d;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(to bottom, var(--bg-gradient-start), var(--bg-gradient-end));
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .title-section {
            border-left: 4px solid var(--title-green);
            padding-left: 15px;
            margin-bottom: 25px;
        }

        .title-text {
            color: var(--title-green);
            font-weight: 700;
            font-size: 22px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.4); 
            border-radius: 2px;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .erp-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .erp-table th {
            background-color: var(--header-dark);
            color: white;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-right: 1px solid #444;
        }

        .erp-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            color: #1a1a1a;
            background: rgba(255, 255, 255, 0.2);
        }

        .erp-table tr:hover td {
            background: rgba(255, 255, 255, 0.5);
        }

        .input-custom {
            background: white;
            border: 1px solid var(--border-color);
            padding: 5px 10px;
            border-radius: 2px;
            font-size: 13px;
            outline: none;
        }

        .role-badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }

        .role-admin { background: #fee2e2; color: #991b1b; }
        .role-petugas { background: #dcfce7; color: #166534; }
        .role-user { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>
    <?php include_once '../template/navbar.php'; ?>

<div class="max-w-7xl mx-auto">
    <div class="title-section">
        <h2 class="title-text">Log Aktivitas & Data User</h2>
    </div>


    </div>

    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 15%">Username</th>
                        <th style="width: 25%">Email</th>
                        <th style="width: 20%">Role / Level</th>
                        <th style="width: 20%">Tanggal Dibuat</th>
                        <th style="width: 15%">Status</th>
                    </tr>
                </thead>

                <tbody id="userTableBody">
                <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td class="font-bold text-center"><?= $data['id_user']; ?></td>
                        <td><span class="font-bold"><?= $data['username']; ?></span></td>
                        <td><?= $data['email']; ?></td>

                        <td>
                            <?php if ($data['role'] == 'admin') { ?>
                                <span class="role-badge role-admin">admin</span>
                            <?php } elseif ($data['role'] == 'petugas') { ?>
                                <span class="role-badge role-petugas">petugas</span>
                            <?php } else { ?>
                                <span class="role-badge role-user">user</span>
                            <?php } ?>
                        </td>

                        <td class="text-gray-700"><?= date('d/m/Y H:i'); ?></td>
                        <td class="italic text-xs text-green-700">Aktif</td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

    <div class="mt-4 flex gap-1 items-center">
        <button class="bg-white border border-[#a68b6d] px-3 py-1 text-xs font-bold hover:bg-gray-100">&lt;</button>
        <span class="text-xs px-4 py-1 bg-white border border-[#a68b6d] font-bold">Menampilkan data dari database</span>
        <button class="bg-white border border-[#a68b6d] px-3 py-1 text-xs font-bold hover:bg-gray-100">&gt;</button>
    </div>
</div>

</body>
</html>
```
