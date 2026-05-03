<!DOCTYPE html>
<html>

<head>
    <title>ZMart - Setup & Test</title>
    <style>
        body {
            font-family: Arial;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0071ce;
            border-bottom: 2px solid #0071ce;
            padding-bottom: 10px;
        }

        h2 {
            color: #333;
            margin-top: 30px;
        }

        .section {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #0071ce;
            border-radius: 4px;
        }

        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .status.ok {
            background: #d4edda;
            color: #155724;
        }

        .status.error {
            background: #f8d7da;
            color: #721c24;
        }

        .status.warning {
            background: #fff3cd;
            color: #856404;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0071ce;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }

        .btn:hover {
            background: #00539b;
        }

        .links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .link-box {
            padding: 15px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .link-box a {
            color: #0071ce;
            text-decoration: none;
            font-weight: bold;
        }

        .link-box a:hover {
            text-decoration: underline;
        }

        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🛒 ZMart E-Commerce - Setup & Testing</h1>

        <div class="section">
            <h2>📋 Checklist Instalasi</h2>

            <?php
            include "config.php";

            echo "<div class='status ok'>✅ Database Connection: OK</div>";

            // Check tables
            $tables = ['users', 'products', 'cart', 'orders', 'order_items', 'chat_messages'];
            foreach ($tables as $table) {
                $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
                if (mysqli_num_rows($check) > 0) {
                    echo "<div class='status ok'>✅ Table <code>$table</code>: OK</div>";
                } else {
                    echo "<div class='status error'>❌ Table <code>$table</code>: MISSING</div>";
                }
            }

            // Check uploads folder
            if (is_dir('uploads') && is_writable('uploads')) {
                echo "<div class='status ok'>✅ Folder uploads: WRITABLE</div>";
            } else {
                echo "<div class='status error'>❌ Folder uploads: NOT WRITABLE</div>";
            }

            // Check profile columns
            $result = mysqli_query($conn, "DESCRIBE users");
            $columns = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $columns[] = $row['Field'];
            }

            $profile_cols = ['email', 'no_hp', 'alamat', 'foto_profil'];
            $missing_cols = [];
            foreach ($profile_cols as $col) {
                if (in_array($col, $columns)) {
                    echo "<div class='status ok'>✅ Column <code>users.$col</code>: OK</div>";
                } else {
                    echo "<div class='status warning'>⚠️ Column <code>users.$col</code>: MISSING</div>";
                    $missing_cols[] = $col;
                }
            }

            if (count($missing_cols) > 0) {
                echo "<div class='status warning' style='margin-top: 20px;'>";
                echo "<strong>⚠️ Ada kolom yang belum ditambahkan!</strong><br>";
                echo "Jalankan: <a href='setup_profile.php' class='btn'>Setup Profile</a>";
                echo "</div>";
            }
            ?>
        </div>

        <div class="section">
            <h2>🔐 Test Login</h2>
            <p>Gunakan akun test berikut untuk login:</p>
            <ul>
                <li><strong>Admin:</strong> Username: <code>admin</code> | Password: <code>12345</code></li>
                <li><strong>User:</strong> Username: <code>user1</code> | Password: <code>12345</code></li>
            </ul>
            <a href="login.php" class="btn">Go to Login</a>
        </div>

        <div class="section">
            <h2>🌐 Navigasi Halaman</h2>
            <div class="links">
                <div class="link-box">
                    <strong>🏠 Public Pages</strong><br>
                    <a href="home.php">Home Page</a>
                </div>
                <div class="link-box">
                    <strong>👥 Authentication</strong><br>
                    <a href="login.php">Login</a> |
                    <a href="register.php">Register</a>
                </div>
                <div class="link-box">
                    <strong>📊 Admin Dashboard</strong><br>
                    <a href="dashboard_admin.php">Dashboard Admin</a><br>
                    <a href="tambah_produk.php">Tambah Produk</a><br>
                    <a href="orders_admin.php">Pesanan Admin</a>
                </div>
                <div class="link-box">
                    <strong>👤 User Dashboard</strong><br>
                    <a href="dashboard_user.php">Dashboard User</a><br>
                    <a href="cart.php">Keranjang</a><br>
                    <a href="orders_user.php">Pesanan User</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>✨ Fitur Baru yang Ditambahkan</h2>
            <ul>
                <li>✅ <strong>Home Page Dinamis</strong> (<code>home.php</code>) - Dengan desain seperti Walmart</li>
                <li>✅ <strong>Dashboard Admin Baru</strong> (<code>dashboard_admin.php</code>) - Dengan statistik & edit profil</li>
                <li>✅ <strong>Dashboard User Baru</strong> (<code>dashboard_user.php</code>) - Dengan profil & statistik pengguna</li>
                <li>✅ <strong>Edit Profil & Upload Foto</strong> (<code>update_profile.php</code>) - Untuk admin dan user</li>
                <li>✅ <strong>Setup Database</strong> (<code>setup_profile.php</code>) - Menambah kolom profil</li>
                <li>✅ <strong>Integrasi Login</strong> - Home page terhubung dengan login & dashboard</li>
            </ul>
        </div>

        <div class="section">
            <h2>🚀 Quick Start</h2>
            <ol>
                <li>Jalankan <a href="setup_profile.php">setup_profile.php</a> untuk setup database</li>
                <li>Login ke <a href="login.php">halaman login</a></li>
                <li>Jelajahi <a href="home.php">halaman home</a> sebagai pengunjung</li>
                <li>Akses dashboard sesuai role (admin/user)</li>
                <li>Edit profil & upload foto di modal edit profil</li>
            </ol>
        </div>

        <div class="section">
            <h2>📝 File Dokumentasi</h2>
            <p>Lihat file <code>README_FEATURES.md</code> untuk dokumentasi lengkap tentang semua fitur yang telah ditambahkan.</p>
        </div>
    </div>
</body>

</html>