<?php
session_start();
include "config.php";

// Cek user login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ambil order ID dari URL
$order_id = $_GET['id'] ?? 0;

if ($order_id == 0) {
    die("Order tidak ditemukan");
}

// Ambil data order
$order_query = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id AND user_name='$user'");
$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    die("Order tidak ditemukan");
}

// Cek apakah sudah ada payment record
$payment_check = mysqli_query($conn, "SELECT * FROM payments WHERE order_id=$order_id");
$existing_payment = mysqli_fetch_assoc($payment_check);

// Generate transaction ID
$transaction_id = "TRX-" . $order_id . "-" . time();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💳 Pembayaran - ZMart</title>
    <link rel="stylesheet" href="css/animations.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #0071ce, #00539b);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: #f0f0f0;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-section h3 {
            color: #0071ce;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: bold;
        }

        .info-value {
            color: #333;
            text-align: right;
        }

        .payment-methods {
            margin-bottom: 20px;
        }

        .method-option {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .method-option:hover {
            border-color: #0071ce;
            background: #f9f9f9;
        }

        .method-option.active {
            border-color: #0071ce;
            background: #eff6ff;
        }

        .method-option input[type="radio"] {
            margin-right: 15px;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .method-info {
            flex: 1;
        }

        .method-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .method-desc {
            font-size: 13px;
            color: #666;
        }

        .method-icon {
            font-size: 24px;
            margin-right: 15px;
        }

        .bank-details {
            background: #fff3cd;
            border-left: 4px solid #ffc220;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            display: none;
        }

        .bank-details.show {
            display: block;
        }

        .bank-details h4 {
            color: #856404;
            margin-bottom: 10px;
        }

        .bank-account {
            background: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-family: monospace;
        }

        .bank-account-num {
            color: #0071ce;
            font-weight: bold;
        }

        .upload-section {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            display: none;
        }

        .upload-section.show {
            display: block;
        }

        .upload-section h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: block;
            padding: 10px 15px;
            background: #0071ce;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            transition: background 0.3s;
        }

        .file-input-label:hover {
            background: #00539b;
        }

        .file-name {
            margin-top: 10px;
            font-size: 13px;
            color: #666;
        }

        .amount-total {
            background: linear-gradient(135deg, #0071ce, #00539b);
            color: white;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-total .label {
            font-size: 13px;
            opacity: 0.9;
        }

        .amount-total .value {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #0071ce;
            color: white;
        }

        .btn-primary:hover {
            background: #00539b;
        }

        .btn-secondary {
            background: #ddd;
            color: #333;
        }

        .btn-secondary:hover {
            background: #ccc;
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #cce5ff;
            border-left: 4px solid #0071ce;
            color: #004085;
        }

        .alert-success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>💳 Pembayaran Pesanan</h1>
            <p>ZMart Payment Gateway Demo</p>
        </div>

        <?php if ($existing_payment && $existing_payment['status'] == 'completed'): ?>
            <div class="alert alert-success">
                <strong>✅ Pembayaran Sudah Dikonfirmasi!</strong><br>
                Pesanan Anda telah dibayar pada <?php echo date('d M Y H:i', strtotime($existing_payment['updated_at'])); ?>
            </div>
        <?php endif; ?>

        <div class="payment-card">
            <div class="card-header">
                <h2>📋 Ringkasan Pesanan</h2>
            </div>
            <div class="card-body">
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">Order ID:</span>
                        <span class="info-value">#<?php echo $order['id']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value"><?php echo date('d M Y H:i', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Order:</span>
                        <span class="info-value" style="color: #ffc220; font-weight: bold;"><?php echo ucfirst($order['status']); ?></span>
                    </div>
                </div>

                <div class="amount-total">
                    <div class="label">Total Pembayaran</div>
                    <div class="value">Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <div class="payment-card">
            <div class="card-header">
                <h2>💰 Pilih Metode Pembayaran</h2>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>ℹ️ Mode Demo:</strong> Ini adalah sistem pembayaran demo untuk testing. Silakan pilih metode pembayaran berikut.
                </div>

                <form id="paymentForm">
                    <div class="payment-methods">
                        <!-- Bank Transfer -->
                        <label class="method-option active" onclick="selectMethod(this, 'bank_transfer')">
                            <input type="radio" name="payment_method" value="bank_transfer" checked>
                            <div class="method-icon">🏦</div>
                            <div class="method-info">
                                <div class="method-name">Transfer Bank</div>
                                <div class="method-desc">Transfer ke rekening ZMart</div>
                            </div>
                        </label>

                        <!-- E-Wallet -->
                        <label class="method-option" onclick="selectMethod(this, 'ewallet')">
                            <input type="radio" name="payment_method" value="ewallet">
                            <div class="method-icon">📱</div>
                            <div class="method-info">
                                <div class="method-name">E-Wallet</div>
                                <div class="method-desc">GCash, PayMaya, OVO, DANA</div>
                            </div>
                        </label>

                        <!-- Credit Card -->
                        <label class="method-option" onclick="selectMethod(this, 'credit_card')">
                            <input type="radio" name="payment_method" value="credit_card">
                            <div class="method-icon">💳</div>
                            <div class="method-info">
                                <div class="method-name">Kartu Kredit</div>
                                <div class="method-desc">Visa, Mastercard, Amex</div>
                            </div>
                        </label>

                        <!-- Cash On Delivery -->
                        <label class="method-option" onclick="selectMethod(this, 'cod')">
                            <input type="radio" name="payment_method" value="cod">
                            <div class="method-icon">🚚</div>
                            <div class="method-info">
                                <div class="method-name">Bayar di Tempat</div>
                                <div class="method-desc">Bayar saat barang tiba</div>
                            </div>
                        </label>
                    </div>

                    <!-- Bank Details Section -->
                    <div id="bankDetails" class="bank-details show">
                        <h4>🏦 Rekening Transfer</h4>
                        <div class="bank-account">
                            <div>Bank: <strong>BCA</strong></div>
                            <div>No. Rekening: <span class="bank-account-num">1234567890</span></div>
                            <div>Atas Nama: <strong>PT. ZMART INDO</strong></div>
                        </div>
                        <p style="font-size:13px;color:#666;margin-top:10px;">Mohon transfer sesuai nominal pesanan. Pembayaran akan diverifikasi dalam 1x24 jam.</p>
                    </div>

                    <!-- Upload Proof Section -->
                    <div id="uploadSection" class="upload-section show">
                        <h4>📸 Upload Bukti Pembayaran</h4>
                        <div class="file-input-wrapper">
                            <input type="file" id="proofFile" name="proof_image" accept="image/*">
                            <label for="proofFile" class="file-input-label">Pilih Bukti Pembayaran</label>
                        </div>
                        <div class="file-name" id="fileName">Belum ada file dipilih</div>
                    </div>

                    <input type="hidden" id="orderId" value="<?php echo $order_id; ?>">
                    <input type="hidden" id="transactionId" value="<?php echo $transaction_id; ?>">

                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">Kembali</button>
                        <button type="button" class="btn btn-primary" onclick="submitPayment()">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update file name
        document.getElementById('proofFile')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            document.getElementById('fileName').textContent = fileName ? fileName : 'Belum ada file dipilih';
        });

        function selectMethod(element, method) {
            // Remove active class from all
            document.querySelectorAll('.method-option').forEach(opt => {
                opt.classList.remove('active');
            });

            // Add active class to selected
            element.classList.add('active');

            // Show/hide details based on method
            const bankDetails = document.getElementById('bankDetails');
            const uploadSection = document.getElementById('uploadSection');

            if (method === 'bank_transfer') {
                bankDetails?.classList.add('show');
                uploadSection?.classList.add('show');
            } else if (method === 'cod') {
                bankDetails?.classList.remove('show');
                uploadSection?.classList.remove('show');
            } else {
                bankDetails?.classList.remove('show');
                uploadSection?.classList.remove('show');
            }
        }

        function submitPayment() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;
            const orderId = document.getElementById('orderId').value;
            const transactionId = document.getElementById('transactionId').value;
            const proofFile = document.getElementById('proofFile').files[0];

            // Validasi
            if (method === 'bank_transfer' && !proofFile) {
                alert('Silakan upload bukti pembayaran terlebih dahulu!');
                return;
            }

            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('payment_method', method);
            formData.append('transaction_id', transactionId);
            if (proofFile) {
                formData.append('proof_image', proofFile);
            }

            fetch('process_payment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = 'orders_user.php';
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses pembayaran');
                });
        }
    </script>
    <script src="js/animations.js"></script>
</body>

</html>