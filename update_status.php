<?php
session_start();
include "config.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$status = $_GET['status'];

$allowed = ['proses','dikirim','selesai','dibatalkan'];
if(!in_array($status, $allowed)){
    die("Status tidak valid");
}

/* AMBIL STATUS LAMA */
$old = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT status FROM orders WHERE id=$id
"));

if(!$old){
    die("Order tidak ditemukan");
}

/* =========================
   CANCEL → BALIK STOK
========================= */
if($status == 'dibatalkan' && $old['status'] != 'dibatalkan'){

    $q = mysqli_query($conn, "
        SELECT product_id, qty 
        FROM order_items 
        WHERE order_id = $id
    ");

    if(mysqli_num_rows($q) == 0){
        die("❌ Tidak ada item untuk order_id = $id");
    }

    while($row = mysqli_fetch_assoc($q)){

        $pid = (int)$row['product_id'];
        $qty = (int)$row['qty'];

        if($pid > 0 && $qty > 0){

            $ok = mysqli_query($conn, "
                UPDATE products 
                SET stock = stock + $qty 
                WHERE id = $pid
            ");

            if(!$ok){
                die("ERROR UPDATE STOCK: " . mysqli_error($conn));
            }
        }
    }
}

/* UPDATE STATUS ORDER (INI YANG BENAR) */
mysqli_query($conn, "
    UPDATE orders 
    SET status='$status' 
    WHERE id=$id
");

header("Location: orders_admin.php");
exit;
?>