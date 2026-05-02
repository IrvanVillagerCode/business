<?php
session_start();
include "config.php";

/* cek login */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$order_id = (int)($_GET['id'] ?? 0);

/* ambil item dari order */
$items = mysqli_query($conn, "
    SELECT product_id, qty 
    FROM order_items 
    WHERE order_id = $order_id
");

/* cek kalau tidak ada item */
if(mysqli_num_rows($items) == 0){
    die("Order tidak punya item atau data tidak ditemukan");
}

/* masukkan ke cart */
while($item = mysqli_fetch_assoc($items)){

    $product_id = (int)$item['product_id'];
    $qty = (int)$item['qty'];

    /* cek cart (prepared biar aman) */
    $check = mysqli_prepare($conn, "
        SELECT qty FROM cart 
        WHERE user_name=? AND product_id=?
    ");
    mysqli_stmt_bind_param($check, "si", $user, $product_id);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if(mysqli_num_rows($result) > 0){

        /* update qty */
        $update = mysqli_prepare($conn, "
            UPDATE cart 
            SET qty = qty + ? 
            WHERE user_name=? AND product_id=?
        ");
        mysqli_stmt_bind_param($update, "isi", $qty, $user, $product_id);
        mysqli_stmt_execute($update);

    } else {

        /* insert baru */
        $insert = mysqli_prepare($conn, "
            INSERT INTO cart (user_name, product_id, qty) 
            VALUES (?, ?, ?)
        ");
        mysqli_stmt_bind_param($insert, "sii", $user, $product_id, $qty);
        mysqli_stmt_execute($insert);
    }
}

/* redirect */
header("Location: cart.php");
exit;