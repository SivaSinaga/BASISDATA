<?php
require 'config.php';
cek_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$edit = $id > 0;

$sewa = [
  'id_pelanggan'=>'',
  'id_mobil'=>'',
  'tanggal_sewa'=>date('Y-m-d'),
  'tanggal_rencana_kembali'=>date('Y-m-d', strtotime('+1 day')),
  'status_sewa'=>'berjalan'
];
$nama_pelanggan_terpilih = '';

if ($edit) {
    $res = mysqli_query($conn, "SELECT * FROM sewa WHERE id_sewa=$id");
    if ($res && mysqli_num_rows($res) === 1) {
        $sewa = mysqli_fetch_assoc($res);
        $resPel = mysqli_query($conn, "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan=".$sewa['id_pelanggan']." LIMIT 1");
        if ($resPel && mysqli_num_rows($resPel) === 1) {
            $rowPel = mysqli_fetch_assoc($resPel);
            $nama_pelanggan_terpilih = $rowPel['nama_pelanggan'];
        }
    }
}

$pelanggan = mysqli_query($conn, "SELECT id_pelanggan, nama_pelanggan FROM pelanggan ORDER BY nama_pelanggan");
$mobil = mysqli_query($conn, "
    SELECT id_mobil, merk, tipe, harga_sewa, status
    FROM mobil
");

if (isset($_POST['simpan'])) {
    // ambil nama pelanggan dari input teks
    $nama_pel = trim($_POST['nama_pelanggan']);
    $nama_pel = mysqli_real_escape_string($conn, $nama_pel);

    $resPel = mysqli_query($conn, "SELECT id_pelanggan FROM pelanggan WHERE nama_pelanggan='$nama_pel' LIMIT 1");
    if (!$resPel || mysqli_num_rows($resPel) === 0) {
        die("Pelanggan dengan nama tersebut tidak ditemukan. Silakan tambahkan di menu Pelanggan terlebih dahulu.");
    }
    $rowPel     = mysqli_fetch_assoc($resPel);
    
    $id_pelanggan = (int)$_POST['id_pelanggan'];
    $id_mobil     = (int)$_POST['id_mobil'];
    $tgl_sewa     = $_POST['tanggal_sewa'];
    $tgl_kembali  = $_POST['tanggal_rencana_kembali'];
    $status_sewa  = $_POST['status_sewa'];
    $id_pegawai   = $_SESSION['id_pegawai'];

    $resHarga = mysqli_query($conn, "SELECT harga_sewa FROM mobil WHERE id_mobil=$id_mobil");
    $hargaRow = mysqli_fetch_assoc($resHarga);
    $harga = (float)$hargaRow['harga_sewa'];

    $lama = (strtotime($tgl_kembali) - strtotime($tgl_sewa)) / 86400;
    if ($lama < 1) $lama = 1;
    $total = $lama * $harga;

    if ($edit) {
        $sql = "UPDATE sewa SET id_pelanggan=$id_pelanggan, id_mobil=$id_mobil,
                id_pegawai=$id_pegawai, tanggal_sewa='$tgl_sewa',
                tanggal_rencana_kembali='$tgl_kembali', total_harga=$total,
                status_sewa='$status_sewa'
                WHERE id_sewa=$id";
    } else {
        $sql = "INSERT INTO sewa(id_pelanggan,id_mobil,id_pegawai,tanggal_sewa,tanggal_rencana_kembali,total_harga,status_sewa)
                VALUES($id_pelanggan,$id_mobil,$id_pegawai,'$tgl_sewa','$tgl_kembali',$total,'berjalan')";
        mysqli_query($conn, $sql);
        mysqli_query($conn, "UPDATE mobil SET status='disewa' WHERE id_mobil=$id_mobil");
    }

    mysqli_query($conn, $sql);
    header("Location: sewa_list.php");
    exit;
}

include 'templates/header.php';
?>

<h4 class="fw-bold mb-3"><?= $edit ? 'Edit' : 'Transaksi' ?> Sewa</h4>

<div class="card card-soft">
  <div class="card-body">
    <form method="post" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Pelanggan</label>
        <input type="text"
               name="nama_pelanggan"
               class="form-control"
               list="pelangganList"
               placeholder="ketik nama pelanggan..."
               value="<?= htmlspecialchars($nama_pelanggan_terpilih) ?>"
               required>
        <datalist id="pelangganList">
          <?php if ($pelanggan): ?>
            <?php while($p = mysqli_fetch_assoc($pelanggan)): ?>
              <option value="<?= htmlspecialchars($p['nama_pelanggan']) ?>"></option>
            <?php endwhile; ?>
          <?php endif; ?>
        </datalist>
        <div class="form-text">
          Nama harus sudah terdaftar di menu Pelanggan.
        </div>
      </div>
      
      <div class="col-md-6">
        <label class="form-label">Mobil</label>
        <select name="id_mobil" class="form-select" required>
          <option value="">- pilih mobil -</option>
          <?php mysqli_data_seek($mobil,0); while($m=mysqli_fetch_assoc($mobil)): ?>
            <option value="<?= $m['id_mobil'] ?>" <?= $sewa['id_mobil']==$m['id_mobil']?'selected':''; ?>>
              <?= htmlspecialchars($m['merk'].' '.$m['tipe'].' (Rp '.number_format($m['harga_sewa'],0,',','.').'/hari)') ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Tanggal Sewa</label>
        <input type="date" name="tanggal_sewa" class="form-control" required value="<?= $sewa['tanggal_sewa'] ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label">Rencana Kembali</label>
        <input type="date" name="tanggal_rencana_kembali" class="form-control" required value="<?= $sewa['tanggal_rencana_kembali'] ?>">
      </div>
      <?php if ($edit): ?>
      <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status_sewa" class="form-select">
          <?php foreach (['berjalan','selesai','batal'] as $st): ?>
            <option value="<?= $st ?>" <?= $sewa['status_sewa']===$st?'selected':''; ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
      <div class="col-12 d-flex gap-2">
        <button type="submit" name="simpan" class="btn btn-pink">Simpan</button>
        <a href="sewa_list.php" class="btn btn-outline-secondary">Kembali</a>
      </div>
    </form>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
