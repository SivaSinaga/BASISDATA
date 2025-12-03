<?php 
require 'config.php';
cek_login();
include 'templates/header.php';
/*filter*/
$q      = isset($_GET['q']) ? trim($_GET['q']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'semua';
$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if ($limit <= 0) $limit = 10;

$whereParts = [];

if ($q !== '') {
    $q_esc = mysqli_real_escape_string($conn, $q);
    $whereParts[] = "(merk LIKE '%$q_esc%' 
                  OR tipe LIKE '%$q_esc%' 
                  OR plat_nomor LIKE '%$q_esc%' 
                  OR warna LIKE '%$q_esc%')";
}

if ($status !== 'semua') {
    $status_esc = mysqli_real_escape_string($conn, $status);
    $whereParts[] = "status = '$status_esc'";
}

$where = '';
if (!empty($whereParts)) {
    $where = 'WHERE ' . implode(' AND ', $whereParts);
}

/* query data mobil*/
$sql  = "SELECT * FROM mobil $where ORDER BY id_mobil ASC LIMIT $limit";
$data = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">Data Mobil</h4>
  <a href="mobil_form.php" class="btn btn-pink">+ Tambah Mobil</a>
</div>

<!-- FORM FILTER -->
<form class="mb-3" method="get">
  <!-- baris 1: pencarian -->
  <div class="row g-2 mb-2">
    <div class="col-md-4">
      <input type="text"
             class="form-control"
             name="q"
             placeholder="Cari merk / tipe / plat..."
             value="<?= htmlspecialchars($q) ?>">
    </div>
  </div>

  <!-- baris 2: limit + status + tombol filter -->
  <div class="row g-2 align-items-center">
    <!-- jumlah data -->
    <div class="col-auto">
      <select name="limit" class="form-select">
        <option value="10" <?= $limit==10 ? 'selected' : '' ?>>10</option>
        <option value="25" <?= $limit==25 ? 'selected' : '' ?>>25</option>
        <option value="50" <?= $limit==50 ? 'selected' : '' ?>>50</option>
      </select>
    </div>

    <!-- status mobil -->
    <div class="col-auto">
      <select name="status" class="form-select">
        <option value="semua"     <?= $status=='semua'     ? 'selected' : '' ?>>Semua status</option>
        <option value="tersedia"  <?= $status=='tersedia'  ? 'selected' : '' ?>>Tersedia</option>
        <option value="disewa"    <?= $status=='disewa'    ? 'selected' : '' ?>>Disewa</option>
        <option value="perawatan" <?= $status=='perawatan' ? 'selected' : '' ?>>Perawatan</option>
      </select>
    </div>

    <!-- tombol filter -->
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
      <?php while($m = mysqli_fetch_assoc($data)): ?>
        <tr>
            
          <td><?= $m['id_mobil'] ?></td>
          <td><?= htmlspecialchars($m['merk']) ?></td>
          <td><?= htmlspecialchars($m['tipe']) ?></td>
          <td><?= htmlspecialchars($m['plat_nomor']) ?></td>
          <td><?= $m['tahun'] ?></td>
          <td><?= htmlspecialchars($m['warna']) ?></td>
          <td>Rp <?= number_format($m['harga_sewa'], 0, ',', '.') ?></td>
          <td><span class="badge badge-soft"><?= $m['status'] ?></span></td>
          <td class="d-flex gap-1">
            <a class="btn btn-sm btn-outline-secondary"
               href="mobil_form.php?id=<?= $m['id_mobil'] ?>">Edit</a>
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
