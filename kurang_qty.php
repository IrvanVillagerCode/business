<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$id   = (int)$_GET['id'];

/* CEK ITEM DI CART */
$cek = mysqli_query($conn, "
    SELECT qty FROM cart 
    WHERE user_name='$user' AND product_id=$id
");

if(mysqli_num_rows($cek) == 0){
    header("Location: cart.php");
    exit;
}

$data = mysqli_fetch_assoc($cek);
$qty  = (int)$data['qty'];

/* KURANGI QTY */
if($qty > 1){
    mysqli_query($conn, "
        UPDATE cart 
        SET qty = qty - 1 
        WHERE user_name='$user' AND product_id=$id
    ");
} else {
    /* kalau qty 1 → hapus */
    mysqli_query($conn, "
        DELETE FROM cart 
        WHERE user_name='$user' AND product_id=$id
    ");
}

/* kembali */
header("Location: cart.php");
exit;