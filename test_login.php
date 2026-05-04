    <?php
    session_start();
    include "config.php";

    ?><!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>✅ Login Test - ZMart</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: Arial; background: #f5f5f5; padding: 20px; }
            .container { max-width: 800px; margin: 0 auto; }
            .box { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
            h1 { color: #0071ce; margin-bottom: 10px; }
            h2 { color: #333; margin: 15px 0 10px 0; font-size: 18px; }
            .success { color: #22c55e; font-weight: bold; }
            .error { color: #ef4444; font-weight: bold; }
            .info { color: #3b82f6; font-weight: bold; }
            .form-group { margin: 15px 0; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
            button { background: #0071ce; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
            button:hover { background: #00539b; }
            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
            .card { background: #f0f9ff; border-left: 4px solid #0071ce; padding: 15px; border-radius: 4px; }
            .card h3 { color: #0071ce; margin-bottom: 10px; }
            code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background: #0071ce; color: white; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="box">
                <h1>🧪 Login System Test</h1>
                <p>Halaman ini untuk testing login system ZMart</p>
            </div>

            <?php
            // Check 1: Database connection
            echo "<div class='box'>";
            echo "<h2>1️⃣ Database Connection</h2>";
            if ($conn && !mysqli_connect_errno()) {
                echo "<p class='success'>✅ Database Connected</p>";
            } else {
                echo "<p class='error'>❌ Database Connection Failed</p>";
                echo "<p>" . mysqli_connect_error() . "</p>";
            }
            echo "</div>";

            // Check 2: Users in Database
            echo "<div class='box'>";
            echo "<h2>2️⃣ Users in Database</h2>";
            $users_result = mysqli_query($conn, "SELECT id, username, role FROM users");
            if ($users_result) {
                $user_count = mysqli_num_rows($users_result);
                if ($user_count > 0) {
                    echo "<p class='success'>✅ Found " . $user_count . " user(s)</p>";
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
                } else {
                    echo "<p class='error'>❌ No users found in database</p>";
                }
            } else {
                echo "<p class='error'>❌ Query failed: " . mysqli_error($conn) . "</p>";
            }
            echo "</div>";

            // Check 3: Session Status
            echo "<div class='box'>";
            echo "<h2>3️⃣ Current Session</h2>";
            if (isset($_SESSION['user'])) {
                echo "<p class='success'>✅ User logged in</p>";
                echo "<table>";
                echo "<tr><th>Session Key</th><th>Value</th></tr>";
                echo "<tr><td>user</td><td>" . $_SESSION['user'] . "</td></tr>";
                echo "<tr><td>role</td><td><code>" . $_SESSION['role'] . "</code></td></tr>";
                echo "<tr><td>user_id</td><td>" . $_SESSION['user_id'] . "</td></tr>";
                echo "</table>";
                echo "<p style='margin-top: 15px;'><a href='logout.php' style='color: #ef4444; text-decoration: none;'>🚪 Logout</a></p>";
            } else {
                echo "<p class='info'>ℹ️ No user logged in (normal if you just opened this page)</p>";
            }
            echo "</div>";

            // Check 4: Test Credentials
            echo "<div class='box'>";
            echo "<h2>4️⃣ Test Credentials</h2>";
            echo "<div class='grid'>";
            echo "<div class='card'>";
            echo "<h3>👨‍💼 Admin Account</h3>";
            echo "<p><strong>Username:</strong> <code>admin</code></p>";
            echo "<p><strong>Password:</strong> <code>12345</code></p>";
            echo "</div>";
            echo "<div class='card'>";
            echo "<h3>👤 User Account</h3>";
            echo "<p><strong>Username:</strong> <code>user1</code></p>";
            echo "<p><strong>Password:</strong> <code>12345</code></p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            // Check 5: Quick Test Form
            echo "<div class='box'>";
            echo "<h2>5️⃣ Quick Login Test</h2>";
            echo "<form method='POST' action='login.php'>";
            echo "<div class='form-group'>";
            echo "<label>Username:</label>";
            echo "<input type='text' name='username' value='admin' required>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<label>Password:</label>";
            echo "<input type='password' name='password' value='12345' required>";
            echo "</div>";
            echo "<button type='submit' name='login'>🔐 Test Login</button>";
            echo "</form>";
            echo "</div>";

            // Check 6: File Status
            echo "<div class='box'>";
            echo "<h2>6️⃣ Critical Files Status</h2>";
            $files = [
                'login.php',
                'index.php',
                'dashboard_admin.php',
                'dashboard_user.php',
                'home.php',
                'config.php',
                'css/animations.css',
                'js/animations.js'
            ];
            echo "<table>";
            echo "<tr><th>File</th><th>Status</th></tr>";
            foreach ($files as $file) {
                $exists = file_exists($file);
                $status = $exists ? "<span class='success'>✅ OK</span>" : "<span class='error'>❌ Missing</span>";
                echo "<tr><td>$file</td><td>$status</td></tr>";
            }
            echo "</table>";
            echo "</div>";

            // Check 7: Navigation
            echo "<div class='box'>";
            echo "<h2>7️⃣ Quick Navigation</h2>";
            echo "<p>";
            echo "<a href='home.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>🏠 Home</a>";
            echo "<a href='login.php' style='display: inline-block; background: #0071ce; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>🔐 Login</a>";
            if (isset($_SESSION['user'])) {
                if (strtolower($_SESSION['role']) == 'admin') {
                    echo "<a href='dashboard_admin.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>👨‍💼 Dashboard Admin</a>";
                } else {
                    echo "<a href='dashboard_user.php' style='display: inline-block; background: #22c55e; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; margin: 5px;'>👤 Dashboard User</a>";
                }
            }
            echo "</p>";
            echo "</div>";
        </div>
       </body>
    </html>
