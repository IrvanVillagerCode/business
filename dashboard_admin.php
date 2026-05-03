<?php
session_start();
include "config.php";

/* =====================
   AUTH
===================== */
if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* =====================
   FILTER TANGGAL
===================== */
$start = $_GET['start'] ?? date('Y-m-07');
$end   = $_GET['end'] ?? date('Y-m-d');

/* =====================
   STATISTIK
===================== */
$totalSales = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COALESCE(SUM(total),0) as total
    FROM orders
    WHERE status='selesai'
    AND DATE(created_at) BETWEEN '$start' AND '$end'
"))['total'];

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as total
    FROM orders
    WHERE DATE(created_at) BETWEEN '$start' AND '$end'
"))['total'];

$totalItems = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COALESCE(SUM(qty),0) as total
    FROM order_items oi
    JOIN orders o ON oi.order_id=o.id
    WHERE DATE(o.created_at) BETWEEN '$start' AND '$end'
"))['total'];

/* =====================
   CHART DATA
===================== */
$chart = mysqli_query($conn,"
    SELECT DATE(created_at) as tgl, SUM(total) as total
    FROM orders
    WHERE status='selesai'
    AND DATE(created_at) BETWEEN '$start' AND '$end'
    GROUP BY DATE(created_at)
");

$labels = [];
$data = [];

while($c = mysqli_fetch_assoc($chart)){
    $labels[] = $c['tgl'];
    $data[] = $c['total'];
}

/* =====================
   NOTIF CHAT
===================== */
$notif = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) as total 
    FROM chat_messages 
    WHERE receiver='admin' AND is_read=0
"));
$notifCount = $notif['total'];

/* =====================
   FILTER PRODUK
===================== */
$search = $_GET['search'] ?? '';
$cat = $_GET['cat'] ?? '';

$query = "SELECT * FROM products WHERE 1=1";

if($search != ''){
    $safeSearch = mysqli_real_escape_string($conn, $search);
    $query .= " AND name LIKE '%$safeSearch%'";
}

if($cat != ''){
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    margin:0;
    font-family:'Segoe UI',Arial;
    background:#f1f5f9;
    color:#1f2937;
}
.header{
    background:white;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}
.logo{
    font-size:20px;
    font-weight:bold;
    color:#2563eb;
}
.btn{
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
    background:#2563eb;color:white;
}
.btn:hover{
    opacity:0.9;
    transform:translateY(-1px);
    transition:0.2s;
}
.logout{
    background:#ef4444;
    color:white;
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
}
.container{
    padding:15px;
}
.card{
    background:white;
    padding:15px;
    border-radius:12px;
    flex:1;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}
.card:hover{
    transform:translateY(-2px);
    transition:0.2s;
}
.flex{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}
table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}
tr:hover{
    background:#f9fafb;
}
th{
    background:#3b82f6;
    color:white;
    padding:12px;
}
td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #e5e7eb;
}
.badge{
    padding:5px 10px;
    border-radius:20px;
    background:#f1f5f9;
    font-size:12px;
    border:1px solid #e5e7eb;
}
.stat-title{
    font-size:13px;
    color:#6b7280;
}
.stat-value{
    font-size:22px;
    font-weight:bold;
    margin-top:5px;
}
.chart-box{
    margin-top:20px;
}
input, select {
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

canvas{
    max-height:300px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="logo">
    🛠 Admin ZMart | <?= $_SESSION['user'] ?>
    </div>
    <div style="display:flex;gap:10px;">
        <a class="btn" href="tambah_produk.php">+ Produk</a>
        <a class="btn" href="orders_admin.php">Orders</a>
        <a class="btn" href="admin_chat.php">
            Chat <?= $notifCount > 0 ? "($notifCount)" : "" ?>
        </a>
        <a class="logout" href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

<!-- FILTER -->
<form method="GET" class="flex" style="margin-top:10px;">
    <input type="date" name="start" value="<?= $start ?>">
    <input type="date" name="end" value="<?= $end ?>">
    <small style="color:#6b7280;">
        Tanggal hanya mempengaruhi grafik & statistik
    </small>
    <input type="text" name="search" placeholder="Cari produk..." value="<?= $search ?>">

    <select name="cat">
        <option value="">Semua</option>
        <option value="makanan" <?= $cat=='makanan'?'selected':'' ?>>Makanan</option>
        <option value="minuman" <?= $cat=='minuman'?'selected':'' ?>>Minuman</option>
        <option value="atk" <?= $cat=='atk'?'selected':'' ?>>ATK</option>
    </select>
    <button class="btn">Filter</button>
</form>

<!-- STATISTIK -->
<div class="flex" style="margin-top:20px;">
    <div class="card">
        <div class="stat-title">Total Penjualan</div>
        <div class="stat-value">Rp <?= number_format($totalSales) ?></div>
    </div>
    <div class="card">
        <div class="stat-title">Total Order</div>
        <div class="stat-value"><?= $totalOrders ?></div>
    </div>
    <div class="card">
        <div class="stat-title">Produk Terjual</div>
        <div class="stat-value"><?= $totalItems ?></div>
    </div>
</div>

<!-- GRAFIK -->
<div class="card chart-box">
    <h3 style="margin-bottom:10px;">📊 Grafik Penjualan</h3>
    <canvas id="chart"></canvas>
</div>

<!-- TABLE -->
<table>
    <tr>
        <th>Gambar</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Kategori</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

<?php while($p = mysqli_fetch_assoc($produk)) { ?>
<tr>
    <td>
        <?php if(!empty($p['image'])) { ?>
        <img src="uploads/<?= htmlspecialchars($p['image']) ?>" width="60">
        <?php } ?>
    </td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td>Rp <?= number_format($p['price']) ?></td>
    <td>
        <span class="badge"><?= ucfirst($p['category']) ?></span>
    </td>
    <td><?= $p['stock'] ?></td>
    <td>
        <a class="btn" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
        <a class="btn logout" href="hapus.php?id=<?= $p['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
    </td>
</tr>

<?php } ?>

</table>

</div>

<script>
    new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Penjualan (Rp)',
            data: <?= json_encode($data) ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.15)',
            borderWidth: 2,
            fill: true,
            tension: 0.3,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
            },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</body>
</html>