<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM pengembalian WHERE id_kembali=$id");
}
header("Location: pengembalian_list.php");
exit;
