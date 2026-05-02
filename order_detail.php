<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$user = $_SESSION['user'];

/* AMBIL ORDER (WAJIB ADA) */
$stmt = mysqli_prepare($conn, "
    SELECT * FROM orders 
    WHERE id=? AND user_name=?
");
mysqli_stmt_bind_param($stmt, "is", $id, $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);

/* JIKA ORDER TIDAK ADA */
if(!$order){
    echo "❌ Order tidak ditemukan atau bukan milik Anda";
    exit;
}

/* AMBIL ITEM ORDER */
$stmt2 = mysqli_prepare($conn, "
    SELECT 
        oi.*, 
        p.name 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id=?
");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);
$items = mysqli_stmt_get_result($stmt2);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Order</title>

<style>
body{
    font-family:'Segoe UI', sans-serif;
    margin:0;
    background:#f1f5f9;
    color:#1f2937;
}

.container{
    max-width:900px;
    margin:30px auto;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:16px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

h2{ margin:0; }

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.pending{ background:#fef3c7; color:#92400e; }
.proses{ background:#dbeafe; color:#1e3a8a; }
.selesai{ background:#dcfce7; color:#166534; }

.info{
    margin-bottom:15px;
}
.info p{
    margin:5px 0;
    font-size:14px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    border-radius:10px;
    overflow:hidden;
}

th{
    background:#2563eb;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.total{
    text-align:right;
    font-weight:bold;
    margin-top:10px;
    font-size:16px;
}

.back{
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    background:#e5e7eb;
    padding:8px 12px;
    border-radius:8px;
    color:#111;
    font-size:13px;
}
.back:hover{
    background:#d1d5db;
}
</style>

</head>

<body>

<div class="container">

<div class="card">

<div class="header">
    <h2>📦 Order #<?= $order['id'] ?></h2>
    <span class="status <?= $order['status'] ?>">
        <?= strtoupper($order['status']) ?>
    </span>
</div>

<div class="info">
    <p><b>User:</b> <?= htmlspecialchars($order['user_name']) ?></p>
    <p><b>Tanggal:</b> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
    <p><b>Total:</b> Rp <?= number_format($order['total']) ?></p>
</div>

<table>
<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
</tr>

<?php 
$grand = 0;
while($i = mysqli_fetch_assoc($items)){
    $sub = $i['price'] * $i['qty'];
    $grand += $sub;
?>

<tr>
    <td><?= htmlspecialchars($i['name']) ?></td>
    <td>Rp <?= number_format($i['price']) ?></td>
    <td><?= $i['qty'] ?></td>
    <td>Rp <?= number_format($sub) ?></td>
</tr>

<?php } ?>

</table>

<div class="total">
    Grand Total: Rp <?= number_format($grand) ?>
</div>

<a href="orders_user.php" class="back">← Kembali</a>

</div>

</div>

</body>
</html>