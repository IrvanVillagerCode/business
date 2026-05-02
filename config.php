<?php
$conn = mysqli_connect("localhost","root","","zmart");

if(!$conn){
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>