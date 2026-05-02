<?php
session_start();
include "config.php";

// ambil & amankan data
$id    = (int)$_POST['id'];
$name  = mysqli_real_escape_string($conn, $_POST['name']);
$link  = mysqli_real_escape_string($conn, $_POST['link']);
$price = max(0, (int)$_POST['price']);
$stock = max(0, (int)$_POST['stock']);

// ambil gambar lama
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id=$id"));

$image = $_FILES['image']['name'];

if(!empty($image)){
    $tmp = $_FILES['image']['tmp_name'];

    // hapus gambar lama
    if($data && !empty($data['image'])){
        $old = "uploads/".$data['image'];
        if(file_exists($old)){
            unlink($old);
        }
    }

    // buat nama unik
    $newName = time() . "_" . $image;
    move_uploaded_file($tmp, "uploads/".$newName);

    mysqli_query($conn, "UPDATE products SET 
        name='$name',
        price='$price',
        link='$link',
        stock='$stock',
        image='$newName'
        WHERE id=$id");

} else {

    mysqli_query($conn, "UPDATE products SET 
        name='$name',
        price='$price',
        link='$link',
        stock='$stock'
        WHERE id=$id");
}

// redirect + notif
header("Location: dashboard_admin.php?edit=1");
exit;