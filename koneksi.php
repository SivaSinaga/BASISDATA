<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "rental_mobil";

// 
$koneksi = mysqli_connect($host, $user, $password, $db);

// memeriksa koneksi
if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>
