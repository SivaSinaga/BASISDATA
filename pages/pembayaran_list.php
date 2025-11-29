<?php
$sql = "SELECT b.*, 
               p.nama AS nama_pelanggan,
               m.merk, m.tipe, m.plat_nomor
        FROM tb_pembayaran b
        JOIN tb_penyewaan s ON b.id_sewa = s.id_sewa
        JOIN tb_pelanggan p ON s.id_pelanggan = p.id_pelanggan
        JOIN tb_mobil m ON s.id_mobil = m.id_mobil
        ORDER BY b.tanggal_bayar DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Pembayaran</h3>
    <a href="index.php?page=pembayaran_form" class="btn btn-primary">+ Tambah Pembayaran</a>
</div>

<div class="card shadow-sm">
  <div class="card-body">
    <?php if (mysqli_num_rows($result) == 0): ?>
      <p class="text-muted mb-0">Belum ada data pembayaran.</p>
    <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Tgl Bayar</th>
            <th>Pelanggan</th>
            <th>Mobil</th>
            <th>Metode</th>
            <th>Jumlah</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['tanggal_bayar']; ?></td>
            <td><?php echo htmlspecialchars($row['nama_pelanggan']); ?></td>
            <td><?php echo htmlspecialchars($row['merk'].' '.$row['tipe'].' ('.$row['plat_nomor'].')'); ?></td>
            <td><?php echo strtoupper($row['metode_bayar']); ?></td>
            <td>Rp <?php echo number_format($row['jumlah_bayar'],0,',','.'); ?></td>
            <td>
              <span class="badge <?php echo $row['status_bayar']=='lunas'?'bg-success':'bg-warning text-dark'; ?>">
                <?php echo strtoupper($row['status_bayar']); ?>
              </span>
            </td>
            <td>
              <a href="index.php?page=pembayaran_form&id=<?php echo $row['id_bayar']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="index.php?page=pembayaran_delete&id=<?php echo $row['id_bayar']; ?>" onclick="return confirm('Yakin hapus data ini?')" class="btn btn-sm btn-outline-danger">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>
