<?php
session_start();
include "config.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0){

    // ambil data gambar
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id=$id"));

    // hapus file gambar jika ada
    if($data && !empty($data['image'])){
        $path = "uploads/" . $data['image'];
        if(file_exists($path)){
            unlink($path);
        }
    }

    // hapus dari database
    mysqli_query($conn, "DELETE FROM products WHERE id=$id");
}

header("Location: dashboard_admin.php");
exit;