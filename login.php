<?php
session_start();
include "config.php";

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    if($data){

        if($password == $data['password']){

            session_regenerate_id(true);

            $_SESSION['user'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            if($data['role'] == 'admin'){
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
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
<html>
<head>
<title>Login ZMart</title>
<style>
body{
    margin:0;
    font-family:Arial;
    background:#dbeafe;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    color:#1f2937;
}
.box{
    background:#ffffff;
    padding:40px;
    border-radius:15px;
    width:320px;
    box-shadow:0 10px 25px rgba(59,130,246,0.15);
    border:1px solid #bfdbfe;
}
input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #bfdbfe;
    outline:none;
    background:#f8fafc;
}
button{
    width:100%;
    padding:10px;
    margin:10px 0;
    background:#3b82f6;
    border:none;
    color:white;
    font-weight:bold;
    cursor:pointer;
    border-radius:8px;
    transition:.3s;
}
button:hover{
    background:#2563eb;
}

.error{
    background:#fee2e2;
    color:#991b1b;
    padding:10px;
    font-size:13px;
    border-radius:8px;
    border:1px solid #fecaca;
}
</style>
</head>
<body>

<div class="box">
<h2>Login</h2>

<?php if($error){ ?>
<div class="error"><?= $error ?></div>
<?php } ?>

<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit" name="login">Login</button>
</form>

</div>

</body>
</html>