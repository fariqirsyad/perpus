-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Apr 2026 pada 19.27
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
  `isbn` varchar(50) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `penulis` varchar(255) NOT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `denda_per_hari` int(11) DEFAULT 5000,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `isbn`, `kategori`, `penerbit`, `judul`, `penulis`, `tahun_terbit`, `stok`, `deskripsi`, `denda_per_hari`, `cover`) VALUES
(21, '978-623-8587-55-1', 'Umum', 'An-Nizam Media', 'Menggali Kepercayaan Diri sesuai Tuntunan Islam', 'Nuri Septina', '2024', 4, '', 5000, NULL),
(23, '978-623-8587-55-1', 'Umum', 'An-Nizam Media', 'fariq', 'fariq', '2024', 3, '', 5000, '1776661451_bf737988b58100641fea.jpg'),
(24, '978-623-09-3270-0', 'Sains & Teknologi', 'Agna Media Indokarya', 'PENGANTAR TEKNOLOGI INFORMASI DAN KOMUNIKASI', 'Dr. Drs. Didik Tristianto, S.Kom., M.Kom., M.Cs.', '2023', 6, '', 5000, '1776664931_02a4afae23c9171c4548.webp'),
(25, '978-6-02412-518-9', 'Umum', 'Kompas', 'Filosofi Teras', 'Henry Manampiring', '2018', 4, '', 5000, '1776665322_77fc7d9d7682bfb444f1.jpg'),
(26, '978-602-453-292-5', 'Agama', 'Deepublish', 'Buku Islamic Intensive Study Pendidikan Agama Islam ', 'Sudirman', '2017', 1, '', 5000, '1776665546_287ed8ed68741a8c4fe8.jpg'),
(28, '9786024021795', 'Bahasa & Sastra', 'Qanita', 'Recehan Bahasa', 'Ivan Lanin', '2020', 4, 'Buku “Recehan Bahasa” yang ditulis oleh Ivan Lanin ini berisi pembahasan mengenai kebahasaan Indonesia serta kesalahan yang sering dilakukan dalam penggunaannya. Pengemasannya yang menarik membuat buku ini berbeda dari buku referensi bahasa kebanyakan. Penggunaan gaya penulisan dan pemilihan katanya yang santai, serta terkadang diselipi humor membuat buku ini jauh dari kesan membosankan.', 5000, '1776665909_97633d7ea1ca1b2f56cc.jpg'),
(30, '9786024246945', 'Sosial & Sejarah', 'Kepustakaan Populer Gramedia', 'Laut Bercerita', 'Leila S. Chudori', '2017', 6, '', 5000, '1776668213_d0014988ed6e64515443.jpg'),
(31, '978-602-401-967-9', 'Seni & Rekreasi', 'Deepublish', 'Penuntun Pewarna Alam Soga Batik', 'R. Jati Nurcahyo', '2017', 4, '', 5000, '1776668488_9330b606c1243320f7ca.jpg');

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
(58, 7, 21, '2026-04-20', '2026-04-27', '2026-04-20', 0, 'kembali'),
(59, 7, 26, '2026-04-20', '2026-04-27', '2026-04-20', 0, 'kembali'),
(60, 7, 21, '2026-04-20', '2026-04-27', '2026-04-20', 0, 'kembali'),
(61, 7, 24, '2026-04-20', '2026-04-27', '2026-04-20', 0, 'kembali'),
(62, 7, 23, '2026-04-20', '2026-04-27', '2026-04-20', 0, 'kembali'),
(63, 7, 23, '2026-04-20', '2026-04-19', '2026-04-20', 5000, 'kembali'),
(64, 7, 28, '2026-04-20', '2026-04-10', '2026-04-20', 50000, 'kembali');

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
(2, 'irsyad', 'irsyadfariq@gmail.com', 'fariqadmin', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'admin', NULL, 'aktif', '2026-04-11 04:04:12'),
(3, 'irsyad', 'fariq@gmail.com', 'user', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'anggota', NULL, 'aktif', '2026-04-11 06:48:11'),
(4, 'fff', 'fariq.irsyad37@smk.belajar.id', 'ff', '$2y$10$1YPXxwR38TWEpMwqJd/39.Vtkf9/.z8Cr8p4P1gesluGHjVZxBtn.', 'petugas', NULL, 'aktif', '2026-04-11 16:52:01'),
(7, 'Fariq ', 'fariq.irsyad37@smk.belajar.id', 'fariquser', '$2y$10$6rGOC6uXsGCeVGwhEY966ec1vrSZ3zxDOzFof7WzU0l1bbv8FS7vW', 'anggota', '1776485897_18c0bb822cb3287066c0.jpg', 'aktif', '2026-04-18 04:18:17');

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
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
