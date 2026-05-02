<?php
session_start();
include "config.php";

/* cek login */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* ambil filter */
$status = $_GET['status'] ?? 'all';

/* validasi biar aman (optional tapi bagus) */
$allowed = ['all','pending','proses','selesai'];
if(!in_array($status, $allowed)){
    $status = 'all';
}
/* query */
if($status == 'all'){
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM orders 
        WHERE user_name=? 
        ORDER BY id DESC
    ");
    mysqli_stmt_bind_param($stmt, "s", $user);
} else {
    $stmt = mysqli_prepare($conn, "
        SELECT * FROM orders 
        WHERE user_name=? AND status=? 
        ORDER BY id DESC
    ");
    mysqli_stmt_bind_param($stmt, "ss", $user, $status);
}
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Belanja</title>

<style>
body{
    font-family:'Segoe UI', sans-serif;
    background:#f1f5f9;
    padding:20px;
    line-height:1.5;
}

/* CONTAINER */
.container{
    max-width:900px;
    margin:auto;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.header h2{
    margin:0;
}
.back-link{
    text-decoration:none;
    background:#e5e7eb;
    padding:8px 12px;
    border-radius:8px;
    color:#111;
    font-size:13px;
}
.back-link:hover{
    background:#d1d5db;
}

/* CARD */
.card{
    background:white;
    border-radius:16px;
    padding:18px;
    margin-bottom:15px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
    transition:.25s ease;
}
.card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* TOP */
.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:10px;
}
.order-id{
    font-weight:bold;
}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}
.pending{ background:#fef3c7; color:#92400e; }
.proses{ background:#dbeafe; color:#1e3a8a; }
.selesai{ background:#dcfce7; color:#166534; }

.status::before{
    margin-right:5px;
}
.pending::before{ content:"⏳"; }
.proses::before{ content:"🚚"; }
.selesai::before{ content:"✔"; }

/* MIDDLE */
.middle{
    margin:10px 0;
}
.middle p{
    margin:0;
    font-size:13px;
    color:#6b7280;
}
.middle h3{
    margin:5px 0;
    color:#2563eb;
}

/* BOTTOM */
.bottom{
    display:flex;
    gap:10px;
    align-items:center;
}

/* BUTTON */
.detail-btn, .cancel-btn{
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
    transition:.2s;
}

.detail-btn{
    background:#2563eb;
    color:white;
}
.detail-btn:hover{
    background:#1d4ed8;
    transform:scale(1.03);
}

.cancel-btn{
    background:#fee2e2;
    color:#dc2626;
}
.cancel-btn:hover{
    background:#fecaca;
    transform:scale(1.03);
}

/* EMPTY */
.empty{
    background:white;
    padding:30px;
    text-align:center;
    border-radius:16px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

.filter{
    display:flex;
    gap:10px;
    margin-bottom:15px;
    flex-wrap:wrap;
}

.filter a{
    text-decoration:none;
    padding:6px 12px;
    border-radius:20px;
    background:#e5e7eb;
    color:#111;
    font-size:12px;
    transition:.2s;
}

.filter a:hover{
    background:#d1d5db;
}

.filter a.active{
    background:#2563eb;
    color:white;
}
</style>

</head>
<body>

<div class="container">

    <div class="header">
        <h2>📦 Riwayat Belanja</h2>
        <a href="dashboard_user.php" class="back-link">← Kembali</a>
    </div>
    <div class="filter">
        <a href="?status=all" class="<?= (!isset($_GET['status']) || $_GET['status']=='all') ? 'active' : '' ?>">Semua</a>
        <a href="?status=pending" class="<?= ($_GET['status'] ?? '')=='pending' ? 'active' : '' ?>">Pending</a>
        <a href="?status=proses" class="<?= ($_GET['status'] ?? '')=='proses' ? 'active' : '' ?>">Proses</a>
        <a href="?status=selesai" class="<?= ($_GET['status'] ?? '')=='selesai' ? 'active' : '' ?>">Selesai</a>
    </div>

    <?php if(mysqli_num_rows($query) == 0){ ?>

        <div class="empty">
            <h3>Belum ada transaksi</h3>
            <p>Yuk mulai belanja sekarang</p>
            <a href="dashboard_user.php" class="detail-btn">Belanja</a>
        </div>

    <?php } else { ?>

        <?php while($row = mysqli_fetch_assoc($query)) { ?>

        <div class="card">

            <div class="top">
                <div>
                    <span class="order-id">
                        Order #<?= htmlspecialchars($row['id']) ?>
                    </span><br>
                    <small>
                        <?= date('d M Y, H:i', strtotime($row['created_at'])) ?>
                    </small>
                </div>

                <div style="display:flex; gap:8px; align-items:center;">
                    <span class="status <?= $row['status'] ?>">
                        <?= strtoupper($row['status']) ?>
                    </span>
                </div>
            </div>

            <!-- MIDDLE -->
            <div class="middle">
                <p>Total Belanja</p>
                <h3>Rp <?= number_format($row['total']) ?></h3>
            </div>
            <!-- BOTTOM -->
            <div class="bottom">
                <a href="order_detail.php?id=<?= $row['id'] ?>" class="detail-btn">
                    Detail
                </a>
                <?php if($row['status'] == 'pending'){ ?>
                    <a href="cancel_order.php?id=<?= $row['id'] ?>" 
                        class="cancel-btn"
                        onclick="return confirm('Batalkan pesanan ini?')">
                        Batalkan
                    </a>
                <?php } ?>

                 <a href="reorder.php?id=<?= $row['id'] ?>" class="detail-btn">
                    🔄 Beli Lagi
                </a>
                
            </div>
        </div>

        <?php } ?>

    <?php } ?>

</div>

</body>
</html>