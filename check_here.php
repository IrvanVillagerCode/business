<?php
session_start();
include "config.php";

$current_user = $_SESSION['user'] ?? null;
$current_role = $_SESSION['role'] ?? null;

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>⚡ Check Here First</title><style>";
echo "* { margin: 0; padding: 0; box-sizing: border-box; }";
echo "body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }";
echo ".container { background: white; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 600px; width: 100%; padding: 40px; }";
echo "h1 { color: #667eea; margin-bottom: 10px; font-size: 32px; }";
echo ".subtitle { color: #666; margin-bottom: 30px; font-size: 16px; }";
echo ".status { background: #f0f9ff; border-left: 4px solid #667eea; padding: 15px; border-radius: 6px; margin-bottom: 20px; }";
echo ".status-text { font-weight: 600; margin: 5px 0; }";
echo ".status-value { font-family: monospace; background: white; padding: 8px; border-radius: 4px; display: inline-block; margin-top: 5px; }";
echo ".button { display: block; background: #667eea; color: white; padding: 15px 20px; border-radius: 6px; text-decoration: none; text-align: center; margin: 10px 0; font-weight: 600; transition: background 0.3s; }";
echo ".button:hover { background: #5568d3; }";
echo ".button-secondary { background: #f59e0b; }";
echo ".button-secondary:hover { background: #d97706; }";
echo ".button-danger { background: #ef4444; }";
echo ".button-danger:hover { background: #dc2626; }";
echo ".button-success { background: #22c55e; }";
echo ".button-success:hover { background: #16a34a; }";
echo ".grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }";
echo ".icon { font-size: 24px; margin-right: 8px; }";
echo "@media (max-width: 600px) { .grid { grid-template-columns: 1fr; } h1 { font-size: 24px; } }";
echo "</style></head><body>";

echo "<div class='container'>";

// HEADER
echo "<h1><span class='icon'>⚡</span>ZMart Login System</h1>";
echo "<div class='subtitle'>Testing & Verification Page</div>";

// CURRENT STATUS
echo "<div class='status'>";
if ($current_user) {
    echo "<div class='status-text'>✅ You are logged in!</div>";
    echo "<div class='status-text'>User: <div class='status-value'>$current_user</div></div>";
    echo "<div class='status-text'>Role: <div class='status-value'>" . strtoupper($current_role) . "</div></div>";
} else {
    echo "<div class='status-text'>⚪ Not logged in</div>";
    echo "<div class='status-text'>Status: <div class='status-value'>Anonymous</div></div>";
}
echo "</div>";

// ACTION BUTTONS
if ($current_user) {
    // User is logged in
    echo "<h2 style='margin: 20px 0 15px 0; font-size: 18px; color: #333;'>You are logged in</h2>";

    if ($current_role == 'admin') {
        echo "<a href='dashboard_admin.php' class='button button-success'><span class='icon'>👨‍💼</span> Go to Admin Dashboard</a>";
    } else if ($current_role == 'user') {
        echo "<a href='dashboard_user.php' class='button button-success'><span class='icon'>👤</span> Go to User Dashboard</a>";
    }

    echo "<a href='debug_session.php' class='button button-secondary'><span class='icon'>🔍</span> Debug Session Info</a>";
    echo "<a href='logout.php' class='button button-danger'><span class='icon'>🚪</span> Logout</a>";
} else {
    // User is not logged in
    echo "<h2 style='margin: 20px 0 15px 0; font-size: 18px; color: #333;'>Quick Test</h2>";

    echo "<div class='grid'>";
    echo "<form method='POST' action='login.php'>";
    echo "<input type='hidden' name='username' value='admin'>";
    echo "<input type='hidden' name='password' value='12345'>";
    echo "<button type='submit' class='button' style='border: none; cursor: pointer;'><span class='icon'>👨‍💼</span> Test Admin Login</button>";
    echo "</form>";

    echo "<form method='POST' action='login.php'>";
    echo "<input type='hidden' name='username' value='user1'>";
    echo "<input type='hidden' name='password' value='12345'>";
    echo "<button type='submit' class='button button-secondary' style='border: none; cursor: pointer;'><span class='icon'>👤</span> Test User Login</button>";
    echo "</form>";
    echo "</div>";

    echo "<h2 style='margin: 30px 0 15px 0; font-size: 18px; color: #333;'>Full Testing</h2>";

    echo "<a href='login_diagnostic.php' class='button button-secondary'><span class='icon'>🔬</span> Run Full Diagnostic</a>";
    echo "<a href='login_tester.php' class='button button-secondary'><span class='icon'>🧪</span> Login Tester</a>";
    echo "<a href='login.php' class='button button-secondary'><span class='icon'>🔐</span> Go to Login Page</a>";
}

// FOOTER
echo "<div style='margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #666; font-size: 13px; text-align: center;'>";
echo "<p>If you're having issues, start with:</p>";
echo "<code style='background: #f3f4f6; padding: 10px; border-radius: 4px; display: block; margin-top: 10px;'>login_diagnostic.php</code>";
echo "</div>";

echo "</div>";

echo "</body></html>";
