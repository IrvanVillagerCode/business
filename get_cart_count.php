<?php
session_start();
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$username = $_SESSION['user'];
$query = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE user_name='$username'");
$result = mysqli_fetch_assoc($query);

echo json_encode(['count' => $result['count']]);
