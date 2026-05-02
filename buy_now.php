<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$id = (int)$_GET['id'];

/* AMBIL PRODUK */
$p = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM products WHERE id=$id
"));

if(!$p){
    die("Produk tidak ditemukan");
}

/* CEK SUDAH ADA DI CART */
$cek = mysqli_query($conn, "
    SELECT * FROM cart 
    WHERE user_name='$user' AND product_id='$id'
");

if(mysqli_num_rows($cek) > 0){
    /* kalau sudah ada → tambah qty */
    mysqli_query($conn, "
        UPDATE cart 
        SET qty = qty + 1
        WHERE user_name='$user' AND product_id='$id'
    ");
} else {
    /* kalau belum → insert */
    mysqli_query($conn, "
        INSERT INTO cart (user_name, product_id, qty)
        VALUES ('$user', '$id', 1)
    ");
}

/* LANGSUNG KE CHECKOUT */
header("Location: checkout.php");
exit;