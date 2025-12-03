<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // ambil id_mobil
    $res = mysqli_query($conn, "SELECT id_mobil FROM sewa WHERE id_sewa=$id");
    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        $id_mobil = (int)$row['id_mobil'];

        mysqli_query($conn, "UPDATE sewa SET status_sewa='selesai' WHERE id_sewa=$id");
        mysqli_query($conn, "UPDATE mobil SET status='tersedia' WHERE id_mobil=$id_mobil");
    }
}
header("Location: sewa_list.php");
exit;
