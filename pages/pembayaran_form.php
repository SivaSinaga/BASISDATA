<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;

$bayar = [
  'id_sewa'=>'',
  'tanggal_bayar'=>date('Y-m-d'),
  'metode_bayar'=>'cash',
  'jumlah_bayar'=>'',
  'status_bayar'=>'pending'
];

if ($edit) {
    $res = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_bayar=$id");
    if ($res && mysqli_num_rows($res) === 1) {
        $bayar = mysqli_fetch_assoc($res);
    }
}

$sewa = mysqli_query($conn, "
    SELECT s.id_sewa, p.nama_pelanggan
    FROM sewa s
    JOIN pelanggan p ON s.id_pelanggan=p.id_pelanggan
    ORDER BY s.id_sewa DESC
");

if (isset($_POST['simpan'])) {
    $id_sewa  = (int)$_POST['id_sewa'];
    $tgl      = $_POST['tanggal_bayar'];
    $metode   = $_POST['metode_bayar'];
    $jumlah   = (float)$_POST['jumlah_bayar'];
    $status   = $_POST['status_bayar'];

    if ($edit) {
        $sql = "UPDATE pembayaran SET id_sewa=$id_sewa, tanggal_bayar='$tgl',
                metode_bayar='$metode', jumlah_bayar=$jumlah, status_bayar='$status'
                WHERE id_bayar=$id";
    } else {
        $sql = "INSERT INTO pembayaran(id_sewa,tanggal_bayar,metode_bayar,jumlah_bayar,status_bayar)
                VALUES($id_sewa,'$tgl','$metode',$jumlah,'$status')";
    }
    mysqli_query($conn, $sql);
    header("Location: pembayaran_list.php");
    exit;
}

include 'templates/header.php';
?>

<h4 class="fw-bold mb-3"><?= $edit ? 'Edit' : 'Tambah' ?> Pembayaran</h4>

<div class="card card-soft">
  <div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Sewa - Pelanggan</label>
        <select name="id_sewa" class="form-select" required>
          <option value="">- pilih sewa -</option>
          <?php mysqli_data_seek($sewa,0); while($s=mysqli_fetch_assoc($sewa)): ?>
            <option value="<?= $s['id_sewa'] ?>" <?= $bayar['id_sewa']==$s['id_sewa']?'selected':''; ?>>
              <?= $s['id_sewa'].' - '.htmlspecialchars($s['nama_pelanggan']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Bayar</label>
        <input type="date" name="tanggal_bayar" class="form-control" required value="<?= $bayar['tanggal_bayar'] ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Metode</label>
        <select name="metode_bayar" class="form-select">
          <?php foreach (['cash','transfer','qris'] as $m): ?>
            <option value="<?= $m ?>" <?= $bayar['metode_bayar']===$m?'selected':''; ?>><?= strtoupper($m) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Jumlah Bayar</label>
        <input type="number" step="0.01" name="jumlah_bayar" class="form-control" required value="<?= htmlspecialchars($bayar['jumlah_bayar']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status_bayar" class="form-select">
          <?php foreach (['lunas','belum_lunas','pending'] as $st): ?>
            <option value="<?= $st ?>" <?= $bayar['status_bayar']===$st?'selected':''; ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" name="simpan" class="btn btn-pink">Simpan</button>
        <a href="pembayaran_list.php" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
