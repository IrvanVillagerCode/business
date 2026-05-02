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

/* CEK ITEM ADA DI CART */
$cek = mysqli_query($conn, "
    SELECT * FROM cart 
    WHERE user_name='$user' AND product_id=$id
");

if(mysqli_num_rows($cek) == 0){
    header("Location: cart.php");
    exit;
}

/* TAMBAH QTY */
mysqli_query($conn, "
    UPDATE cart 
    SET qty = qty + 1 
    WHERE user_name='$user' AND product_id=$id
");

/* KEMBALI KE CART */
header("Location: cart.php");
exit;