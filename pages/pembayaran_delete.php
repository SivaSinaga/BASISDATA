<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page=pembayaran');
    exit;
}
$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM tb_pembayaran WHERE id_bayar=$id");
header('Location: index.php?page=pembayaran');
exit;
