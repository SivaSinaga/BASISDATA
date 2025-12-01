<?php
require 'config.php';

// jika sudah login, langsung ke dashboard
if (isset($_SESSION['id_pegawai'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $hashpw   = hash('sha256', $password); // cocok dengan SHA2(...,256) di MySQL

    $sql = "SELECT * FROM pegawai WHERE username='$username' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        if ($row['password'] === $hashpw) {
            $_SESSION['id_pegawai']   = $row['id_pegawai'];
            $_SESSION['nama_pegawai'] = $row['nama_pegawai'];
            $_SESSION['username']     = $row['username'];

            header("Location: index.php");
            exit;
        }
    }

    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Rental Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-soft">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card card-soft p-4" style="max-width:420px; width:100%;">
        <h4 class="text-center fw-bold mb-3">Login Pegawai</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input required name="username" class="form-control" autocomplete="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input required type="password" name="password" class="form-control" autocomplete="current-password">
            </div>
            <button type="submit" name="login" class="btn btn-pink w-100">Login</button>
        </form>
    </div>
</div>

</body>
</html>
