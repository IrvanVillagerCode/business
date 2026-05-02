<?php
session_start();
include "config.php";

/* CEK ADMIN */
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* VALIDASI ID */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
    die("ID tidak valid");
}

/* AMBIL ORDER */
$order = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM orders WHERE id=$id
"));

if(!$order){
    die("❌ Order tidak ditemukan");
}

/* AMBIL ITEM (FIX UTAMA DI SINI) */
$items = mysqli_query($conn, "
    SELECT 
        oi.*,
        p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id=$id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Order Admin</title>

<style>
body{
    font-family:Arial;
    margin:0;
    background:#dbeafe;
}

.container{
    max-width:900px;
    margin:30px auto;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 16px rgba(59,130,246,0.1);
}

/* STATUS */
.status{
    padding:5px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
    font-weight:bold;
}

.pending{ background:#f59e0b; }
.proses{ background:#3b82f6; }
.dikirim{ background:#8b5cf6; }
.selesai{ background:#22c55e; }
.dibatalkan{ background:#ef4444; }

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

th{
    background:#3b82f6;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #eee;
}

/* BUTTON */
.btn{
    display:inline-block;
    padding:6px 12px;
    margin:5px 3px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:12px;
}

.proses-btn{ background:#3b82f6; }
.kirim-btn{ background:#8b5cf6; }
.selesai-btn{ background:#22c55e; }
.cancel-btn{ background:#ef4444; }

/* TOTAL */
.total{
    text-align:right;
    font-weight:bold;
    margin-top:10px;
    font-size:16px;
}

/* BACK */
.back{
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    color:#2563eb;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="container">
<div class="card">

<h2>📦 Order #<?= $order['id'] ?></h2>

<p><b>User:</b> <?= htmlspecialchars($order['user_name']) ?></p>

<p>
<b>Status:</b>
<span class="status <?= $order['status'] ?>">
    <?= strtoupper($order['status']) ?>
</span>
</p>

<p><b>Total:</b> Rp <?= number_format($order['total']) ?></p>
<p><small><?= $order['created_at'] ?></small></p>

<!-- ACTION -->
<div>

<?php if($order['status'] == 'pending'){ ?>
    <a class="btn proses-btn"
       href="update_status.php?id=<?= $order['id'] ?>&status=proses">
       Proses
    </a>
<?php } ?>

<?php if($order['status'] == 'proses'){ ?>
    <a class="btn kirim-btn"
       href="update_status.php?id=<?= $order['id'] ?>&status=dikirim">
       Kirim
    </a>
<?php } ?>

<?php if($order['status'] == 'dikirim'){ ?>
    <a class="btn selesai-btn"
       href="update_status.php?id=<?= $order['id'] ?>&status=selesai">
       Selesai
    </a>
<?php } ?>

<?php if($order['status'] != 'dibatalkan' && $order['status'] != 'selesai'){ ?>
    <a class="btn cancel-btn"
       href="update_status.php?id=<?= $order['id'] ?>&status=dibatalkan">
       Batalkan
    </a>
<?php } ?>

</div>

<!-- ITEM TABLE -->
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

<a href="orders_admin.php" class="back">← Kembali</a>

</div>
</div>

</body>
</html>