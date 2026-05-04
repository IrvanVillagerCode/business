<?php
session_start();
include "config.php";

echo "<h1>🔍 DEBUG LOGIN & DATABASE</h1>";
echo "<hr>";

// Test 1: Database Connection
echo "<h2>1. Database Connection</h2>";
if ($conn) {
    echo "✅ Database Connected<br>";
} else {
    echo "❌ Database Connection Failed<br>";
}

// Test 2: Check Users Table
echo "<h2>2. Users in Database</h2>";
$result = mysqli_query($conn, "SELECT id, username, role, email FROM users");
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Email</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['role'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No users found in database";
    }
} else {
    echo "❌ Query failed: " . mysqli_error($conn);
}

// Test 3: Session Test
echo "<h2>3. Current Session</h2>";
if (isset($_SESSION['user'])) {
    echo "✅ User is logged in: " . $_SESSION['user'] . "<br>";
    echo "✅ Role: " . $_SESSION['role'] . "<br>";
    echo "✅ User ID: " . $_SESSION['user_id'] . "<br>";
} else {
    echo "❌ No user logged in<br>";
}

// Test 4: Test Login Form
echo "<h2>4. Test Login Form</h2>";
echo "
<form method='POST'>
    <label>Username: <input type='text' name='test_username' value='admin'></label><br>
    <label>Password: <input type='password' name='test_password' value='12345'></label><br>
    <button type='submit' name='test_login'>Test Login</button>
</form>
";

if (isset($_POST['test_login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['test_username']);
    $password = $_POST['test_password'];

    echo "<h3>Login Test Result:</h3>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password . "<br>";

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        echo "✅ User found in database<br>";
        echo "Database password: " . $data['password'] . "<br>";
        echo "Input password: " . $password . "<br>";

        if ($password == $data['password']) {
            echo "✅ Password matches!<br>";
            echo "✅ Role: " . $data['role'] . "<br>";
        } else {
            echo "❌ Password does not match<br>";
        }
    } else {
        echo "❌ User not found<br>";
    }
}

// Test 5: Database Structure
echo "<h2>5. Users Table Structure</h2>";
$structure = mysqli_query($conn, "DESCRIBE users");
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($col = mysqli_fetch_assoc($structure)) {
    echo "<tr>";
    echo "<td>" . $col['Field'] . "</td>";
    echo "<td>" . $col['Type'] . "</td>";
    echo "<td>" . $col['Null'] . "</td>";
    echo "<td>" . $col['Key'] . "</td>";
    echo "<td>" . $col['Default'] . "</td>";
    echo "<td>" . $col['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";
echo "<a href='login.php'>Back to Login</a> | <a href='home.php'>Go to Home</a>";
