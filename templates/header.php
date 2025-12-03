<?php
// template untuk header 
// ketika mau menghubungan atau menampilkan header panggil dengan 'templates/footer.php'
require_once __DIR__ . '/../config.php';
cek_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Rental Mobil</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- untuk menampilkan cssnya -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet"> <!--terhubung ke css yg buat tampilan lebih menarik-->
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-soft sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Rental Mobil </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

     <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto gap-2">
        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="mobil_list.php">Mobil</a></li>
        <li class="nav-item"><a class="nav-link" href="pelanggan_list.php">Pelanggan</a></li>
        <li class="nav-item"><a class="nav-link" href="sewa_list.php">Sewa</a></li>
        <li class="nav-item"><a class="nav-link" href="pembayaran_list.php">Pembayaran</a></li>
        <li class="nav-item"><a class="nav-link" href="pengembalian_list.php">Pengembalian</a></li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php" onclick="return confirm('Logout?')">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>  
    
<main class="flex-grow-1">
    <div class="container py-4">
