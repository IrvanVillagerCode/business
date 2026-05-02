<?php
session_start();
include "config.php";

if($_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

/* ambil semua user yang pernah chat */
$users = mysqli_query($conn,"
    SELECT DISTINCT sender 
    FROM chat_messages 
    WHERE sender != 'admin'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat Admin</title>
<style>
body{font-family:Arial;background:#f1f5f9;margin:0;}
.container{padding:20px;}

.box{
    background:white;
    padding:15px;
    border-radius:12px;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

a{
    background:#2563eb;
    color:white;
    padding:6px 12px;
    border-radius:8px;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="container">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;">

        <a href="dashboard_admin.php"
           style="background:#ef4444;color:white;padding:6px 12px;border-radius:8px;text-decoration:none;font-size:13px;">
            ← Kembali
        </a>

        <h2 style="margin:0;">💬 Chat User</h2>

        <div style="width:90px;"></div>

    </div>

    <?php while($u = mysqli_fetch_assoc($users)) { ?>
        <div class="box">
            <div>👤 <?= $u['sender'] ?></div>
            <a href="chat_room.php?user=<?= $u['sender'] ?>">Buka Chat</a>
        </div>
    <?php } ?>

</div>

</body>
</html>