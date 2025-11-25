CREATE DATABASE rental_mobil;
USE rental_mobil;

CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(20),
    no_ktp VARCHAR(30) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE pegawai (
    id_pegawai INT AUTO_INCREMENT PRIMARY KEY,
    nama_pegawai VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20)
);


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


CREATE TABLE pembayaran (
    id_bayar INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT NOT NULL,
    tanggal_bayar DATE NOT NULL,
    metode_bayar ENUM('cash', 'transfer', 'qris') NOT NULL,
    jumlah_bayar FLOAT NOT NULL,
    status_bayar ENUM('lunas', 'belum_lunas', 'pending') NOT NULL,
    FOREIGN KEY (id_sewa) REFERENCES sewa(id_sewa)
);


CREATE TABLE pengembalian (
    id_kembali INT AUTO_INCREMENT PRIMARY KEY,
    id_sewa INT NOT NULL,
    tanggal_kembali DATE NOT NULL,
    kondisi_mobil TEXT,
    keterlambatan_hari INT,
    denda FLOAT,

    FOREIGN KEY (id_sewa) REFERENCES sewa(id_sewa)
);
