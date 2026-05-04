<?php
include "config.php";

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>Setup Database - ZMart</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; background: #f5f5f5; }";
echo ".box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }";
echo ".success { color: #22c55e; font-weight: bold; }";
echo ".error { color: #ef4444; font-weight: bold; }";
echo ".warning { color: #f59e0b; font-weight: bold; }";
echo ".info { color: #3b82f6; font-weight: bold; }";
echo "h1 { color: #0071ce; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='box'>";
echo "<h1>🔧 Setup Database ZMart</h1>";

// Function to check if column exists
function column_exists($conn, $table, $column)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    return mysqli_num_rows($result) > 0;
}

// Step 1: Add columns to users table
echo "<h2>Step 1: Menambahkan kolom ke tabel users</h2>";

$columns_to_add = [
    'email' => "VARCHAR(100) DEFAULT NULL",
    'no_hp' => "VARCHAR(15) DEFAULT NULL",
    'alamat' => "TEXT DEFAULT NULL",
    'foto_profil' => "VARCHAR(255) DEFAULT NULL"
];

$success_count = 0;
foreach ($columns_to_add as $column_name => $column_type) {
    if (column_exists($conn, 'users', $column_name)) {
        echo "<p class='warning'>⚠️ Kolom '$column_name' sudah ada, skip...</p>";
        $success_count++;
    } else {
        $alter_query = "ALTER TABLE `users` ADD COLUMN `$column_name` $column_type AFTER `password`";
        if (mysqli_query($conn, $alter_query)) {
            echo "<p class='success'>✅ Kolom '$column_name' berhasil ditambahkan</p>";
            $success_count++;
        } else {
            echo "<p class='error'>❌ Error menambahkan kolom '$column_name': " . mysqli_error($conn) . "</p>";
        }
    }
}

// Step 2: Create payments table if not exists
echo "<h2>Step 2: Membuat tabel payments</h2>";

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

if (mysqli_query($conn, $payment_table_query)) {
    echo "<p class='success'>✅ Tabel payments berhasil dibuat/diupdate</p>";
} else {
    echo "<p class='error'>❌ Error: " . mysqli_error($conn) . "</p>";
}

// Step 3: Create uploads directory if not exists
echo "<h2>Step 3: Membuat direktori uploads</h2>";

if (!is_dir('uploads')) {
    if (mkdir('uploads', 0755, true)) {
        echo "<p class='success'>✅ Direktori uploads berhasil dibuat</p>";
    } else {
        echo "<p class='warning'>⚠️ Direktori uploads mungkin sudah ada</p>";
    }
} else {
    echo "<p class='info'>ℹ️ Direktori uploads sudah ada</p>";
}

// Step 4: Display database structure
echo "<h2>Step 4: Struktur Tabel Users</h2>";

$describe = mysqli_query($conn, "DESCRIBE users");
echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; margin-top: 10px;'>";
echo "<tr style='background: #0071ce; color: white;'>";
echo "<th>Field</th>";
echo "<th>Type</th>";
echo "<th>Null</th>";
echo "<th>Key</th>";
echo "<th>Default</th>";
echo "<th>Extra</th>";
echo "</tr>";

while ($row = mysqli_fetch_assoc($describe)) {
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

// Step 5: Check users in database
echo "<h2>Step 5: Users dalam Database</h2>";

$users = mysqli_query($conn, "SELECT id, username, role, email FROM users");
echo "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; margin-top: 10px;'>";
echo "<tr style='background: #0071ce; color: white;'>";
echo "<th>ID</th>";
echo "<th>Username</th>";
echo "<th>Role</th>";
echo "<th>Email</th>";
echo "</tr>";

$user_count = 0;
while ($row = mysqli_fetch_assoc($users)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td><strong>" . strtoupper($row['role']) . "</strong></td>";
    echo "<td>" . ($row['email'] ?? 'Belum diisi') . "</td>";
    echo "</tr>";
    $user_count++;
}

echo "</table>";

if ($user_count == 0) {
    echo "<p class='warning'>⚠️ Tidak ada users di database. Silakan tambahkan users terlebih dahulu.</p>";
} else {
    echo "<p class='success'>✅ $user_count user(s) ditemukan</p>";
}

// Summary
echo "<h2>📋 Summary</h2>";
echo "<div class='box' style='background: #ecfdf5; border-left: 4px solid #22c55e;'>";
echo "<p class='success'>✅ Setup database selesai!</p>";
echo "<p>Semua kolom dan tabel sudah siap digunakan.</p>";
echo "<p><strong>Langkah selanjutnya:</strong></p>";
echo "<ul>";
echo "<li>Test login: <a href='login.php'>Ke halaman login</a></li>";
echo "<li>Kembali ke home: <a href='home.php'>Ke home page</a></li>";
echo "</ul>";
echo "</div>";

echo "</body>";
echo "</html>";
?>
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
    echo "<tr style='background:#0071ce;color:white;'>
        <th>Field</th>
        <th>Type</th>
        <th>Null</th>
        <th>Key</th>
        <th>Default</th>
        <th>Extra</th>
    </tr>";
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
    echo "<tr style='background:#0071ce;color:white;'>
        <th>Field</th>
        <th>Type</th>
        <th>Null</th>
        <th>Key</th>
        <th>Default</th>
        <th>Extra</th>
    </tr>";
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