-- database rental mobil

-- Struktur tabel 'Mobil'
CREATE TABLE tb_mobil (
  id_mobil INT AUTO_INCREMENT PRIMARY KEY,
  merk VARCHAR(50),
  tipe VARCHAR(50),
  plat_nomor VARCHAR(15),
  tahun YEAR,
  warna VARCHAR(30),
  harga_sewa FLOAT,
  status ENUM('tersedia','disewa','perawatan')
);

-- Data tabel 'Mobil'
INSERT INTO tb_mobil (merk, tipe, plat_nomor, tahun, warna, harga_sewa, status)
VALUES
('Toyota', 'Avanza', 'BE1234AA', 2021, 'Silver', 350000, 'tersedia'),
('Honda', 'Jazz', 'BE5678BB', 2020, 'Merah', 400000, 'tersedia'),
('Suzuki', 'Ertiga', 'BE9012CC', 2022, 'Putih', 375000, 'disewa'),
('Mitsubishi', 'Xpander', 'BE3456DD', 2023, 'Hitam', 450000, 'perawatan'),
('Daihatsu', 'Sigra', 'BE7890EE', 2019, 'Biru', 300000, 'tersedia');

--------------------------------------------------------------------------

-- Struktur tabel 'Peanggan'
CREATE TABLE tb_pelanggan (
  id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  alamat TEXT,
  no_telepon VARCHAR(15),
  email VARCHAR(100),
  no_ktp VARCHAR(20),
  tanggal_daftar DATE
);

-- Data tabel 'Pelanggan'
INSERT INTO tb_pelanggan (nama, alamat, no_telepon, email, no_ktp, tanggal_daftar)
VALUES
('Andi Pratama', 'Jl. Pahlawan No.12, Bandar Lampung', '081234567890', 'andi@gmail.com', '1671020200010001', '2024-01-12'),
('Siti Rahma', 'Jl. Mawar No.45, Metro', '081298765432', 'siti@gmail.com', '1671030300020002', '2024-02-05'),
('Budi Santoso', 'Jl. Gatot Subroto No.33, Pringsewu', '082345678901', 'budi@gmail.com', '1671040400030003', '2024-03-22'),
('Dewi Lestari', 'Jl. Kartini No.9, Bandar Lampung', '083456789012', 'dewi@gmail.com', '1671050500040004', '2024-04-10'),
('Rudi Hartono', 'Jl. Ahmad Yani No.7, Tanggamus', '084567890123', 'rudi@gmail.com', '1671060600050005', '2024-05-14');

--------------------------------------------------------------------------------------
-- Struktur tabel 'Karyawan'
CREATE TABLE tb_karyawan (
  id_karyawan INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  jabatan VARCHAR(50),
  username VARCHAR(50),
  password VARCHAR(255),
  no_hp VARCHAR(15)
);

-- Data tabel 'Karyawan'
INSERT INTO tb_karyawan (nama, jabatan, username, password, no_hp)
VALUES
('Agus Setiawan', 'Admin', 'agus', 'admin123', '081245678901'),
('Lina Marlina', 'Kasir', 'lina', 'kasir123', '081256789012'),
('Rian Saputra', 'Petugas Lapangan', 'rian', 'lapangan123', '081267890123');

--Struktur tabel 'Penyewaan'
CREATE TABLE tb_penyewaan (
  id_sewa INT AUTO_INCREMENT PRIMARY KEY,
  id_pelanggan INT,
  id_mobil INT,
  id_karyawan INT,
  tanggal_sewa DATE,
  tanggal_rencana_kembali DATE,
  total_harga FLOAT,
  status_sewa ENUM('aktif','selesai','batal'),
  FOREIGN KEY (id_pelanggan) REFERENCES tb_pelanggan(id_pelanggan),
  FOREIGN KEY (id_mobil) REFERENCES tb_mobil(id_mobil),
  FOREIGN KEY (id_karyawan) REFERENCES tb_karyawan(id_karyawan)
);

-- Data tabel 'penyewaan'
INSERT INTO tb_penyewaan (id_pelanggan, id_mobil, id_karyawan, tanggal_sewa, tanggal_rencana_kembali, total_harga, status_sewa)
VALUES
(1, 3, 1, '2025-11-10', '2025-11-13', 1125000, 'aktif'),
(2, 1, 2, '2025-11-05', '2025-11-08', 1050000, 'selesai'),
(3, 2, 3, '2025-10-25', '2025-10-27', 800000, 'selesai'),
(4, 5, 1, '2025-11-12', '2025-11-15', 900000, 'aktif');
------------------------------------------------------------------------------------

--Struktur tabel 'Pengembalian'
CREATE TABLE tb_pengembalian (
  id_kembali INT AUTO_INCREMENT PRIMARY KEY,
  id_sewa INT,
  tanggal_kembali DATE,
  kondisi_mobil TEXT,
  keterlambatan_hari INT,
  denda FLOAT,
  FOREIGN KEY (id_sewa) REFERENCES tb_penyewaan(id_sewa)
);

-- Data tabel 'Pengembalian'
INSERT INTO tb_pengembalian (id_sewa, tanggal_kembali, kondisi_mobil, keterlambatan_hari, denda)
VALUES
(2, '2025-11-08', 'Baik', 0, 0),
(3, '2025-10-27', 'Sedikit kotor', 0, 0);
-------------------------------------------------------------------------------------------

--Struktur tabel 'Pembayaran'
CREATE TABLE tb_pembayaran (
  id_bayar INT AUTO_INCREMENT PRIMARY KEY,
  id_sewa INT,
  tanggal_bayar DATE,
  metode_bayar ENUM('cash','transfer','ewallet'),
  jumlah_bayar FLOAT,
  status_bayar ENUM('lunas','belum_lunas'),
  FOREIGN KEY (id_sewa) REFERENCES tb_penyewaan(id_sewa)
);

-- Data tabel 'Pembayaran'
INSERT INTO tb_pembayaran (id_sewa, tanggal_bayar, metode_bayar, jumlah_bayar, status_bayar)
VALUES
(2, '2025-11-08', 'transfer', 1050000, 'lunas'),
(3, '2025-10-27', 'cash', 800000, 'lunas'),
(1, '2025-11-10', 'ewallet', 500000, 'belum_lunas');
-------------------------------------------------------------------------------------

--Struktur tabel 'Perawatan'
CREATE TABLE tb_perawatan (
  id_perawatan INT AUTO_INCREMENT PRIMARY KEY,
  id_mobil INT,
  tanggal_servis DATE,
  jenis_servis VARCHAR(100),
  biaya_servis FLOAT,
  keterangan TEXT,
  FOREIGN KEY (id_mobil) REFERENCES tb_mobil(id_mobil)
);

-- Data tabel 'Perawatan'
INSERT INTO tb_perawatan (id_mobil, tanggal_servis, jenis_servis, biaya_servis, keterangan)
VALUES
(4, '2025-11-01', 'Servis rutin', 500000, 'Ganti oli dan periksa rem'),
(4, '2025-11-11', 'Perbaikan AC', 350000, 'AC kurang dingin'),
(2, '2025-10-10', 'Cuci mesin', 200000, 'Membersihkan ruang mesin');

