-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Apr 2026 pada 17.22
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
-- Database: `perpus`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) NOT NULL,
  `stok` int(11) DEFAULT NULL,
  `denda_per_hari` int(11) DEFAULT 5000
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `stok`, `denda_per_hari`) VALUES
(6, 'p', 'fariqqq', 3, 5000),
(7, 'f', 'f', 4, NULL),
(8, 'Laskar Pelangi', 'fariq', 4, NULL),
(9, 'Negeri 5 Menara', 'fariq', 5, 5000),
(10, 'fffff', 'ffff', 4, 5000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_buku` int(11) DEFAULT NULL,
  `tgl_pinjam` date DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status` enum('dipinjam','diajukan','kembali') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_user`, `id_buku`, `tgl_pinjam`, `tgl_kembali`, `tgl_dikembalikan`, `denda`, `status`) VALUES
(31, 3, 8, '2026-04-17', '2026-04-24', NULL, 0, 'dipinjam'),
(32, 3, 9, '2026-04-17', '2026-04-24', NULL, 0, 'dipinjam'),
(33, 3, 10, '2026-04-17', '2026-04-24', '2026-04-17', 0, 'kembali'),
(34, 6, 9, '2026-04-17', '2026-04-24', NULL, 0, 'dipinjam'),
(35, 6, 8, '2026-04-17', '2026-04-24', NULL, 0, 'dipinjam');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','petugas','anggota') DEFAULT 'anggota',
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `username`, `password`, `role`, `foto`, `status`, `created_at`) VALUES
(2, 'irsyad', 'irsyadfariq', 'fariqadmin', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'admin', NULL, 'aktif', '2026-04-11 04:04:12'),
(3, 'irsyad', 'fariq@gmail.com', 'user', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'anggota', NULL, 'aktif', '2026-04-11 06:48:11'),
(4, 'fff', 'fariq.irsyad37@smk.belajar.id', 'ff', '$2y$10$1YPXxwR38TWEpMwqJd/39.Vtkf9/.z8Cr8p4P1gesluGHjVZxBtn.', 'petugas', NULL, 'aktif', '2026-04-11 16:52:01'),
(6, 'f', 'fariq.irsyad37@smk.belajar.id', 'ganteng', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'anggota', NULL, 'aktif', '2026-04-15 02:11:58');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
