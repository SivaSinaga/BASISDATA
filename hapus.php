<?php
  include 'koneksi.php';

  $id_sewa = $_POST['id_sewa'];
  $sql_hapus_peminjaman : "delete from penyewaan where id_sewa = '$id_sewa' ";
  mysqli_query($koneksi, $sql_hapus_peminjaman);

?>


    
    


