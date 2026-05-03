<?php
session_start();
include "config.php";

// CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// CEK ROLE
if ($_SESSION['role'] != 'user') {
    header("Location: dashboard_admin.php");
    exit;
}

$user = $_SESSION['user'];

/* AMBIL PRODUK */
/* FILTER KATEGORI */
$kategori = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : 'all';

if($kategori == 'all'){
    $produk = mysqli_query($conn, "SELECT * FROM products");
} else {
    $produk = mysqli_query($conn, "SELECT * FROM products WHERE category='$kategori'");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - ZMart</title>
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
<?php
$cartCount = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(qty),0) as total 
    FROM cart 
    WHERE user_name='$user'
"));
?>
<!-- HEADER -->
<div class="header">

    <div class="logo">ZMart</div>

    <div class="search">
        <input type="text" id="search" placeholder="Cari produk...">
    </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Pesanan</h3>
                    <div class="stat-value"><?php echo $total_orders; ?></div>
                </div>

                <div class="stat-card">
                    <h3>Total Pengeluaran</h3>
                    <div class="stat-value">Rp <?php echo number_format($total_spent ?? 0, 0, ',', '.'); ?></div>
                </div>

                <div class="stat-card">
                    <h3>Keranjang</h3>
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
                                        <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-primary">Lihat</a>
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

<!-- BANNER -->
<div class="banner">
    <div>
        🔥 Promo Hari Ini
        <br><small>Diskon sampai 50%</small>
    </div>
    <div>🏷️ SALE</div>
</div>

<!-- PRODUK -->
<div class="container">

<?php while($p = mysqli_fetch_assoc($produk)) { ?>

<div class="card product">

    <img src="uploads/<?= $p['image'] ?>">

    <div class="body">

        <a href="detail_produk.php?id=<?= $p['id'] ?>" class="title">
            <?= htmlspecialchars($p['name']) ?>
        </a>

        <div class="price">
            Rp <?= number_format($p['price']) ?>
        </div>

        <div class="btn-group">
            <a class="buy" href="buy_now.php?id=<?= $p['id'] ?>">
                Beli
            </a>

            <a class="cart-btn" href="add_cart.php?id=<?= $p['id'] ?>">
                +
            </a>
        </div>
    </div>

    <!-- MODAL EDIT PROFIL -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profil</h2>
                <span class="close" onclick="closeEditProfileModal()">&times;</span>
            </div>

            <div class="profile-section">
                <div class="profile-avatar">
                    <img id="previewAvatar" src="<?php echo (isset($user_data['foto_profil']) && $user_data['foto_profil']) ? 'uploads/' . $user_data['foto_profil'] : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ctext x=%2250%22 y=%2260%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2240%22%3E' . strtoupper(substr($user_data['username'], 0, 1)) . '%3C/text%3E%3C/svg%3E'; ?>" alt="Profil">
                </div>
                <div>
                    <h3>Upload Foto Profil</h3>
                    <input type="file" id="fotoInput" accept="image/*" onchange="previewImage(this)">
                </div>
            </div>

            <form id="editProfileForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo $user_data['username']; ?>" disabled>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="phone">No. Telepon</label>
                    <input type="tel" id="phone" value="<?php echo isset($user_data['no_hp']) ? $user_data['no_hp'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea id="address"><?php echo isset($user_data['alamat']) ? $user_data['alamat'] : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" id="password">
                </div>

                <button type="button" class="btn btn-primary" onclick="updateProfile()">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <script>
        function openEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'block';
        }

        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
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

            fetch('update_profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profil berhasil diperbarui');
                        closeEditProfileModal();
                        location.reload();
                    } else {
                        alert('Gagal memperbarui profil: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editProfileModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>

</html>