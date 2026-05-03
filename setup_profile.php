<?php
include "config.php";

// Array of SQL queries to execute
$queries = [
    "ALTER TABLE `users` ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `role`",
    "ALTER TABLE `users` ADD COLUMN `no_hp` VARCHAR(15) DEFAULT NULL AFTER `email`",
    "ALTER TABLE `users` ADD COLUMN `alamat` TEXT DEFAULT NULL AFTER `no_hp`",
    "ALTER TABLE `users` ADD COLUMN `foto_profil` VARCHAR(255) DEFAULT NULL AFTER `alamat`"
];

// Check if payments table exists, if not create it
$payment_table_query = "CREATE TABLE IF NOT EXISTS `payments` (
    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `order_id` int(11) NOT NULL,
    `user_name` varchar(100) DEFAULT NULL,
    `amount` int(11) DEFAULT NULL,
    `payment_method` varchar(50) DEFAULT 'transfer_bank',
    `status` varchar(50) DEFAULT 'pending',
    `transaction_id` varchar(100) DEFAULT NULL,
    `proof_image` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp DEFAULT NULL ON UPDATE current_timestamp()
)";

$success_count = 0;
$errors = [];

echo "Menambahkan kolom profil ke tabel users...<br><br>";

foreach ($queries as $query) {
    if (mysqli_query($conn, $query)) {
        echo "✅ Query berhasil: " . substr($query, 0, 50) . "...<br>";
        $success_count++;
    } else {
        // Kolom mungkin sudah ada, lanjutkan saja
        if (strpos(mysqli_error($conn), "Duplicate column") !== false) {
            echo "⚠️ Kolom sudah ada, skip...<br>";
            $success_count++;
        } else {
            echo "❌ Error: " . mysqli_error($conn) . "<br>";
            $errors[] = mysqli_error($conn);
        }
    }
}

echo "<br>================================<br>";
echo "<h3>Membuat Tabel Pembayaran...</h3>";
if (mysqli_query($conn, $payment_table_query)) {
    echo "✅ Tabel payments berhasil dibuat/diupdate<br>";
} else {
    $err = mysqli_error($conn);
    if (strpos($err, "already exists") === false) {
        echo "❌ Error: " . $err . "<br>";
        $errors[] = $err;
    } else {
        echo "⚠️ Tabel payments sudah ada<br>";
    }
}

echo "================================<br><br>";

if ($success_count == count($queries)) {
    echo "✅ Semua kolom profil sudah ditambahkan!<br>";
} else {
    echo "Beberapa kolom mungkin sudah ada sebelumnya.<br>";
}
echo "================================<br><br>";

// Tampilkan struktur tabel
echo "<h3>Struktur Tabel Users Saat Ini:</h3>";
$result = mysqli_query($conn, "DESCRIBE users");
if ($result) {
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
    echo "<tr style='background:#0071ce;color:white;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
}

echo "<br><br>";

// Tampilkan struktur tabel payments
echo "<h3>Struktur Tabel Payments:</h3>";
$result2 = mysqli_query($conn, "DESCRIBE payments");
if ($result2) {
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse;'>";
    echo "<tr style='background:#0071ce;color:white;'><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = mysqli_fetch_assoc($result2)) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
}

echo "<br><br><a href='dashboard_admin.php' style='background:#0071ce;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;'>Kembali ke Dashboard</a>";
