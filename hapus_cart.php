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

/* HAPUS ITEM DARI CART */
mysqli_query($conn, "
    DELETE FROM cart 
    WHERE user_name='$user' AND product_id=$id
");

/* kembali ke cart */
header("Location: cart.php");
exit;