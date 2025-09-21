-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2025 pada 08.50
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
-- Struktur dari tabel `table_nodemcu_rfidrc522_mysql`
--

CREATE TABLE `table_nodemcu_rfidrc522_mysql` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nim` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `class` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `table_nodemcu_rfidrc522_mysql`
--

INSERT INTO `table_nodemcu_rfidrc522_mysql` (`id`, `name`, `nim`, `gender`, `email`, `class`, `mobile`) VALUES
('01', 'rahyan', '230210501057', 'male', 'rahyannn@gmail.com', 'SC B 23', '');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `table_nodemcu_rfidrc522_mysql`
--
ALTER TABLE `table_nodemcu_rfidrc522_mysql`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
