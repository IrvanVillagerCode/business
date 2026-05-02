<?php
include 'config.php';

if(isset($_POST['register'])){
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $pass = md5($_POST['password']);

  mysqli_query($conn,"INSERT INTO users (nama,email,password)
  VALUES('$nama','$email','$pass')");

  echo "<script>alert('Berhasil daftar'); window.location='index.php';</script>";
}
?>

<form method="post">
  <input name="nama" placeholder="Nama"><br>
  <input name="email" placeholder="Email"><br>
  <input type="password" name="password" placeholder="Password"><br>
  <button name="register">Daftar</button>
</form>