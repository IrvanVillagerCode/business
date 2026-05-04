<?php
session_start();
include "config.php";

// Show current session
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Session</title><style>";
echo "body { font-family: Arial; padding: 20px; background: #f5f5f5; }";
echo ".box { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; }";
echo "h2 { color: #0071ce; }";
echo "code { background: #f0f0f0; padding: 5px 10px; border-radius: 3px; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 10px; }";
echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #0071ce; color: white; }";
echo ".success { color: #22c55e; font-weight: bold; }";
echo ".error { color: #ef4444; font-weight: bold; }";
echo "</style></head><body>";

echo "<div class='box'>";
echo "<h2>🔍 Session Debug Info</h2>";

// Session info
echo "<h3>Session Variables:</h3>";
echo "<table>";
echo "<tr><th>Key</th><th>Value</th></tr>";

if (empty($_SESSION)) {
    echo "<tr><td colspan='2' style='text-align: center;'><span class='error'>❌ No session data</span></td></tr>";
} else {
    foreach ($_SESSION as $key => $value) {
        echo "<tr>";
        echo "<td><code>$key</code></td>";
        echo "<td>" . (is_array($value) ? json_encode($value) : htmlspecialchars($value)) . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

// Database check
echo "<h3>Database Connection:</h3>";
if ($conn && !mysqli_connect_errno()) {
    echo "<p class='success'>✅ Connected</p>";
} else {
    echo "<p class='error'>❌ Connection Failed: " . mysqli_connect_error() . "</p>";
}

// Users in DB
echo "<h3>Users in Database:</h3>";
$users = mysqli_query($conn, "SELECT id, username, role FROM users");
echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Role</th></tr>";
while ($row = mysqli_fetch_assoc($users)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['username'] . "</td>";
    echo "<td><code>" . strtoupper($row['role']) . "</code></td>";
    echo "</tr>";
}
echo "</table>";

// Navigation
echo "<h3>Quick Links:</h3>";
echo "<p>";
echo "<a href='test_login.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Test Login</a>";
echo "<a href='login.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Go to Login</a>";
if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] == 'admin') {
        echo "<a href='dashboard_admin.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Go to Admin Dashboard</a>";
    } else {
        echo "<a href='dashboard_user.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Go to User Dashboard</a>";
    }
    echo "<a href='logout.php' style='display: inline-block; background: #ef4444; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Logout</a>";
} else {
    echo "<p><span class='error'>❌ Not logged in</span></p>";
}
echo "</p>";

echo "</div>";

echo "</body></html>";
