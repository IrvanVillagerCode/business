<?php
session_start();
include "config.php";

$id = (int) $_GET['id'];

/* ambil status lama */
$old = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT status FROM orders WHERE id=$id
"));

if(!$old){
    die("Order tidak ditemukan");
}

if($old['status'] == 'dibatalkan'){
    die("Order sudah dibatalkan sebelumnya");
}

/* =========================
   KEMBALIKAN STOK
========================= */
$q = mysqli_query($conn, "
    SELECT product_id, qty 
    FROM order_items 
    WHERE order_id = $id
");

if(mysqli_num_rows($q) == 0){
    die("Tidak ada item order");
}

while($row = mysqli_fetch_assoc($q)){

    $pid = (int)$row['product_id'];
    $qty = (int)$row['qty'];

    mysqli_query($conn, "
        UPDATE products 
        SET stock = stock + $qty 
        WHERE id = $pid
    ");
}

/* =========================
   UPDATE STATUS ORDER
========================= */
mysqli_query($conn, "
    UPDATE orders 
    SET status='dibatalkan' 
    WHERE id=$id
");

echo "
<script>
alert('Order berhasil dibatalkan & stok dikembalikan');
window.location.href='orders_user.php';
</script>
";