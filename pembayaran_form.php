<?php
$id_bayar = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$data = [
    'id_sewa' => '',
    'tanggal_bayar' => date('Y-m-d'),
    'metode_bayar' => 'cash',
    'jumlah_bayar' => '',
    'status_bayar' => 'belum_lunas'
];

if ($id_bayar) {
    $q = mysqli_query($conn, "SELECT * FROM tb_pembayaran WHERE id_bayar=$id_bayar");
    if ($row = mysqli_fetch_assoc($q)) {
        $data = $row;
    }
}

$sqlSewa = "SELECT s.id_sewa,
                   p.nama AS nama_pelanggan,
                   m.merk, m.tipe, m.plat_nomor,
                   s.total_harga
            FROM tb_penyewaan s
            JOIN tb_pelanggan p ON s.id_pelanggan = p.id_pelanggan
            JOIN tb_mobil m ON s.id_mobil = m.id_mobil
            ORDER BY s.id_sewa DESC";
$sewas = mysqli_query($conn, $sqlSewa);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sewa      = (int)$_POST['id_sewa'];
    $tanggal_bayar= mysqli_real_escape_string($conn, $_POST['tanggal_bayar']);
    $metode_bayar = mysqli_real_escape_string($conn, $_POST['metode_bayar']);
    $jumlah_bayar = (float)$_POST['jumlah_bayar'];
    $status_bayar = mysqli_real_escape_string($conn, $_POST['status_bayar']);

    if ($id_bayar) {
        $sql = "UPDATE tb_pembayaran SET
                    id_sewa=$id_sewa,
                    tanggal_bayar='$tanggal_bayar',
                    metode_bayar='$metode_bayar',
                    jumlah_bayar=$jumlah_bayar,
                    status_bayar='$status_bayar'
                WHERE id_bayar=$id_bayar";
    } else {
        $sql = "INSERT INTO tb_pembayaran
                    (id_sewa, tanggal_bayar, metode_bayar, jumlah_bayar, status_bayar)
                VALUES
                    ($id_sewa, '$tanggal_bayar', '$metode_bayar', $jumlah_bayar, '$status_bayar')";
    }

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php?page=pembayaran');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan data: ".mysqli_error($conn)."</div>";
    }
}
?>

<h3 class="mb-3"><?php echo $id_bayar ? 'Edit Pembayaran' : 'Tambah Pembayaran'; ?></h3>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Pilih Sewa</label>
        <select name="id_sewa" class="form-select" required>
          <option value="">-- Pilih Transaksi Sewa --</option>
          <?php while($s = mysqli_fetch_assoc($sewas)): ?>
            <?php
              $label = 'ID#'.$s['id_sewa'].' - '.$s['nama_pelanggan'].' - '.$s['merk'].' '.$s['tipe'].' ('.$s['plat_nomor'].') - Rp '.number_format($s['total_harga'],0,',','.');
            ?>
            <option value="<?php echo $s['id_sewa']; ?>" <?php echo $data['id_sewa']==$s['id_sewa']?'selected':''; ?>>
              <?php echo htmlspecialchars($label); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Tanggal Bayar</label>
          <input type="date" name="tanggal_bayar" class="form-control" required value="<?php echo htmlspecialchars($data['tanggal_bayar']); ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Metode Bayar</label>
          <select name="metode_bayar" class="form-select">
            <option value="cash" <?php echo $data['metode_bayar']=='cash'?'selected':''; ?>>Cash</option>
            <option value="transfer" <?php echo $data['metode_bayar']=='transfer'?'selected':''; ?>>Transfer</option>
            <option value="ewallet" <?php echo $data['metode_bayar']=='ewallet'?'selected':''; ?>>E-Wallet</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Jumlah Bayar</label>
          <input type="number" name="jumlah_bayar" class="form-control" required value="<?php echo htmlspecialchars($data['jumlah_bayar']); ?>">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Status Pembayaran</label>
        <select name="status_bayar" class="form-select">
          <option value="lunas" <?php echo $data['status_bayar']=='lunas'?'selected':''; ?>>Lunas</option>
          <option value="belum_lunas" <?php echo $data['status_bayar']=='belum_lunas'?'selected':''; ?>>Belum Lunas</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="index.php?page=pembayaran" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
