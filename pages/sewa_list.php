<?php
require 'config.php';
cek_login();
include 'templates/header.php';

$sql = "SELECT s.*, 
               p.nama_pelanggan,
               m.merk, m.tipe,
               g.nama_pegawai
        FROM sewa s
        JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
        JOIN mobil m ON s.id_mobil=m.id_mobil
        JOIN pegawai g ON s.id_pegawai=g.id_pegawai
        ORDER BY s.id_sewa DESC";
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Sewa</h4>
  <a href="sewa_form.php" class="btn btn-pink">+ Transaksi Sewa</a>
</div>

<div class="card card-soft">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Pelanggan</th>
          <th>Mobil</th>
          <th>Pegawai</th>
          <th>Tgl Sewa</th>
          <th>Rencana Kembali</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php $no=1; while($s=mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($s['nama_pelanggan']) ?></td>
          <td><?= htmlspecialchars($s['merk'].' '.$s['tipe']) ?></td>
          <td><?= htmlspecialchars($s['nama_pegawai']) ?></td>
          <td><?= $s['tanggal_sewa'] ?></td>
          <td><?= $s['tanggal_rencana_kembali'] ?></td>
          <td>Rp <?= number_format($s['total_harga'],0,',','.') ?></td>
          <td><span class="badge badge-soft"><?= $s['status_sewa'] ?></span></td>
          <td class="d-flex gap-1">
            <?php if ($s['status_sewa']==='berjalan'): ?>
              <a class="btn btn-sm btn-outline-success"
                 onclick="return confirm('Selesaikan sewa ini?')"
                 href="sewa_selesai.php?id=<?= $s['id_sewa'] ?>">Selesai</a>
            <?php endif; ?>
            <a class="btn btn-sm btn-outline-secondary" href="sewa_form.php?id=<?= $s['id_sewa'] ?>">Edit</a>
            <a class="btn btn-sm btn-outline-danger"
               onclick="return confirm('Hapus data sewa ini?')"
               href="sewa_delete.php?id=<?= $s['id_sewa'] ?>">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
