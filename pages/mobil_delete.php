<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM mobil WHERE id_mobil=$id");
}
header("Location: mobil_list.php");
exit;
