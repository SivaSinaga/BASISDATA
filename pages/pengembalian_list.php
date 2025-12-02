<?php
require 'config.php';
cek_login();
include 'templates/header.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$where = '';
if ($q !== '') {
    $q_esc = mysqli_real_escape_string($conn, $q);
    $where = "WHERE p.nama_pelanggan LIKE '%$q_esc%' 
              OR b.metode_bayar LIKE '%$q_esc%' 
              OR b.status_bayar LIKE '%$q_esc%'";
}

$sql = "SELECT b.*, p.nama_pelanggan
        FROM pembayaran b
        JOIN sewa s ON b.id_sewa=s.id_sewa
        JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
        $where
        ORDER BY b.id_bayar DESC";
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Pembayaran</h4>
  <a href="pembayaran_form.php" class="btn btn-pink">+ Tambah Pembayaran</a>
</div>

<form class="mb-3" method="get">
  <div class="row g-2">
    <div class="col-md-4">
      <input type="text" class="form-control" name="q" placeholder="Cari pelanggan / metode / status..." value="<?= htmlspecialchars($q) ?>">
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
    <th>ID Pembayaran</th>
    <th>Pelanggan</th>
    <th>ID Sewa</th>
    <th>Tanggal Bayar</th>
    <th>Metode</th>
    <th>Jumlah</th>
    <th>Status</th>
    <th>Aksi</th>
  </tr>
</thead>
<tbody>
<?php while($b = mysqli_fetch_assoc($data)): ?>
  <tr>
    <!-- pakai id_bayar, BUKAN id_pembayaran -->
    <td><?= $b['id_bayar'] ?></td>
    <td><?= htmlspecialchars($b['nama_pelanggan']) ?></td>
    <td><?= $b['id_sewa'] ?></td>
    <td><?= $b['tanggal_bayar'] ?></td>
    <td><?= htmlspecialchars($b['metode_bayar']) ?></td>
    <td>Rp <?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td>
    <td><span class="badge badge-soft"><?= $b['status_bayar'] ?></span></td>
    <td class="d-flex gap-1">
      <a class="btn btn-sm btn-outline-secondary"
         href="pembayaran_form.php?id=<?= $b['id_bayar'] ?>">Edit</a>
      <a class="btn btn-sm btn-outline-danger"
         onclick="return confirm('Hapus pembayaran ini?')"
         href="pembayaran_delete.php?id=<?= $b['id_bayar'] ?>">Hapus</a>
    </td>
  </tr>
<?php endwhile; ?>
</tbody>

    </table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>

