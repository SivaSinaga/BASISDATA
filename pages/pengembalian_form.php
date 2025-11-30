<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;

$kembali = [
  'id_sewa'=>'',
  'tanggal_kembali'=>date('Y-m-d'),
  'kondisi_mobil'=>'baik',
  'keterlambatan_hari'=>0,
  'denda'=>0
];

if ($edit) {
    $res = mysqli_query($conn, "SELECT * FROM pengembalian WHERE id_kembali=$id");
    if ($res && mysqli_num_rows($res) === 1) {
        $kembali = mysqli_fetch_assoc($res);
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
    $tgl      = $_POST['tanggal_kembali'];
    $kondisi  = mysqli_real_escape_string($conn, $_POST['kondisi_mobil']);
    $telat    = (int)$_POST['keterlambatan_hari'];
    $denda    = (float)$_POST['denda'];

    if ($edit) {
        $sql = "UPDATE pengembalian SET id_sewa=$id_sewa, tanggal_kembali='$tgl',
                kondisi_mobil='$kondisi', keterlambatan_hari=$telat, denda=$denda
                WHERE id_kembali=$id";
    } else {
        $sql = "INSERT INTO pengembalian(id_sewa,tanggal_kembali,kondisi_mobil,keterlambatan_hari,denda)
                VALUES($id_sewa,'$tgl','$kondisi',$telat,$denda)";
    }
    mysqli_query($conn, $sql);
    header("Location: pengembalian_list.php");
    exit;
}

include 'templates/header.php';
?>

<h4 class="fw-bold mb-3"><?= $edit ? 'Edit' : 'Tambah' ?> Pengembalian</h4>

<div class="card card-soft">
  <div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">ID Sewa - Pelanggan</label>
        <select name="id_sewa" class="form-select" required>
          <option value="">- pilih sewa -</option>
          <?php mysqli_data_seek($sewa,0); while($s=mysqli_fetch_assoc($sewa)): ?>
            <option value="<?= $s['id_sewa'] ?>" <?= $kembali['id_sewa']==$s['id_sewa']?'selected':''; ?>>
              <?= $s['id_sewa'].' - '.htmlspecialchars($s['nama_pelanggan']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" class="form-control" required value="<?= $kembali['tanggal_kembali'] ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Keterlambatan (hari)</label>
        <input type="number" name="keterlambatan_hari" class="form-control" value="<?= htmlspecialchars($kembali['keterlambatan_hari']) ?>">
      </div>
      <div class="col-12">
        <label class="form-label">Kondisi Mobil</label>
        <textarea name="kondisi_mobil" class="form-control" rows="3"><?= htmlspecialchars($kembali['kondisi_mobil']) ?></textarea>
      </div>
      <div class="col-md-4">
        <label class="form-label">Denda</label>
        <input type="number" step="0.01" name="denda" class="form-control" value="<?= htmlspecialchars($kembali['denda']) ?>">
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" name="simpan" class="btn btn-pink">Simpan</button>
        <a href="pengembalian_list.php" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
