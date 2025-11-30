<?php
require 'config.php';
cek_login();
include 'templates/header.php';

$sql = "SELECT k.*, p.nama_pelanggan, m.merk, m.tipe
        FROM pengembalian k
        JOIN sewa s ON k.id_sewa=s.id_sewa
        JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
        JOIN mobil m ON s.id_mobil=m.id_mobil
        ORDER BY k.id_kembali DESC";
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Pengembalian</h4>
  <a href="pengembalian_form.php" class="btn btn-pink">+ Tambah Pengembalian</a>
</div>

<div class="card card-soft">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>id</th>
          <th>Pelanggan</th>
          <th>Mobil</th>
          <th>ID Sewa</th>
          <th>Tgl Kembali</th>
          <th>Keterlambatan</th>
          <th>Denda</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no=1; while($k=mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($k['nama_pelanggan']) ?></td>
          <td><?= htmlspecialchars($k['merk'].' '.$k['tipe']) ?></td>
          <td><?= $k['id_sewa'] ?></td>
          <td><?= $k['tanggal_kembali'] ?></td>
          <td><?= $k['keterlambatan_hari'] ?> hari</td>
          <td>Rp <?= number_format($k['denda'],0,',','.') ?></td>
          <td class="d-flex gap-1">
            <a class="btn btn-sm btn-outline-secondary" href="pengembalian_form.php?id=<?= $k['id_kembali'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Hapus data pengembalian ini?')"
               href="pengembalian_delete.php?id=<?= $k['id_kembali'] ?>">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
