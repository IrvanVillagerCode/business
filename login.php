<?php
session_start();
include "config.php";

$error = "";
$success = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {

        if ($password == $data['password']) {

            session_regenerate_id(true);

            $_SESSION['user'] = $data['username'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['user_id'] = $data['id'];

            $success = "Login berhasil! Mengarahkan...";

            if ($data['role'] == 'admin') {
                header("Location: dashboard_admin.php", true, 303);
            } else {
                header("Location: dashboard_user.php", true, 303);
            }
            exit;
        } else {
            $error = "Password salah";
        }
    } else {
        $error = "Username tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ZMart - E-Commerce Platform</title>
    <link rel="stylesheet" href="css/login-animations.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-form-container {
                margin-left: 10px;
                margin-right: 10px;
            }

            .login-form-container h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container gradient-bg">
        <!-- Animated Background -->
        <div class="particles-container"></div>

        <!-- Login Form -->
        <div class="login-form-container">
            <h1>🛒 ZMart</h1>
            <p>Selamat datang kembali</p>

            <?php if ($error) { ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php } ?>

            <form method="POST" class="login-form" onsubmit="return handleLoginSubmit(event)">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username Anda"
                        autocomplete="username"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative; display: flex;">
                        <input type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                            required
                            style="flex: 1; padding-right: 40px;">
                        <button type="button"
                            class="toggle-password"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer; font-size: 18px; padding: 0;">
                            👁️
                        </button>
                    </div>
                </div>

                <button type="submit" name="login" class="login-btn">
                    Login Sekarang
                </button>

                <div class="login-links">
                    <a href="#forgot-password" onclick="showNotification('Fitur akan segera tersedia')">Lupa Password?</a>
                    <a href="register.php">Daftar Akun</a>
                </div>
            </form>

            <div class="register-link">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/login-animations.js"></script>
    <script>
        function handleLoginSubmit(event) {
            // Prevent default submission for validation
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (username === '' || password === '') {
                showNotification('Semua field harus diisi', 'warning');
                return false;
            }

            return true;
        }

        function showNotification(message, type = 'info') {
            window.showLoginNotification(message, type);
        }

        // Debug: Log when script loads
        console.log('Login page loaded with animations');
    </script>
</body>

</html>