<?php
session_start();
include "config.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* SIMPAN DATA */
if(isset($_POST['submit'])){

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $link  = mysqli_real_escape_string($conn, $_POST['link']);
    $price = max(0, (int)$_POST['price']);
    $stock = max(0, (int)$_POST['stock']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    /* UPLOAD GAMBAR */
    $image = "";

    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    }

    mysqli_query($conn, "
        INSERT INTO products (name, price, link, image, stock, category)
        VALUES ('$name','$price','$link','$image','$stock','$category')
    ");

    header("Location: dashboard_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Produk ZMart</title>
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
input, select{
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

/* BACK */
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

<h2>+ Tambah Produk</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Nama Produk</label>
    <input type="text" name="name" required>

    <label>Harga</label>
    <input type="number" name="price" required>

    <label>Stok</label>
    <input type="number" name="stock" required>

    <!-- CATEGORY (NEW) -->
    <label>Kategori</label>
    <select name="category" required>
        <option value="makanan">Makanan</option>
        <option value="minuman">Minuman</option>
        <option value="rumah">Rumah</option>
        <option value="atk">ATK</option>
    </select>

    <label>Link / Deskripsi</label>
    <input type="text" name="link">

    <label>Upload Gambar</label>
    <input type="file" name="image" required>

    <button type="submit" name="submit">Simpan</button>

</form>

<a href="dashboard_admin.php" class="back">← Kembali</a>

</div>

</body>
</html>