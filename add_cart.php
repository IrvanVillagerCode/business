<?php
session_start();
include "config.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: dashboard_user.php");
    exit;
}

$user = $_SESSION['user'];
$id   = (int)$_GET['id'];

/* CEK PRODUK */
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$product = mysqli_stmt_get_result($stmt)->fetch_assoc();

if(!$product){
    echo "<script>alert('Produk tidak ditemukan');window.location='dashboard_user.php';</script>";
    exit;
}

/* CEK STOK */
if($product['stock'] <= 0){
    echo "<script>alert('Stok habis!');window.location='detail_produk.php?id=$id';</script>";
    exit;
}

/* CEK CART */
$stmt = mysqli_prepare($conn, "
    SELECT qty FROM cart 
    WHERE user_name=? AND product_id=?
");
mysqli_stmt_bind_param($stmt, "si", $user, $id);
mysqli_stmt_execute($stmt);
$check = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($check) > 0){

    $row = mysqli_fetch_assoc($check);
    $new_qty = $row['qty'] + 1;

    /* BATASI DENGAN STOK */
    if($new_qty > $product['stock']){
        echo "<script>alert('Qty melebihi stok!');window.location='detail_produk.php?id=$id';</script>";
        exit;
    }

    /* UPDATE QTY */
    $stmt = mysqli_prepare($conn, "
        UPDATE cart 
        SET qty=? 
        WHERE user_name=? AND product_id=?
    ");
    mysqli_stmt_bind_param($stmt, "isi", $new_qty, $user, $id);
    mysqli_stmt_execute($stmt);

} else {

    /* INSERT */
    $stmt = mysqli_prepare($conn, "
        INSERT INTO cart (user_name, product_id, qty)
        VALUES (?, ?, 1)
    ");
    mysqli_stmt_bind_param($stmt, "si", $user, $id);
    mysqli_stmt_execute($stmt);
}

/* REDIRECT */
if(isset($_GET['buy'])){
    header("Location: cart.php");
} else {
    header("Location: detail_produk.php?id=$id&success=1");
}
exit;