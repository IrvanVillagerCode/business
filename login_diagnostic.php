<?php

/**
 * LOGIN DIAGNOSTIC - This tests the login process step by step
 */
session_start();
include "config.php";

// Simulate a login test
$test_username = 'admin';
$test_password = '12345';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Login Diagnostic</title><style>";
echo "body { font-family: Arial; padding: 20px; background: #f5f5f5; max-width: 900px; margin: 0 auto; }";
echo ".box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }";
echo "h1, h2 { color: #0071ce; }";
echo ".success { background: #f0fdf4; color: #22c55e; padding: 10px; border-radius: 4px; border-left: 4px solid #22c55e; margin: 10px 0; font-weight: bold; }";
echo ".error { background: #fef2f2; color: #ef4444; padding: 10px; border-radius: 4px; border-left: 4px solid #ef4444; margin: 10px 0; font-weight: bold; }";
echo ".warning { background: #fffbeb; color: #d97706; padding: 10px; border-radius: 4px; border-left: 4px solid #d97706; margin: 10px 0; font-weight: bold; }";
echo "code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }";
echo ".step { margin: 20px 0; padding: 15px; background: #f9fafb; border-left: 4px solid #3b82f6; }";
echo ".step-title { font-weight: bold; color: #3b82f6; font-size: 18px; margin-bottom: 10px; }";
echo "table { width: 100%; border-collapse: collapse; margin-top: 10px; }";
echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #0071ce; color: white; }";
echo "</style></head><body>";

echo "<div class='box'><h1>🔍 Login System Diagnostic</h1>";
echo "<p>Testing login process step by step...</p></div>";

// STEP 1
echo "<div class='step'>";
echo "<div class='step-title'>Step 1: Database Connection</div>";
if ($conn) {
    echo "<div class='success'>✅ Database connected successfully</div>";

    // Test query
    $test_query = mysqli_query($conn, "SELECT 1");
    if ($test_query) {
        echo "<div class='success'>✅ Query execution works</div>";
    } else {
        echo "<div class='error'>❌ Query failed: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='error'>❌ Database connection failed: " . mysqli_connect_error() . "</div>";
    exit;
}
echo "</div>";

// STEP 2
echo "<div class='step'>";
echo "<div class='step-title'>Step 2: Test User Lookup</div>";
echo "<p>Looking for user: <code>$test_username</code></p>";

$lookup_query = mysqli_query($conn, "SELECT id, username, role, password FROM users WHERE username='$test_username'");
if (!$lookup_query) {
    echo "<div class='error'>❌ Query error: " . mysqli_error($conn) . "</div>";
} else {
    $user_found = mysqli_fetch_assoc($lookup_query);
    if ($user_found) {
        echo "<div class='success'>✅ User found!</div>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>ID</td><td>" . $user_found['id'] . "</td></tr>";
        echo "<tr><td>Username</td><td><code>" . $user_found['username'] . "</code></td></tr>";
        echo "<tr><td>Role</td><td><code>" . $user_found['role'] . "</code></td></tr>";
        echo "<tr><td>Password (stored)</td><td><code>" . substr($user_found['password'], 0, 10) . "...</code></td></tr>";
        echo "</table>";
    } else {
        echo "<div class='error'>❌ User not found in database</div>";
    }
}
echo "</div>";

// STEP 3
echo "<div class='step'>";
echo "<div class='step-title'>Step 3: Test Password Verification</div>";
echo "<p>Testing password: <code>$test_password</code></p>";

if ($user_found) {
    $stored_pass = $user_found['password'];

    // Test plain text match
    if ($test_password === $stored_pass) {
        echo "<div class='success'>✅ Password matches (plain text)</div>";
    } else {
        echo "<div class='warning'>⚠️ Password doesn't match plain text, trying hashed...</div>";

        // Test hashed
        if (password_verify($test_password, $stored_pass)) {
            echo "<div class='success'>✅ Password matches (hashed with password_verify)</div>";
        } else {
            echo "<div class='error'>❌ Password doesn't match (neither plain nor hashed)</div>";
        }
    }
}
echo "</div>";

// STEP 4
echo "<div class='step'>";
echo "<div class='step-title'>Step 4: Simulate Session Creation</div>";
if ($user_found) {
    $_SESSION['user'] = $user_found['username'];
    $_SESSION['role'] = strtolower($user_found['role']);
    $_SESSION['user_id'] = $user_found['id'];

    echo "<div class='success'>✅ Session variables set</div>";
    echo "<table>";
    echo "<tr><th>Session Key</th><th>Value</th></tr>";
    echo "<tr><td>\$_SESSION['user']</td><td><code>" . $_SESSION['user'] . "</code></td></tr>";
    echo "<tr><td>\$_SESSION['role']</td><td><code>" . $_SESSION['role'] . "</code></td></tr>";
    echo "<tr><td>\$_SESSION['user_id']</td><td><code>" . $_SESSION['user_id'] . "</code></td></tr>";
    echo "</table>";
}
echo "</div>";

// STEP 5
echo "<div class='step'>";
echo "<div class='step-title'>Step 5: Verify Session Persistence</div>";
if (isset($_SESSION['user'])) {
    echo "<div class='success'>✅ Session variables are accessible</div>";
    echo "<p>Session User: <code>" . $_SESSION['user'] . "</code></p>";
    echo "<p>Session Role: <code>" . $_SESSION['role'] . "</code></p>";
    echo "<p>Session User ID: <code>" . $_SESSION['user_id'] . "</code></p>";
} else {
    echo "<div class='error'>❌ Session variables are not accessible</div>";
}
echo "</div>";

// STEP 6
echo "<div class='step'>";
echo "<div class='step-title'>Step 6: Test Dashboard Redirect Logic</div>";
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    $role_check = strtolower($_SESSION['role']);
    echo "<p>Role check: <code>" . $role_check . "</code></p>";

    if ($role_check == 'admin') {
        echo "<div class='success'>✅ Role check passed: User should go to dashboard_admin.php</div>";
        echo "<p><a href='dashboard_admin.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;'>Try Dashboard Admin</a></p>";
    } else if ($role_check == 'user') {
        echo "<div class='success'>✅ Role check passed: User should go to dashboard_user.php</div>";
        echo "<p><a href='dashboard_user.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;'>Try Dashboard User</a></p>";
    } else {
        echo "<div class='error'>❌ Role not recognized: " . $role_check . "</div>";
    }
} else {
    echo "<div class='error'>❌ No session data available</div>";
}
echo "</div>";

// STEP 7
echo "<div class='step'>";
echo "<div class='step-title'>Step 7: All Users in Database</div>";
$all_users = mysqli_query($conn, "SELECT id, username, role FROM users");
echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Role</th></tr>";
while ($user = mysqli_fetch_assoc($all_users)) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td><code>" . $user['username'] . "</code></td>";
    echo "<td><code>" . strtoupper($user['role']) . "</code></td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Navigation
echo "<div class='box'>";
echo "<h2>What to do next:</h2>";
if (isset($_SESSION['user'])) {
    if ($role_check == 'admin') {
        echo "<p>1. Click the button above to go to Admin Dashboard</p>";
        echo "<p>2. Or try <a href='login.php'>login directly</a> using admin / 12345</p>";
    } else if ($role_check == 'user') {
        echo "<p>1. Click the button above to go to User Dashboard</p>";
        echo "<p>2. Or try <a href='login.php'>login directly</a> using user1 / 12345</p>";
    }
} else {
    echo "<p><a href='login_tester.php'>Go to Login Tester</a></p>";
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
}
echo "</div>";

echo "</body></html>";
