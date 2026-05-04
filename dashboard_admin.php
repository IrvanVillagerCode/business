<?php
session_start();
include "config.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Check if role is admin
$role = strtolower($_SESSION['role']);
if ($role != 'admin') {
    // Redirect user ke dashboard mereka
    if ($role == 'user') {
        header("Location: dashboard_user.php");
    } else {
        header("Location: login.php");
    }
    exit();
}

/* NOTIF CHAT MASUK */
$notif = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM chat_messages 
    WHERE receiver='admin' AND is_read=0
"));
$notifCount = $notif['total'];

/* AMBIL PRODUK */
$search = $_GET['search'] ?? '';
$cat = $_GET['cat'] ?? '';
$currentCat = $cat;

$query = "SELECT * FROM products WHERE 1=1";

if ($search != '') {
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $query .= " AND name LIKE '%$safeSearch%'";
}

if ($cat != '') {
    $safeCat = mysqli_real_escape_string($conn, strtolower($cat));
    $query .= " AND LOWER(category) = '$safeCat'";
}

$produk = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard ZMart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">

    <style>
        /* ===== GLOBAL ===== */
        body {
            margin: 0;
            font-family: Arial;
            background: #f1f5f9;
            color: #1f2937;
        }

        /* ===== HEADER ===== */
        .header {
            background: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
        }

        /* BUTTON */
        .btn {
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #2563eb;
            color: white;
            transition: .2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .logout {
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            background: #ef4444;
            color: white;
        }

        .logout:hover {
            background: #dc2626;
        }

        /* ===== CONTAINER ===== */
        .container {
            padding: 15px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        th {
            background: #3b82f6;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        tr:hover {
            background: #f0f6ff;
        }

        a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        /* badge kategori */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            background: #f1f5f9;
            font-size: 12px;
            border: 1px solid #e5e7eb;
        }

        .active-select {
            border: 2px solid #2563eb !important;
            background: #eff6ff;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">

        <div class="logo">🛠 Admin ZMart</div>

        <div style="display:flex;gap:10px;align-items:center;">
            <a class="btn" href="tambah_produk.php">+ Tambah Produk</a>
            <a class="btn" href="orders_admin.php">📦 Orders</a>
            <a class="btn" href="admin_chat.php">
                💬 Chat
                <?php if ($notifCount > 0) { ?>
                    <span style="
            background:red;
            color:white;
            font-size:11px;
            padding:2px 6px;
            border-radius:50%;
            margin-left:5px;
        ">
                        <?= $notifCount ?>
                    </span>
                <?php } ?>
            </a>
            <a class="logout" href="logout.php">Logout</a>
        </div>

    </div>

    <!-- CONTENT -->
    <div class="container">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:10px;">

            <!-- SEARCH -->
            <input type="text" name="search" placeholder="Cari nama produk..."
                value="<?= $_GET['search'] ?? '' ?>"
                style="padding:10px;border:1px solid #ccc;border-radius:8px;flex:1;min-width:200px;">

            <!-- FILTER KATEGORI -->
            <select name="cat"
                class="<?= (($_GET['cat'] ?? '') != '') ? 'active-select' : '' ?>"
                style="padding:10px;border:1px solid #ccc;border-radius:8px;">
                <option value="">Semua Kategori</option>
                <option value="makanan" <?= (($_GET['cat'] ?? '') == 'makanan') ? 'selected' : ''; ?>>Makanan</option>
                <option value="minuman" <?= (($_GET['cat'] ?? '') == 'minuman') ? 'selected' : ''; ?>>Minuman</option>
                <option value="rumah" <?= (($_GET['cat'] ?? '') == 'rumah') ? 'selected' : ''; ?>>Rumah</option>
                <option value="atk" <?= (($_GET['cat'] ?? '') == 'atk') ? 'selected' : ''; ?>>ATK</option>
            </select>

            <button type="submit" style="padding:10px 15px;background:#3b82f6;color:white;border:none;border-radius:8px;">
                Filter
            </button>

        </form>

        <?php if (isset($_GET['hapus'])) { ?>
            <div style="color:green;margin-bottom:10px;font-weight:bold;">
                ✅ Produk berhasil dihapus
            </div>
        <?php } ?>

        <table>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Link / Deskripsi</th>
                <th>Kategori</th>
                <th>Aksi</th>
                <th>Stok</th>
            </tr>

            <?php while ($p = mysqli_fetch_assoc($produk)) { ?>
                <tr>

                    <!-- GAMBAR -->
                    <td>
                        <?php if (!empty($p['image'])) { ?>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" width="60">
                        <?php } else {
                            echo "-";
                        } ?>
                    </td>

                    <!-- NAMA -->
                    <td><?= htmlspecialchars($p['name']) ?></td>

                    <!-- HARGA -->
                    <td>Rp <?= number_format($p['price']) ?></td>

                    <!-- LINK -->
                    <td>
                        <?php
                        if (filter_var($p['link'], FILTER_VALIDATE_URL)) {
                            echo "<a href='{$p['link']}' target='_blank'>Link Produk</a>";
                        } else {
                            echo htmlspecialchars($p['link']);
                        }
                        ?>
                    </td>

                    <!-- KATEGORI (NEW) -->
                    <td>
                        <span class="badge">
                            <?= ucfirst($p['category'] ?? '-') ?>
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td>
                        <a href="edit.php?id=<?= $p['id'] ?>">Edit</a> |
                        <a href="/business/hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus produk?')">Hapus</a>
                    </td>

                    <!-- STOK -->
                    <td><?= $p['stock'] ?></td>

                </tr>
            <?php } ?>

        </table>

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
    <script src="js/animations.js"></script>
</body>

</html>