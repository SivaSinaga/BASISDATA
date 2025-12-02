<?php
require 'config.php';
cek_login();
include 'templates/header.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$where = '';
if ($q !== '') {
    $q_esc = mysqli_real_escape_string($conn, $q);
    $where = "WHERE p.nama_pelanggan LIKE '%$q_esc%' 
              OR m.merk LIKE '%$q_esc%' 
              OR m.tipe LIKE '%$q_esc%'";
}

$sql = "SELECT k.*, p.nama_pelanggan, m.merk, m.tipe
        FROM pengembalian k
        JOIN sewa s ON k.id_sewa=s.id_sewa
        JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
        JOIN mobil m ON s.id_mobil=m.id_mobil
        $where
        ORDER BY k.id_kembali DESC"; // join
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Pengembalian</h4>
  <a href="pengembalian_form.php" class="btn btn-pink">+ Tambah Pengembalian</a>
</div>

<form class="mb-3" method="get">
  <div class="row g-2">
    <div class="col-md-4">
      <input type="text" class="form-control" name="q" placeholder="Cari pelanggan / mobil..." value="<?= htmlspecialchars($q) ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-outline-secondary w-100">Cari</button>
    </div>
  </div>
</form>

<div class="card card-soft">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>ID</th>
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
          <td><?= $k['id_kembali'] ?></td>
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


