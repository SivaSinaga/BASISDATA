<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;

$mobil = [
  'merk'=>'','tipe'=>'','plat_nomor'=>'','tahun'=>'','warna'=>'',
  'harga_sewa'=>'','status'=>'tersedia'
];

if ($edit) {
    $res = mysqli_query($conn, "SELECT * FROM mobil WHERE id_mobil=$id");
    if ($res && mysqli_num_rows($res) === 1) {
        $mobil = mysqli_fetch_assoc($res);
    }
}

if (isset($_POST['simpan'])) {
    $merk  = mysqli_real_escape_string($conn, $_POST['merk']);
    $tipe  = mysqli_real_escape_string($conn, $_POST['tipe']);
    $plat  = mysqli_real_escape_string($conn, $_POST['plat_nomor']);
    $tahun = (int)$_POST['tahun'];
    $warna = mysqli_real_escape_string($conn, $_POST['warna']);
    $harga = (float)$_POST['harga_sewa'];
    $status= mysqli_real_escape_string($conn, $_POST['status']);

    if ($edit) {
        $sql = "UPDATE mobil SET merk='$merk', tipe='$tipe', plat_nomor='$plat',
                tahun=$tahun, warna='$warna', harga_sewa=$harga, status='$status'
                WHERE id_mobil=$id";
    } else {
        $sql = "INSERT INTO mobil(merk,tipe,plat_nomor,tahun,warna,harga_sewa,status)
                VALUES('$merk','$tipe','$plat',$tahun,'$warna',$harga,'$status')";
    }

    mysqli_query($conn, $sql);
    header("Location: mobil_list.php");
    exit;
}

include 'templates/header.php';
?>

<h4 class="fw-bold mb-3"><?= $edit ? 'Edit' : 'Tambah' ?> Mobil</h4>

<div class="card card-soft">
  <div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Merk</label>
        <input required name="merk" class="form-control" value="<?= htmlspecialchars($mobil['merk']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Tipe</label>
        <input required name="tipe" class="form-control" value="<?= htmlspecialchars($mobil['tipe']) ?>">
      </div>
      <div class="col-md-6">
        <label class="form-label">Plat Nomor</label>
        <input required name="plat_nomor" class="form-control" value="<?= htmlspecialchars($mobil['plat_nomor']) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Tahun</label>
        <input required type="number" name="tahun" class="form-control" value="<?= htmlspecialchars($mobil['tahun']) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label">Warna</label>
        <input required name="warna" class="form-control" value="<?= htmlspecialchars($mobil['warna']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Harga Sewa/Hari</label>
        <input required type="number" step="0.01" name="harga_sewa" class="form-control" value="<?= htmlspecialchars($mobil['harga_sewa']) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <?php foreach (['tersedia','disewa','perawatan'] as $s): ?>
            <option value="<?= $s ?>" <?= $mobil['status']===$s?'selected':''; ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" name="simpan" class="btn btn-pink">Simpan</button>
        <a href="mobil_list.php" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
