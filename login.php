<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

$error = "";

// PROCESS LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi";
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

        if (!$query) {
            $error = "Error: " . mysqli_error($conn);
        } else {
            $data = mysqli_fetch_assoc($query);

            if (!$data) {
                $error = "Username tidak ditemukan";
            } else {
                // Check password - try plain text first, then hashed
                $password_match = false;
                if ($password === $data['password']) {
                    $password_match = true;
                } else if (password_verify($password, $data['password'])) {
                    $password_match = true;
                }

                if ($password_match) {
                    // PASSWORD CORRECT - SET SESSION
                    $_SESSION['user'] = $data['username'];
                    $_SESSION['role'] = strtolower($data['role']);
                    $_SESSION['user_id'] = $data['id'];

                    // REDIRECT BASED ON ROLE
                    if (strtolower($data['role']) == 'admin') {
                        header("Location: dashboard_admin.php");
                        exit;
                    } else if (strtolower($data['role']) == 'user') {
                        header("Location: dashboard_user.php");
                        exit;
                    } else {
                        $error = "Role tidak dikenali: " . $data['role'];
                    }
                } else {
                    $error = "Password salah";
                }
            }
        }
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

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username Anda"
                        required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative; display: flex;">
                        <input type="password"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required
                            style="flex: 1; padding-right: 40px;">
                        <button type="button"
                            class="toggle-password"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer; font-size: 18px; padding: 0;">
                            👁️
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-btn">
                    Login Sekarang
                </button>

                <div class="login-links">
                    <a href="#forgot-password">Lupa Password?</a>
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
        // Password toggle button
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('.toggle-password');
            const passwordInput = document.getElementById('password');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function() {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.textContent = '🙈';
                    } else {
                        passwordInput.type = 'password';
                        this.textContent = '👁️';
                    }
                });
            }
        });
    </script>
</body>

</html>