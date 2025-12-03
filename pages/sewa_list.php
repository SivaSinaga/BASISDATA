<?php
require 'config.php';
cek_login();
include 'templates/header.php';

/* UNTUK PARAMETER FILTER  */
$q      = isset($_GET['q']) ? trim($_GET['q']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'semua';
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if ($limit <= 0) $limit = 10;

$whereParts = [];

if ($q !== '') {
    $q_esc = mysqli_real_escape_string($conn, $q);
    $whereParts[] = "(p.nama_pelanggan LIKE '%$q_esc%' 
                   OR m.merk LIKE '%$q_esc%' 
                   OR m.tipe LIKE '%$q_esc%' 
                   OR g.nama_pegawai LIKE '%$q_esc%' 
                   OR s.status_sewa LIKE '%$q_esc%')";
}

if ($status !== 'semua') {
    $status_esc = mysqli_real_escape_string($conn, $status);
    $whereParts[] = "s.status_sewa = '$status_esc'";
}

$where = '';
if (!empty($whereParts)) {
    $where = 'WHERE ' . implode(' AND ', $whereParts);
}

/* QUERY DATA SEWA */
$sql = "SELECT s.*, 
               p.nama_pelanggan,
               m.merk, m.tipe,
               g.nama_pegawai
        FROM sewa s
        JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
        JOIN mobil m ON s.id_mobil=m.id_mobil
        JOIN pegawai g ON s.id_pegawai=g.id_pegawai
        $where
        ORDER BY s.id_sewa DESC";
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Sewa</h4>
  <a href="sewa_form.php" class="btn btn-pink">+ Transaksi Sewa</a>
</div>

<form class="mb-3" method="get">
  <!-- baris 1: input pencarian -->
  <div class="row g-2 mb-2">
    <div class="col-md-6">
      <input type="text" class="form-control" name="q"
             placeholder="Cari pelanggan / mobil / pegawai / status..."
             value="<?= htmlspecialchars($q) ?>">
    </div>
  </div>

  <!-- baris 2: limit + status + tombol filter -->
  <div class="row g-2 align-items-center">
    <div class="col-auto">
      <select name="limit" class="form-select">
        <option value="10" <?= $limit==10 ? 'selected' : '' ?>>10</option>
        <option value="25" <?= $limit==25 ? 'selected' : '' ?>>25</option>
        <option value="50" <?= $limit==50 ? 'selected' : '' ?>>50</option>
      </select>
    </div>

    <div class="col-auto">
      <select name="status" class="form-select">
        <option value="semua"    <?= $status=='semua'    ? 'selected' : '' ?>>Semua status</option>
        <option value="berjalan" <?= $status=='berjalan' ? 'selected' : '' ?>>Berjalan</option>
        <option value="selesai"  <?= $status=='selesai'  ? 'selected' : '' ?>>Selesai</option>
        <option value="batal"    <?= $status=='batal'    ? 'selected' : '' ?>>Batal</option>
      </select>
    </div>

    <div class="col-auto">
      <button class="btn btn-outline-secondary w-100">Filter</button>
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
          <td><?= $s['id_sewa'] ?></td>
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
