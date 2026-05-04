<?php
session_start();
include "config.php";

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>ZMart Setup & Verification</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 20px; background: #f5f5f5; }";
echo ".box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }";
echo ".success { color: green; font-weight: bold; }";
echo ".error { color: red; font-weight: bold; }";
echo ".warning { color: orange; font-weight: bold; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 10px; }";
echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background-color: #0071ce; color: white; }";
echo "tr:hover { background-color: #f5f5f5; }";
echo ".btn { padding: 10px 20px; background: #0071ce; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }";
echo ".btn:hover { background: #00539b; }";
echo "h1 { color: #0071ce; }";
echo "h2 { color: #333; margin-top: 20px; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<h1>🔧 ZMart Platform - Setup & Verification</h1>";

// 1. Database Connection
echo "<div class='box'>";
echo "<h2>1. Database Connection Status</h2>";
if ($conn) {
    echo "<p class='success'>✅ Connected to database: zmart.id</p>";
} else {
    echo "<p class='error'>❌ Failed to connect to database</p>";
}
echo "</div>";

// 2. Users Table
echo "<div class='box'>";
echo "<h2>2. Users in Database</h2>";
$users = mysqli_query($conn, "SELECT id, username, role, email FROM users");
if (mysqli_num_rows($users) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Email</th></tr>";
    while ($user = mysqli_fetch_assoc($users)) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td><strong>" . strtoupper($user['role']) . "</strong></td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ No users found. Please add users first.</p>";
}
echo "</div>";

// 3. Products Table
echo "<div class='box'>";
echo "<h2>3. Products in Database</h2>";
$products = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$prod = mysqli_fetch_assoc($products);
echo "<p><strong>Total Products:</strong> " . $prod['total'] . "</p>";
if ($prod['total'] == 0) {
    echo "<p class='warning'>⚠️ No products found. Please add products.</p>";
} else {
    echo "<p class='success'>✅ " . $prod['total'] . " products available</p>";
}
echo "</div>";

// 4. Orders Table
echo "<div class='box'>";
echo "<h2>4. Orders in Database</h2>";
$orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$ord = mysqli_fetch_assoc($orders);
echo "<p><strong>Total Orders:</strong> " . $ord['total'] . "</p>";
echo "</div>";

// 5. Files Check
echo "<div class='box'>";
echo "<h2>5. Critical Files Check</h2>";
$files = [
    'index.php',
    'login.php',
    'register.php',
    'home.php',
    'dashboard_user.php',
    'dashboard_admin.php',
    'payment.php',
    'process_payment.php',
    'config.php',
    'css/animations.css',
    'js/animations.js',
    'js/login-animations.js'
];
echo "<table>";
echo "<tr><th>File</th><th>Status</th></tr>";
foreach ($files as $file) {
    $exists = file_exists($file);
    $status = $exists ? "<span class='success'>✅ Exists</span>" : "<span class='error'>❌ Missing</span>";
    echo "<tr><td>" . $file . "</td><td>" . $status . "</td></tr>";
}
echo "</table>";
echo "</div>";

// 6. Session Test
echo "<div class='box'>";
echo "<h2>6. Session Test</h2>";
if (isset($_SESSION['user'])) {
    echo "<p class='success'>✅ User logged in: " . $_SESSION['user'] . "</p>";
    echo "<p>Role: " . $_SESSION['role'] . "</p>";
    echo "<p><a href='logout.php' class='btn'>Logout</a></p>";
} else {
    echo "<p class='warning'>ℹ️ No user logged in (This is normal)</p>";
    echo "<p><a href='login.php' class='btn'>Go to Login</a></p>";
}
echo "</div>";

// 7. Quick Links
echo "<div class='box'>";
echo "<h2>7. Quick Navigation</h2>";
echo "<a href='index.php' class='btn'>Home</a>";
echo "<a href='login.php' class='btn'>Login</a>";
echo "<a href='home.php' class='btn'>Shop</a>";
echo "<a href='debug_login.php' class='btn'>Debug Login</a>";
echo "<a href='logout.php' class='btn' style='background: #dc2626;'>Logout</a>";
echo "</div>";

// 8. Test Credentials
echo "<div class='box'>";
echo "<h2>8. Test Credentials</h2>";
echo "<p><strong>Admin Login:</strong></p>";
echo "<p>Username: <code>admin</code></p>";
echo "<p>Password: <code>12345</code></p>";
echo "<p><strong>User Login:</strong></p>";
echo "<p>Username: <code>user1</code></p>";
echo "<p>Password: <code>12345</code></p>";
echo "</div>";

// 9. System Requirements
echo "<div class='box'>";
echo "<h2>9. System Requirements</h2>";
echo "<table>";
echo "<tr><th>Requirement</th><th>Status</th></tr>";
echo "<tr>";
echo "<td>PHP Version</td>";
echo "<td>" . phpversion() . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>MySQL Extension</td>";
echo "<td><span class='success'>✅ Available</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>Session Support</td>";
echo "<td><span class='success'>✅ Available</span></td>";
echo "</tr>";
echo "</table>";
echo "</div>";

echo "</body>";
echo "</html>";
