<?php
session_start();
include "config.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];

/* ambil produk (AMAN) */
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$product = mysqli_stmt_get_result($stmt)->fetch_assoc();

if(!$product){
    echo "Produk tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Produk</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#f1f5f9;
}

/* CONTAINER */
.wrapper{
    max-width:900px;
    margin:10px auto 40px;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    display:flex;
    flex-wrap:wrap;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

/* IMAGE */
.image{
    flex:1;
    min-width:300px;
    background:#f9fafb;
}

.image img{
    width:100%;
    height:100%;
    object-fit:cover;
    aspect-ratio:1/1;
}

/* CONTENT */
.content{
    flex:1;
    padding:25px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}
/* TITLE */
.title{
    font-size:24px;
    font-weight:700;
    margin-bottom:10px;
}

/* PRICE */
.price{
    font-size:22px;
    color:#2563eb;
    font-weight:bold;
    margin-bottom:10px;
}
/* STOCK */
.stock{
    font-size:13px;
    color:#16a34a;
    margin-bottom:15px;
}

/* DESC */
.desc{
    font-size:14px;
    color:#6b7280;
    margin-bottom:20px;
    line-height:1.5;
}

/* ALERT */
.alert{
    background:#dcfce7;
    color:#166534;
    padding:10px;
    border-radius:8px;
    font-size:13px;
    margin-bottom:15px;
}
/* BUTTON WRAP */
.btn-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

/* BUTTON */
.btn{
    flex:1;
    text-align:center;
    padding:10px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    transition:.25s;
}

/* BUY */
.buy{
    background:#2563eb;
    color:white;
}

.buy:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

/* CART */
.cart{
    background:#eff6ff;
    color:#1e3a8a;
}
.cart:hover{
    background:#dbeafe;
    transform:translateY(-2px);
}

/* BACK */
.back{
    margin:20px auto;
    display:block;
    max-width:900px;
    text-decoration:none;
    color:#2563eb;
    font-weight:600;
}
.rating{
    margin-bottom:10px;
    font-size:14px;
    color:#f59e0b; /* warna kuning bintang */
    display:flex;
    align-items:center;
    gap:5px;
}

.rating span{
    color:#6b7280;
    font-size:13px;
}

</style>
</head>

<body>

<a class="back" href="dashboard_user.php">← Kembali</a>

<div class="wrapper">

    <div class="image">
        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <div class="content">

    <?php if(isset($_GET['success'])){ ?>
        <div class="alert">✔ Produk berhasil ditambahkan ke keranjang</div>
    <?php } ?>

    <div class="title"><?= htmlspecialchars($product['name']) ?></div>
    <div class="rating">
        ⭐⭐⭐⭐☆
        <span>(4.0 / 5)</span>
    </div>

    <div class="price">
        Rp <?= number_format($product['price']) ?>
    </div>

    <div class="stock">
        Stok tersedia: <?= $product['stock'] ?>
    </div>

    <div class="desc">
        Produk berkualitas tinggi untuk kebutuhan harian Anda.
        Nyaman digunakan dan terpercaya.
    </div>

    <div class="btn-group">
        <a class="btn buy" href="add_cart.php?id=<?= $product['id'] ?>&buy=1">
            🛒 Beli Sekarang
        </a>
        <a class="btn cart" href="add_cart.php?id=<?= $product['id'] ?>">
            + Keranjang
        </a>
    </div>
</div>

</body>
</html>