<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* AMBIL CART */
$cart = mysqli_query($conn, "
    SELECT c.*, 
           p.name, 
           p.price, 
           p.image 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_name='$user'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background:#f1f5f9;
}
/* CONTAINER */
.container{
    max-width:900px;
    margin:30px auto;
    padding:20px;
}

/* TITLE */
h2{
    color:#111;
    margin-bottom:20px;
}

/* CARD */
.card{
    background:#fff;
    border-radius:16px;
    padding:15px;
    margin-bottom:15px;

    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}
.card:hover{
    transform:translateY(-3px);
}
/* LEFT */
.left{
    display:flex;
    gap:15px;
    align-items:center;
}
.thumb{
    width:80px;
    height:80px;
    object-fit:contain;
    background:#f9fafb;
    border-radius:10px;
}

/* INFO */
.info h3{
    margin:0;
    font-size:15px;
}
.price{
    font-size:13px;
    color:#6b7280;
}

.subtotal{
    font-weight:bold;
    color:#2563eb;
}
/* RIGHT */
.right{
    display:flex;
    align-items:center;
    gap:10px;
}

/* QTY */
.qty-box{
    display:flex;
    align-items:center;
    border-radius:10px;
    overflow:hidden;
    border:1px solid #e5e7eb;
}

.qty-box a{
    padding:6px 12px;
    background:#f3f4f6;
    text-decoration:none;
    font-weight:bold;
    color:#111;
}

.qty-box a:hover{
    background:#e5e7eb;
}

.qty-box span{
    padding:0 12px;
}

/* DELETE */
.delete{
    background:#fee2e2;
    color:#dc2626;
    padding:8px;
    border-radius:8px;
    text-decoration:none;
}

.delete:hover{
    background:#fecaca;
}
/* SUMMARY */
.summary{
    margin-top:20px;
    background:#fff;
    padding:20px;
    border-radius:16px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}
.total{
    font-size:20px;
    font-weight:bold;
    margin-bottom:10px;
}

/* BUTTON */
.btn{
    display:block;
    width:100%;
    padding:12px;
    border-radius:10px;
    text-align:center;
    font-weight:600;
    text-decoration:none;
}

.checkout{
    background:#2563eb;
    color:white;
}

.checkout:hover{
    background:#1d4ed8;
}

.back{
    background:#e5e7eb;
    color:#111;
    margin-top:10px;
}

</style>

</head>
<body>

<div class="container">

<h2>🛒 Keranjang Saya</h2>

<?php if(mysqli_num_rows($cart) == 0) { ?>

    <div class="empty-box">
        <h3>Keranjang kosong</h3>
        <p>Yuk mulai belanja sekarang</p>
        <a href="dashboard_user.php" class="btn back">← Belanja</a>
    </div>

<?php } else { ?>

<?php 
$total = 0;
while($item = mysqli_fetch_assoc($cart)) { 

$subtotal = $item['price'] * $item['qty'];
$total += $subtotal;
?>

<div class="card">
    <div class="left">
        <img src="uploads/<?= !empty($item['image']) ? $item['image'] : 'default.png' ?>" class="thumb">

    <div class="info">
        <h3><?= htmlspecialchars($item['name']) ?></h3>
        <p class="price">Rp <?= number_format($item['price']) ?></p>
        <p class="subtotal">Subtotal: Rp <?= number_format($subtotal) ?></p>
    </div>
</div>
<div class="right">
    <div class="qty-box">
        <a href="kurang_qty.php?id=<?= $item['product_id'] ?>">−</a>
        <span><?= $item['qty'] ?></span>
        <a href="tambah_qty.php?id=<?= $item['product_id'] ?>">+</a>
    </div>
    <a href="hapus_cart.php?id=<?= $item['product_id'] ?>" class="delete"onclick="return confirm('Hapus produk ini?')">🗑️</a>
</div>

<?php } ?>

<!-- SUMMARY -->
<div class="summary">
    <div class="total">
        Total: Rp <?= number_format($total) ?>
    </div>

    <a href="checkout.php" class="btn checkout">💳 Checkout</a>
    <a href="dashboard_user.php" class="btn back">← Kembali Belanja</a>
</div>

<?php } ?>

</div>

</body>
</html>