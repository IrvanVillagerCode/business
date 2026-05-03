<?php
session_start();
include "config.php";

header('Content-Type: application/json');

// Cek user login
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
    exit;
}

$user = $_SESSION['user'];
$order_id = $_POST['order_id'] ?? 0;
$payment_method = $_POST['payment_method'] ?? '';
$transaction_id = $_POST['transaction_id'] ?? '';

// Validasi
if (!$order_id || !$payment_method) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

// Ambil data order
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id AND user_name='$user'");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan']);
    exit;
}

// Cek apakah sudah ada payment record
$payment_check = mysqli_query($conn, "SELECT * FROM payments WHERE order_id=$order_id");
$existing_payment = mysqli_fetch_assoc($payment_check);

$proof_image = '';

// Handle file upload jika ada
if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0 && $payment_method == 'bank_transfer') {
    $target_dir = "uploads/";
    $file_name = time() . "_proof_" . basename($_FILES["proof_image"]["name"]);
    $target_file = $target_dir . $file_name;

    // Validasi file
    $allowed = array("jpg", "jpeg", "gif", "png");
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)) {
            $proof_image = $file_name;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal upload bukti pembayaran']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Format file tidak didukung']);
        exit;
    }
}

// Tentukan status berdasarkan metode pembayaran
$payment_status = 'pending';
if ($payment_method == 'cod') {
    $payment_status = 'confirmed'; // COD langsung dikonfirmasi
} elseif ($payment_method == 'credit_card' || $payment_method == 'ewallet') {
    $payment_status = 'completed'; // Instant payment
} elseif ($payment_method == 'bank_transfer' && $proof_image) {
    $payment_status = 'pending'; // Tunggu verifikasi
}

// Insert atau update payment record
if ($existing_payment) {
    // Update existing payment
    $update_query = "UPDATE payments SET 
        payment_method = '" . mysqli_real_escape_string($conn, $payment_method) . "',
        status = '" . $payment_status . "',
        transaction_id = '" . mysqli_real_escape_string($conn, $transaction_id) . "'
        " . ($proof_image ? ", proof_image = '" . $proof_image . "'" : "") . "
        WHERE order_id = $order_id";

    if (!mysqli_query($conn, $update_query)) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
        exit;
    }
} else {
    // Insert new payment
    $insert_query = "INSERT INTO payments (order_id, user_name, amount, payment_method, status, transaction_id, proof_image) 
        VALUES ($order_id, '$user', " . $order['total'] . ", 
        '" . mysqli_real_escape_string($conn, $payment_method) . "',
        '" . $payment_status . "',
        '" . mysqli_real_escape_string($conn, $transaction_id) . "',
        '" . $proof_image . "')";

    if (!mysqli_query($conn, $insert_query)) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . mysqli_error($conn)]);
        exit;
    }
}

// Update order status berdasarkan payment status
$new_order_status = 'pending';
if ($payment_status == 'completed') {
    $new_order_status = 'diproses'; // Mulai diproses
} elseif ($payment_status == 'confirmed') {
    $new_order_status = 'diproses'; // COD mulai diproses
}

// Update order status
$order_update = "UPDATE orders SET status = '" . $new_order_status . "' WHERE id = $order_id";
mysqli_query($conn, $order_update);

// Response message
$message = '';
$redirect = '';

switch ($payment_method) {
    case 'bank_transfer':
        $message = '✅ Bukti pembayaran berhasil dikirim. Tunggu verifikasi admin (max 1x24 jam)';
        $redirect = 'order_detail.php?id=' . $order_id;
        break;
    case 'credit_card':
    case 'ewallet':
        $message = '✅ Pembayaran berhasil! Pesanan Anda sedang diproses.';
        $redirect = 'order_detail.php?id=' . $order_id;
        break;
    case 'cod':
        $message = '✅ Pesanan Anda akan dikirim. Bayar saat barang tiba.';
        $redirect = 'order_detail.php?id=' . $order_id;
        break;
    default:
        $message = '✅ Pembayaran sedang diproses';
        $redirect = 'orders_user.php';
}

echo json_encode([
    'success' => true,
    'message' => $message,
    'redirect' => $redirect,
    'payment_status' => $payment_status,
    'transaction_id' => $transaction_id
]);
