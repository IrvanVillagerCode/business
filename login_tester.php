<?php
session_start();
include "config.php";

// Check if we're testing with GET parameters
$test_username = $_GET['test_user'] ?? 'admin';
$test_password = $_GET['test_pass'] ?? '12345';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Login Tester</title><style>";
echo "body { font-family: Arial; padding: 20px; background: #f5f5f5; max-width: 900px; margin: 0 auto; }";
echo ".box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
echo "h1 { color: #0071ce; }";
echo "h2 { color: #333; margin-top: 20px; border-bottom: 2px solid #0071ce; padding-bottom: 10px; }";
echo ".success { color: #22c55e; font-weight: bold; padding: 10px; background: #f0fdf4; border-radius: 4px; margin: 10px 0; }";
echo ".error { color: #ef4444; font-weight: bold; padding: 10px; background: #fef2f2; border-radius: 4px; margin: 10px 0; }";
echo ".info { color: #3b82f6; padding: 10px; background: #eff6ff; border-radius: 4px; margin: 10px 0; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 10px; }";
echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #0071ce; color: white; }";
echo "button { background: #0071ce; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin: 5px; }";
echo "button:hover { background: #00539b; }";
echo ".form-group { margin: 15px 0; }";
echo "input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 100%; max-width: 300px; }";
echo "label { display: block; margin-bottom: 5px; font-weight: bold; }";
echo ".grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }";
echo ".card { background: #f0f9ff; border-left: 4px solid #0071ce; padding: 15px; border-radius: 4px; }";
echo "</style></head><body>";

echo "<div class='box'>";
echo "<h1>🧪 Login System Tester</h1>";
echo "<p>Halaman ini untuk test dan debug login system</p>";
echo "</div>";

// STEP 1: Check Database Connection
echo "<div class='box'>";
echo "<h2>Step 1: Database Connection</h2>";
if ($conn && !mysqli_connect_errno()) {
    echo "<div class='success'>✅ Database terhubung</div>";
    echo "<p>Host: <code>localhost</code></p>";
    echo "<p>Database: <code>zmart.id</code></p>";
} else {
    echo "<div class='error'>❌ Database tidak terhubung: " . mysqli_connect_error() . "</div>";
}
echo "</div>";

// STEP 2: Check Users
echo "<div class='box'>";
echo "<h2>Step 2: Check Users</h2>";
$users_result = mysqli_query($conn, "SELECT id, username, role FROM users");
$users_count = mysqli_num_rows($users_result);
echo "<div class='info'>ℹ️ Total users: <code>$users_count</code></div>";
echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Role</th></tr>";
while ($row = mysqli_fetch_assoc($users_result)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td><strong>" . $row['username'] . "</strong></td>";
    echo "<td><code>" . strtoupper($row['role']) . "</code></td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// STEP 3: Check Session Status
echo "<div class='box'>";
echo "<h2>Step 3: Current Session Status</h2>";
if (isset($_SESSION['user'])) {
    echo "<div class='success'>✅ User logged in!</div>";
    echo "<table>";
    echo "<tr><th>Session Key</th><th>Value</th></tr>";
    echo "<tr><td>user</td><td><code>" . $_SESSION['user'] . "</code></td></tr>";
    echo "<tr><td>role</td><td><code>" . $_SESSION['role'] . "</code></td></tr>";
    echo "<tr><td>user_id</td><td><code>" . $_SESSION['user_id'] . "</code></td></tr>";
    echo "</table>";
    echo "<p>";
    if ($_SESSION['role'] == 'admin') {
        echo "<a href='dashboard_admin.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;'>Go to Admin Dashboard</a>";
    } else {
        echo "<a href='dashboard_user.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;'>Go to User Dashboard</a>";
    }
    echo " <a href='logout.php' style='display: inline-block; background: #ef4444; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;'>Logout</a>";
    echo "</p>";
} else {
    echo "<div class='info'>ℹ️ No user logged in</div>";
}
echo "</div>";

// STEP 4: Manual Login Test
echo "<div class='box'>";
echo "<h2>Step 4: Manual Login Test</h2>";
echo "<form method='POST' action='login.php' style='margin-top: 20px;'>";
echo "<div class='form-group'>";
echo "<label for='username'>Username:</label>";
echo "<input type='text' id='username' name='username' value='" . htmlspecialchars($test_username) . "' required>";
echo "</div>";
echo "<div class='form-group'>";
echo "<label for='password'>Password:</label>";
echo "<input type='password' id='password' name='password' value='" . htmlspecialchars($test_password) . "' required>";
echo "</div>";
echo "<button type='submit'>🔐 Login Now</button>";
echo "</form>";

// Pre-filled buttons for quick test
echo "<div style='margin-top: 20px;'>";
echo "<h3>Quick Test Buttons:</h3>";
echo "<form method='POST' action='login.php' style='display: inline;'>";
echo "<input type='hidden' name='username' value='admin'>";
echo "<input type='hidden' name='password' value='12345'>";
echo "<button type='submit' style='background: #8b5cf6;'>Test Admin Login</button>";
echo "</form>";
echo "<form method='POST' action='login.php' style='display: inline;'>";
echo "<input type='hidden' name='username' value='user1'>";
echo "<input type='hidden' name='password' value='12345'>";
echo "<button type='submit' style='background: #06b6d4;'>Test User1 Login</button>";
echo "</form>";
echo "</div>";
echo "</div>";

// STEP 5: Debug Info
echo "<div class='box'>";
echo "<h2>Step 5: System Info</h2>";
echo "<table>";
echo "<tr><th>Property</th><th>Value</th></tr>";
echo "<tr><td>PHP Version</td><td><code>" . phpversion() . "</code></td></tr>";
echo "<tr><td>Session Path</td><td><code>" . session_save_path() . "</code></td></tr>";
echo "<tr><td>Server OS</td><td><code>" . php_uname() . "</code></td></tr>";
echo "<tr><td>Document Root</td><td><code>" . $_SERVER['DOCUMENT_ROOT'] . "</code></td></tr>";
echo "<tr><td>Current Script</td><td><code>" . $_SERVER['PHP_SELF'] . "</code></td></tr>";
echo "</table>";
echo "</div>";

// STEP 6: Navigation
echo "<div class='box'>";
echo "<h2>Quick Navigation</h2>";
echo "<p>";
echo "<a href='test_login.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>← Back to Test</a>";
echo "<a href='home.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Home</a>";
echo "<a href='login.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Login Page</a>";
if (isset($_SESSION['user'])) {
    echo "<a href='debug_session.php' style='display: inline-block; background: #f59e0b; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>Debug Session</a>";
}
echo "</p>";
echo "</div>";

echo "</body></html>";
