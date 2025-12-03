<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM tb_pembayaran WHERE id_bayar=$id");
}
header('Location: index.php?page=pembayaran');
exit;
