<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM sewa WHERE id_sewa=$id");
}
header("Location: sewa_list.php");
exit;
