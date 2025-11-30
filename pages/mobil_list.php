<?php
require 'config.php';
cek_login();
include 'templates/header.php';

$data = mysqli_query($conn, "SELECT * FROM mobil ORDER BY id_mobil DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Mobil</h4>
  <a href="mobil_form.php" class="btn btn-pink">+ Tambah Mobil</a>
</div>

<div class="card card-soft">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>id</th>
          <th>Merk</th>
          <th>Tipe</th>
          <th>Plat</th>
          <th>Tahun</th>
          <th>Warna</th>
          <th>Harga/Hari</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no=1; while($m=mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($m['merk']) ?></td>
          <td><?= htmlspecialchars($m['tipe']) ?></td>
          <td><?= htmlspecialchars($m['plat_nomor']) ?></td>
          <td><?= $m['tahun'] ?></td>
          <td><?= htmlspecialchars($m['warna']) ?></td>
          <td>Rp <?= number_format($m['harga_sewa'],0,',','.') ?></td>
          <td><span class="badge badge-soft"><?= $m['status'] ?></span></td>
          <td class="d-flex gap-1">
            <a class="btn btn-sm btn-outline-secondary" href="mobil_form.php?id=<?= $m['id_mobil'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Hapus mobil ini?')"
               href="mobil_delete.php?id=<?= $m['id_mobil'] ?>">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
