<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* AMBIL CART DARI DATABASE */
$cart = mysqli_query($conn, "
    SELECT 
        c.product_id,
        c.qty,
        p.name,
        p.price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_name='$user'
");

if(mysqli_num_rows($cart) == 0){
    echo "<script>alert('Keranjang kosong!');window.location='cart.php';</script>";
    exit;
}

/* HITUNG TOTAL */
$total = 0;
$items = [];

while($item = mysqli_fetch_assoc($cart)){
    $subtotal = $item['price'] * $item['qty'];
    $total += $subtotal;
    $items[] = $item;
}

/* SIMPAN KE ORDERS */
mysqli_query($conn, "
    INSERT INTO orders (user_name, total, status)
    VALUES ('$user', '$total', 'pending')
");

$order_id = mysqli_insert_id($conn);

/* SIMPAN ORDER ITEMS */
foreach($items as $item){

    $product_id = $item['product_id']; 
    $price = $item['price'];
    $qty   = $item['qty'];
    $sub   = $price * $qty;

     mysqli_query($conn, "
        INSERT INTO order_items 
        (order_id, product_id, price, qty, subtotal)
        VALUES 
        ('$order_id', '$product_id', '$price', '$qty', '$sub')
    ");

    /* KURANGI STOK */
    mysqli_query($conn, "
        UPDATE products 
        SET stock = stock - $qty 
        WHERE id='$product_id'
    ");
}

/* HAPUS CART DARI DATABASE */
mysqli_query($conn, "
    DELETE FROM cart WHERE user_name='$user'
");

/* REDIRECT */
echo "<script>
alert('Checkout berhasil!');
window.location='orders_user.php';
</script>";