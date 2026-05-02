<?php
session_start();
include "config.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* VALIDASI ID */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$query = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "Produk tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk ZMart</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
    font-family:'Segoe UI', Arial, sans-serif;
    background:#f1f5f9;
    padding:20px;
    margin:0;
}

/* FORM BOX */
.form-box{
    background:white;
    padding:20px;
    border-radius:12px;
    max-width:420px;
    margin:auto;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

h2{
    color:#2563eb;
    margin-bottom:15px;
}

/* INPUT */
input, textarea, select{
    width:100%;
    padding:10px;
    margin-top:8px;
    margin-bottom:15px;
    border:1px solid #e5e7eb;
    border-radius:8px;
    outline:none;
    font-size:14px;
}

/* BUTTON */
button{
    background:#2563eb;
    color:white;
    padding:10px;
    border:none;
    border-radius:8px;
    width:100%;
    cursor:pointer;
    font-weight:600;
    transition:.2s;
}

button:hover{
    background:#1d4ed8;
}

/* IMAGE */
img{
    width:100px;
    border-radius:8px;
    margin-bottom:10px;
}

/* BACK LINK */
.back{
    display:block;
    margin-top:15px;
    text-align:center;
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}

.back:hover{
    text-decoration:underline;
}

</style>
</head>

<body>

<div class="form-box">

<h2>✏️ Edit Produk</h2>

<form action="update.php" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <label>Nama Produk</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

    <label>Harga</label>
    <input type="number" name="price" value="<?= $data['price'] ?>" required>

    <label>Stok</label>
    <input type="number" name="stock" value="<?= $data['stock'] ?>" required>

    <!-- CATEGORY (NEW) -->
    <label>Kategori</label>
    <select name="category" required>
        <option value="makanan" <?= ($data['category']=='makanan')?'selected':''; ?>>Makanan</option>
        <option value="minuman" <?= ($data['category']=='minuman')?'selected':''; ?>>Minuman</option>
        <option value="rumah" <?= ($data['category']=='rumah')?'selected':''; ?>>Rumah</option>
        <option value="atk" <?= ($data['category']=='atk')?'selected':''; ?>>ATK</option>
    </select>

    <label>Link / Deskripsi</label>
    <input type="text" name="link" value="<?= htmlspecialchars($data['link']) ?>">

    <label>Gambar Saat Ini</label><br>
    <?php if(!empty($data['image'])) { ?>
        <img src="uploads/<?= $data['image'] ?>">
    <?php } else { echo "-"; } ?>

    <label>Ganti Gambar (opsional)</label>
    <input type="file" name="image">

    <button type="submit">Update Produk</button>

</form>

<a href="dashboard_admin.php" class="back">← Kembali</a>

</div>

</body>
</html>