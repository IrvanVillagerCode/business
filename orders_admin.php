<?php
session_start();
include "config.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$orders = mysqli_query($conn, "
    SELECT * FROM orders ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Orders Admin</title>

<style>
body{font-family:Arial;background:#dbeafe;}
.container{padding:20px;}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
}

th{
    background:#3b82f6;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

a{
    text-decoration:none;
    color:#2563eb;
    font-weight:bold;
}

a.action{
    display:inline-block;
    padding:6px 10px;
    margin:2px;
    border-radius:6px;
    color:white;
    font-size:12px;
}

.detail{color:#2563eb;}

.status{
    padding:5px 10px;
    border-radius:20px;
    color:white;
    font-size:12px;
    font-weight:bold;
}
.pending{background:#fbbf24;color:#111;}
.proses{background:#3b82f6;}
.dikirim{background:#f59e0b;}
.selesai{background:#22c55e;}
</style>
</head>

<body>

<div class="container">

<h2>📦 Data Pesanan</h2>

<a href="dashboard_admin.php"
style="display:inline-block;margin-bottom:15px;padding:8px 12px;background:#3b82f6;color:white;border-radius:8px;">
← Dashboard
</a>

<table>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Total</th>
    <th>Status</th>
    <th>Tanggal</th>
    <th>Aksi</th>
</tr>

<?php while($o = mysqli_fetch_assoc($orders)){ ?>
<tr>
    <td><?= $o['id'] ?></td>
    <td><?= $o['user_name'] ?></td>
    <td>Rp <?= number_format($o['total']) ?></td>
    <td><span class="status <?= $o['status'] ?>">
        <?= strtoupper($o['status']) ?>
    </span></td>
    <td><?= $o['created_at'] ?></td>

    <td>
        <a class="detail" href="order_detail_admin.php?id=<?= $o['id'] ?>">Detail</a>

        <?php if($o['status'] == 'pending'){ ?>
            <a class="action" style="background:#3b82f6;"
               href="update_status.php?id=<?= $o['id'] ?>&status=proses">
               Proses
            </a>
        <?php } ?>

        <?php if($o['status'] == 'proses'){ ?>
            <a class="action" style="background:#f59e0b;"
               href="update_status.php?id=<?= $o['id'] ?>&status=dikirim">
               Kirim
            </a>
        <?php } ?>

        <?php if($o['status'] == 'dikirim'){ ?>
            <a class="action" style="background:#22c55e;"
               href="update_status.php?id=<?= $o['id'] ?>&status=selesai">
               Selesai
            </a>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>