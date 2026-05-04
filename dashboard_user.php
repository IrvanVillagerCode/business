<?php
session_start();
include "config.php";

// CEK LOGIN - jika tidak ada session user, redirect ke login
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// CEK ROLE - jika bukan user, redirect ke login (bukan dashboard lain)
$role = strtolower($_SESSION['role']);
if ($role != 'user') {
    // Jika admin, redirect ke dashboard admin
    if ($role == 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: login.php");
    }
    exit();
}

$user = $_SESSION['user'];

// AMBIL DATA USER
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
$user_data = mysqli_fetch_assoc($user_query);

// AMBIL TOTAL ORDERS
$total_orders_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE user_name='$user'");
$total_orders = mysqli_fetch_assoc($total_orders_result)['total'];

// AMBIL TOTAL PENGELUARAN
$total_spent_result = mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE user_name='$user' AND status!='dibatalkan'");
$total_spent = mysqli_fetch_assoc($total_spent_result)['total'];

// AMBIL CART COUNT
$cart_result = mysqli_query($conn, "SELECT COALESCE(SUM(qty), 0) as total FROM cart WHERE user_name='$user'");
$cart_count = mysqli_fetch_assoc($cart_result)['total'];

// AMBIL RECENT ORDERS
$recent_orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_name='$user' ORDER BY created_at DESC LIMIT 5");

// AMBIL PRODUK
$kategori = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'all';

if ($kategori == 'all') {
    $produk = mysqli_query($conn, "SELECT * FROM products");
} else {
    $produk = mysqli_query($conn, "SELECT * FROM products WHERE category='$kategori'");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - ZMart</title>
    <link rel="stylesheet" href="css/animations.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: #0071ce;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 40px;
            color: #ffc220;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin-bottom: 15px;
        }

        .sidebar-menu a {
            color: white;
            text-decoration: none;
            padding: 12px;
            display: block;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #ffc220;
            color: #0071ce;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #0071ce;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #0071ce;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #0071ce;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #0071ce;
        }

        .content-section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            color: #0071ce;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #f0f0f0;
            padding: 12px;
            text-align: left;
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #0071ce;
            color: white;
        }

        .btn-primary:hover {
            background-color: #00539b;
        }

        .btn-warning {
            background-color: #ffc220;
            color: #0071ce;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-dibatalkan {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0071ce;
            box-shadow: 0 0 5px rgba(0, 113, 206, 0.3);
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 8px;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-brand">🛒 ZMart</div>
            <ul class="sidebar-menu">
                <li><a href="dashboard_user.php" class="active">Dashboard</a></li>
                <li><a href="home.php">Belanja</a></li>
                <li><a href="cart.php">Keranjang</a></li>
                <li><a href="orders_user.php">Pesanan Saya</a></li>
                <li><a href="#" onclick="openEditProfileModal(); return false;">Edit Profil</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <!-- HEADER -->
            <div class="header">
                <h1>Selamat Datang, <?php echo htmlspecialchars($user); ?>!</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars(isset($user_data['email']) ? $user_data['email'] : 'Email tidak diset'); ?></span>
                    <div class="user-avatar">
                        <?php if (isset($user_data['foto_profil']) && !empty($user_data['foto_profil'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($user_data['foto_profil']); ?>" alt="Profil">
                        <?php else: ?>
                            <?php echo strtoupper(substr($user, 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>📦 Total Pesanan</h3>
                    <div class="stat-value"><?php echo $total_orders; ?></div>
                </div>

                <div class="stat-card">
                    <h3>💰 Total Pengeluaran</h3>
                    <div class="stat-value">Rp <?php echo number_format($total_spent, 0, ',', '.'); ?></div>
                </div>

                <div class="stat-card">
                    <h3>🛍️ Keranjang</h3>
                    <div class="stat-value"><?php echo $cart_count; ?></div>
                </div>
            </div>

            <!-- RECENT ORDERS -->
            <div class="content-section">
                <h2 class="section-title">Pesanan Terbaru Anda</h2>
                <?php
                $count = mysqli_num_rows($recent_orders);
                if ($count > 0):
                ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo str_replace(' ', '-', strtolower($order['status'])); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Lihat Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color: #666; text-align: center; padding: 20px;">Anda belum memiliki pesanan</p>
                    <div style="text-align: center;">
                        <a href="home.php" class="btn btn-primary">Mulai Berbelanja</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- QUICK LINKS -->
            <div class="content-section">
                <h2 class="section-title">Menu Cepat</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <a href="home.php" class="btn btn-primary" style="text-align: center; padding: 15px;">Belanja Produk</a>
                    <a href="cart.php" class="btn btn-primary" style="text-align: center; padding: 15px;">Lihat Keranjang</a>
                    <a href="orders_user.php" class="btn btn-primary" style="text-align: center; padding: 15px;">Riwayat Pesanan</a>
                    <a href="#" onclick="openEditProfileModal(); return false;" class="btn btn-primary" style="text-align: center; padding: 15px;">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT PROFIL -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profil Anda</h2>
                <span class="close" onclick="closeEditProfileModal()">&times;</span>
            </div>

            <div class="profile-section">
                <div class="profile-avatar">
                    <img id="previewAvatar" src="<?php echo (isset($user_data['foto_profil']) && $user_data['foto_profil']) ? 'uploads/' . htmlspecialchars($user_data['foto_profil']) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect width=%22100%22 height=%22100%22 fill=%22%23e0e0e0%22/%3E%3Ctext x=%2250%22 y=%2260%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2240%22%3E' . strtoupper(substr($user, 0, 1)) . '%3C/text%3E%3C/svg%3E'; ?>" alt="Profil">
                </div>
                <div>
                    <h3>Upload Foto Profil</h3>
                    <input type="file" id="fotoInput" accept="image/*" onchange="previewImage(this)">
                </div>
            </div>

            <form id="editProfileForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="phone">No. Telepon</label>
                    <input type="tel" id="phone" value="<?php echo isset($user_data['no_hp']) ? htmlspecialchars($user_data['no_hp']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea id="address"><?php echo isset($user_data['alamat']) ? htmlspecialchars($user_data['alamat']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" id="password" placeholder="Masukkan password baru">
                </div>

                <button type="button" class="btn btn-primary" onclick="updateProfile()" style="width: 100%; margin-top: 10px;">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script src="js/animations.js"></script>
    <script>
        function openEditProfileModal() {
            const modal = document.getElementById('editProfileModal');
            modal.style.display = 'block';
            modal.style.animation = 'fadeIn 0.3s ease-out';
        }

        function closeEditProfileModal() {
            const modal = document.getElementById('editProfileModal');
            modal.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewAvatar').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function updateProfile() {
            const formData = new FormData();
            formData.append('email', document.getElementById('email').value);
            formData.append('phone', document.getElementById('phone').value);
            formData.append('address', document.getElementById('address').value);
            formData.append('password', document.getElementById('password').value);

            const fileInput = document.getElementById('fotoInput');
            if (fileInput.files && fileInput.files[0]) {
                formData.append('foto', fileInput.files[0]);
            }

            // Show loading spinner
            window.showLoadingSpinner(true);

            fetch('update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    window.showLoadingSpinner(false);
                    if (data.success) {
                        window.showNotification('Profil berhasil diperbarui', 'success');
                        closeEditProfileModal();
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        window.showNotification('Gagal memperbarui profil: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    window.showLoadingSpinner(false);
                    console.error('Error:', error);
                    window.showNotification('Terjadi kesalahan', 'error');
                });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editProfileModal');
            if (event.target == modal) {
                closeEditProfileModal();
            }
        }

        // Add fadeOut animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>