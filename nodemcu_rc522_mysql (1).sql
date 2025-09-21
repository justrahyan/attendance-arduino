-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2025 pada 11.03
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nodemcu_rc522_mysql`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_hadir`
--

CREATE TABLE `daftar_hadir` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `kelas_id` int(10) NOT NULL,
  `waktu_absen` time NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_kelas`
--

CREATE TABLE `table_kelas` (
  `id` int(10) NOT NULL,
  `nama_kelas` varchar(100) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `table_kelas`
--

INSERT INTO `table_kelas` (`id`, `nama_kelas`, `jam_masuk`, `jam_selesai`) VALUES
(3, 'Instrumentasi Cerdas', '10:05:00', '12:40:00'),
(4, 'Pengolahan Citra Digital', '07:30:00', '09:30:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_mahasiswa_kelas`
--

CREATE TABLE `table_mahasiswa_kelas` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `table_mahasiswa_kelas`
--

INSERT INTO `table_mahasiswa_kelas` (`id`, `mahasiswa_id`, `kelas_id`) VALUES
(3, 1, 4),
(4, 12, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `table_nodemcu_rfidrc522_mysql`
--

CREATE TABLE `table_nodemcu_rfidrc522_mysql` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nim` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `table_nodemcu_rfidrc522_mysql`
--

INSERT INTO `table_nodemcu_rfidrc522_mysql` (`id`, `name`, `nim`, `gender`, `email`, `mobile`) VALUES
('1', 'Muhammad Rahyan Noorfauzan', '230210501057', 'Male', 'rahyannn@gmail.com', '082152911426'),
('12', 'Feri Awal', '213123123', 'Male', 'feri@gmail.com', '082152911425');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `daftar_hadir`
--
ALTER TABLE `daftar_hadir`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `table_kelas`
--
ALTER TABLE `table_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `table_mahasiswa_kelas`
--
ALTER TABLE `table_mahasiswa_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `table_nodemcu_rfidrc522_mysql`
--
ALTER TABLE `table_nodemcu_rfidrc522_mysql`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `daftar_hadir`
--
ALTER TABLE `daftar_hadir`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `table_kelas`
--
ALTER TABLE `table_kelas`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `table_mahasiswa_kelas`
--
ALTER TABLE `table_mahasiswa_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
