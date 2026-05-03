<?php
session_start();
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Tidak login']);
    exit;
}

$username = $_SESSION['user'];
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$password = $_POST['password'] ?? '';

// Handle file upload
$foto_profil = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "uploads/";
    $target_file = $target_dir . time() . "_" . basename($_FILES["foto"]["name"]);

    // Validasi tipe file
    $allowed = array("jpg", "jpeg", "gif", "png");
    $filename = basename($_FILES["foto"]["name"]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto_profil = time() . "_" . basename($_FILES["foto"]["name"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal upload foto']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Format file tidak didukung']);
        exit;
    }
}

// Build update query
$update_parts = [];
if ($email) $update_parts[] = "email = '" . mysqli_real_escape_string($conn, $email) . "'";
if ($phone) $update_parts[] = "no_hp = '" . mysqli_real_escape_string($conn, $phone) . "'";
if ($address) $update_parts[] = "alamat = '" . mysqli_real_escape_string($conn, $address) . "'";
if ($password) $update_parts[] = "password = '" . mysqli_real_escape_string($conn, $password) . "'";
if ($foto_profil) $update_parts[] = "foto_profil = '" . $foto_profil . "'";

if (empty($update_parts)) {
    echo json_encode(['success' => false, 'message' => 'Tidak ada data yang diubah']);
    exit;
}

$update_query = "UPDATE users SET " . implode(", ", $update_parts) . " WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'";

if (mysqli_query($conn, $update_query)) {
    echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui profil']);
}
