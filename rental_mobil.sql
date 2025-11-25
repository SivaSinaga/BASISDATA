
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(20),
    no_ktp VARCHAR(30) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO pelanggan (nama_pelanggan, alamat, no_hp, no_ktp) VALUES
('Andi Pratama', 'Jl. Pahlawan No.12, Bandar Lampung', '081234567890', '1671020200010001'),
('Siti Rahma', 'Jl. Mawar No.45, Metro', '081298765432', '1671030300020002'),
('Budi Santoso', 'Jl. Gatot Subroto No.33, Pringsewu', '082345678901', '1671040400030003'),
('Dewi Lestari', 'Jl. Kartini No.9, Bandar Lampung', '083456789012', '1671050500040004'),
('Rudi Hartono', 'Jl. Ahmad Yani No.7, Tanggamus', '084567890123', '1671060600050005');


CREATE TABLE pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama_pegawai VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20)
);

INSERT INTO pegawai (nama_pegawai, username, password, no_hp) VALUES
('Agus Setiawan', 'agus', SHA2('admin123',256), '081245678901'),
('Lina Marlina', 'lina', SHA2('admin456',256), '081256789012'),
('Rian Saputra', 'rian', SHA2('admin789',256), '081267890123');


CREATE TABLE mobil (
    id_mobil INT AUTO_INCREMENT PRIMARY KEY,
    merk VARCHAR(50) NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    plat_nomor VARCHAR(15) NOT NULL UNIQUE,
    tahun YEAR NOT NULL,
    warna VARCHAR(30) NOT NULL,
    harga_sewa FLOAT NOT NULL,
    status ENUM('tersedia','disewa','perawatan') DEFAULT 'tersedia'
);

INSERT INTO mobil (merk, tipe, plat_nomor, tahun, warna, harga_sewa, status) VALUES
('Toyota', 'Avanza', 'BE 1234 ABC', 2019, 'Hitam', 350000, 'tersedia'),
('Honda', 'Brio', 'BE 9876 CFD', 2020, 'Putih', 300000, 'tersedia'),
('Mitsubishi', 'Xpander', 'BE 1122 DFD', 2018, 'Silver', 400000, 'disewa'),
('Suzuki', 'Ertiga', 'BE 5566 HHG', 2021, 'Abu-Abu', 375000, 'tersedia'),
('Daihatsu', 'Sigra', 'BE 7788 KJL', 2019, 'Merah', 280000, 'perawatan');


CREATE TABLE sewa (
    id_sewa INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    id_mobil INT NOT NULL,
    id_pegawai INT NOT NULL,
    tanggal_sewa DATE NOT NULL,
    tanggal_rencana_kembali DATE NOT NULL,
    total_harga FLOAT,
    status_sewa ENUM('berjalan','selesai','batal') DEFAULT 'berjalan',
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan),
    FOREIGN KEY (id_mobil) REFERENCES mobil(id_mobil),
    FOREIGN KEY (id_pegawai) REFERENCES pegawai(id_pegawai)
);

INSERT INTO sewa (id_pelanggan, id_mobil, id_pegawai, tanggal_sewa, tanggal_rencana_kembali, total_harga, status_sewa) VALUES
(1, 2, 1, '2025-01-10', '2025-01-12', 600000, 'selesai'),
(2, 1, 1, '2025-01-11', '2025-01-13', 800000, 'berjalan'),
(3, 4, 2, '2025-01-12', '2025-01-15', 1200000, 'berjalan'),
(1, 3, 3, '2025-01-13', '2025-01-14', 450000, 'batal'),
(4, 5, 2, '2025-01-14', '2025-01-18', 1600000, 'berjalan');


CREATE TABLE pembayaran (
    id_bayar INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT NOT NULL,
    tanggal_bayar DATE NOT NULL,
    metode_bayar ENUM('cash', 'transfer', 'qris') NOT NULL,
    jumlah_bayar FLOAT NOT NULL,
    status_bayar ENUM('lunas', 'belum_lunas', 'pending') NOT NULL,
    FOREIGN KEY (id_sewa) REFERENCES sewa(id_sewa)
);

INSERT INTO pembayaran (id_sewa, tanggal_bayar, metode_bayar, jumlah_bayar, status_bayar) VALUES
(1, '2025-01-11', 'cash', 600000, 'lunas'),
(2, '2025-01-12', 'transfer', 400000, 'pending'),
(2, '2025-01-13', 'qris', 400000, 'lunas'),
(3, '2025-01-14', 'transfer', 1200000, 'lunas'),
(4, '2025-01-13', 'cash', 0, 'pending'),   -- diperbaiki
(5, '2025-01-15', 'cash', 800000, 'pending'),
(5, '2025-01-17', 'transfer', 800000, 'lunas');



CREATE TABLE pengembalian (
    id_kembali INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT NOT NULL,
    tanggal_kembali DATE NOT NULL,
    kondisi_mobil TEXT,
    keterlambatan_hari INT,
    denda FLOAT,
    FOREIGN KEY (id_sewa) REFERENCES sewa(id_sewa)
);

INSERT INTO pengembalian (id_sewa, tanggal_kembali, kondisi_mobil, keterlambatan_hari, denda) VALUES
(1, '2025-01-12', 'baik', 0, 0),
(2, '2025-01-14', 'baik', 1, 50000),
(3, '2025-01-16', 'baik', 1, 50000),
(4, '2025-01-14', 'baik', 0, 0),
(5, '2025-01-18', 'baik', 0, 0);

