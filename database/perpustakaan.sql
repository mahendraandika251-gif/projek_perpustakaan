-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2026 at 07:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nisn` int(9) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `username`, `password`, `email`, `nisn`, `kelas`, `role`) VALUES
(5, 'mulyono', '$2y$10$p.pnAa3oBhfVLTe3KB.Qv.PFL1jSl.k4nbnVVZsuclsHqeZLkQE/q', 'mulyono@gmail.com', 1111, '12', 'admin'),
(15, 'yaya', '$2y$10$2m8SSqFkSGphPsFKlIXHVeuvau4Orv7tVYXFlV/SVOzPRnyODSTMq', 'yaya@gmail.com', 816161, '12 rpl', 'admin'),
(19, 'yuyu', '$2y$10$KNzyaXslUwCDcTlfNkD87.mSRAUi6/wKCIu7khdZjctTyA9dDvlJ2', 'hasip89797@gmail.com', 83636, '12 rpl 3', 'user'),
(20, 'hshs', '$2y$12$wYC5xAHHYLrwsknhSK4DWeAcAdj.T/Idr3jKAtUvoXxvu9utzgOJa', 'huhu@gmail.com', 0, '', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `kategori` varchar(20) NOT NULL,
  `stok` int(5) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `kategori`, `stok`, `foto`) VALUES
(14, 'Pemrograman Web dengan PHP dan MySQL', 'Budi Raharjo', 'Informatika', '2023', 'Teknologi', 12, '0c9cddcf-b6b1-400d-87a8-0dd3d3b90179.jpg'),
(15, 'Belajar Dasar Pemrograman JavaScript', 'Rinrin Krismayanti', 'Elex Media Komputindo', '2022', 'Teknologi', 7, '78e9d47e-cae7-4878-bded-f28e2af09dfa.jpg'),
(16, 'Algoritma dan Struktur Data', 'Rinaldi Munir', 'Informatika', '2021', 'Pendidikan', 14, '41f8b96b-dbd4-4e6f-98df-42bfaffe627f.jpg'),
(17, 'Desain Antarmuka Pengguna (UI/UX)', 'Andika Pratama', 'Andi Publisher', '2024', 'Desain', 9, 'b6680eb3-0bcb-4860-bc2f-e799ea96affe.jpg\r\n'),
(19, 'Basis Data Relasional Moder', 'Fajar Astut', 'Deepublis', '2023', 'Teknologi', 8, '1793998442_images.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('menunggu_persetujuan_pinjam','dipinjam','menunggu_persetujuan_kembali','dikembalikan') NOT NULL DEFAULT 'menunggu_persetujuan_pinjam',
  `jumlah` int(2) NOT NULL,
  `denda` int(11) DEFAULT 0,
  `status_denda` enum('belum_lunas','lunas') DEFAULT 'lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_anggota`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `jumlah`, `denda`, `status_denda`) VALUES
(16, 19, 17, '2026-11-06', '2026-11-06', 'dikembalikan', 1, 0, 'lunas'),
(17, 19, 15, '2026-11-06', '2026-11-11', 'dikembalikan', 1, 1000, 'lunas'),
(18, 19, 19, '2026-11-06', '2026-11-11', 'dikembalikan', 1, 19000, 'belum_lunas'),
(19, 19, 16, '2026-11-08', '2026-11-11', 'dikembalikan', 1, 1000, 'belum_lunas'),
(20, 19, 17, '2026-11-10', '2026-11-11', 'dikembalikan', 1, 1000, 'lunas'),
(21, 19, 15, '2026-11-10', '2026-11-10', 'dikembalikan', 5, 0, 'lunas'),
(22, 19, 19, '2026-11-11', '2026-11-18', 'dipinjam', 1, 0, 'lunas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nisn` (`nisn`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_transaksi_anggota` (`id_anggota`),
  ADD KEY `fk_transaksi_buku` (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
