<?php
session_start();
include "config.php";

/* CEK LOGIN */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

/* CEK INPUT MESSAGE */
if(!isset($_POST['message']) || trim($_POST['message']) == ''){
    echo "empty";
    exit;
}

$message = mysqli_real_escape_string($conn, $_POST['message']);

/* SIMPAN CHAT */
$insert = mysqli_query($conn,"
    INSERT INTO chat_messages (sender, receiver, message, is_read)
    VALUES ('$user', 'admin', '$message', 0)
");

if($insert){
    echo "ok";
} else {
    echo "error";
}
?>