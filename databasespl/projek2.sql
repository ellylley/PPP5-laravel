-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Okt 2024 pada 16.30
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `id_user`, `activity`, `timestamp`) VALUES
(1231, 1, 'Mengakses halaman profile', '2024-10-11 12:08:59'),
(1232, 1, 'mengubah password profile', '2024-10-11 12:09:34'),
(1233, 1, 'Mengakses halaman profile', '2024-10-11 12:09:39'),
(1234, 1, 'mengubah password profile', '2024-10-11 12:09:55'),
(1235, 1, 'Mengakses halaman profile', '2024-10-11 12:10:00'),
(1236, 1, 'mengubah password profile', '2024-10-11 12:10:14'),
(1237, 1, 'Mengakses halaman profile', '2024-10-11 12:10:19'),
(1238, 1, 'Mengakses halaman manajemen user', '2024-10-11 12:10:38'),
(1239, 1, 'Menambah user', '2024-10-11 12:11:19'),
(1240, 1, 'Mengakses halaman manajemen user', '2024-10-11 12:11:24'),
(1241, 1, 'Mengubah data user', '2024-10-11 12:11:42'),
(1242, 1, 'Mengakses halaman manajemen user', '2024-10-11 12:11:47'),
(1243, 1, 'Merestore user yang diedit', '2024-10-11 12:12:14'),
(1244, 1, 'Mengakses halaman manajemen user', '2024-10-11 12:12:19'),
(1245, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:12:48'),
(1246, 1, 'Menambah data kelas', '2024-10-11 12:13:05'),
(1247, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:13:10'),
(1248, 1, 'Mengakses halaman manajemen tugas', '2024-10-11 12:13:34'),
(1249, 1, 'Menambah data tugas', '2024-10-11 12:14:06'),
(1250, 1, 'Mengakses halaman manajemen tugas', '2024-10-11 12:14:11'),
(1251, 1, 'Mengakses halaman penilaian', '2024-10-11 12:14:24'),
(1252, 1, 'Mengakses halaman laporan penilaian', '2024-10-11 12:15:35'),
(1253, 1, 'Mencetak nilai siswa', '2024-10-11 12:16:18'),
(1254, 1, 'Mencetak nilai siswa', '2024-10-11 12:16:32'),
(1255, 1, 'Mencetak nilai siswa', '2024-10-11 12:16:47'),
(1256, 1, 'Mengakses halaman setting', '2024-10-11 12:17:06'),
(1257, 1, 'Mengubah data setting', '2024-10-11 12:17:22'),
(1258, 1, 'Mengakses halaman setting', '2024-10-11 12:17:27'),
(1259, 1, 'Mengakses halaman log aktivitas', '2024-10-11 12:17:34'),
(1260, 1, 'Mengakses halaman restore user', '2024-10-11 12:17:48'),
(1261, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:17:57'),
(1262, 1, 'Menghapus data kelas', '2024-10-11 12:18:04'),
(1263, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:18:10'),
(1264, 1, 'Mengakses halaman restore kelas', '2024-10-11 12:18:19'),
(1265, 1, 'Merestore kelas', '2024-10-11 12:18:30'),
(1266, 1, 'Mengakses halaman restore kelas', '2024-10-11 12:18:35'),
(1267, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:18:43'),
(1268, 1, 'Mengakses halaman restore tugas', '2024-10-11 12:18:53'),
(1269, 21, 'Mengakses halaman laporan penilaian', '2024-10-11 12:20:08'),
(1270, 22, 'Mengakses halaman laporan penilaian', '2024-10-11 12:21:03'),
(1271, 23, 'Mengakses halaman manajemen tugas', '2024-10-11 12:21:53'),
(1272, 23, 'Mengakses halaman penilaian', '2024-10-11 12:22:05'),
(1273, 23, 'Mengakses halaman laporan penilaian', '2024-10-11 12:22:19'),
(1274, 25, 'Mengakses halaman manajemen tugas', '2024-10-11 12:23:14'),
(1275, 25, 'Mengakses halaman laporan penilaian', '2024-10-11 12:23:25'),
(1276, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:52:55'),
(1277, 1, 'Menghapus data kelas', '2024-10-11 12:53:03'),
(1278, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 12:53:09'),
(1279, 1, 'Mengubah data kelas', '2024-10-11 13:13:18'),
(1280, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 13:13:23'),
(1281, 1, 'Menghapus data kelas', '2024-10-11 13:15:18'),
(1282, 1, 'Mengakses halaman manajemen kelas', '2024-10-11 13:15:24'),
(1283, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:07:59'),
(1284, 1, 'Menghapus data user', '2024-10-12 14:08:13'),
(1285, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:08:24'),
(1286, 1, 'Mengakses halaman restore user', '2024-10-12 14:08:44'),
(1287, 1, 'Merestore user', '2024-10-12 14:08:58'),
(1288, 1, 'Mengakses halaman restore user', '2024-10-12 14:09:16'),
(1289, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:09:52'),
(1290, 1, 'Mengubah data user', '2024-10-12 14:10:20'),
(1291, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:10:39'),
(1292, 1, 'Merestore user yang diedit', '2024-10-12 14:10:59'),
(1293, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:11:30'),
(1294, 1, 'Mengakses halaman manajemen kelas', '2024-10-12 14:11:48'),
(1295, 1, 'Menambah data kelas', '2024-10-12 14:12:04'),
(1296, 1, 'Mengakses halaman manajemen kelas', '2024-10-12 14:12:11'),
(1297, 1, 'Mengubah data kelas', '2024-10-12 14:12:27'),
(1298, 1, 'Mengakses halaman manajemen kelas', '2024-10-12 14:12:34'),
(1299, 1, 'Menghapus data kelas', '2024-10-12 14:13:01'),
(1300, 1, 'Mengakses halaman manajemen kelas', '2024-10-12 14:13:08'),
(1301, 1, 'Mengakses halaman restore kelas', '2024-10-12 14:13:29'),
(1302, 1, 'Merestore kelas', '2024-10-12 14:13:39'),
(1303, 1, 'Mengakses halaman restore kelas', '2024-10-12 14:13:46'),
(1304, 1, 'Mengakses halaman manajemen kelas', '2024-10-12 14:14:01'),
(1305, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:14:34'),
(1306, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:14:53'),
(1307, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:16:13'),
(1308, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:16:30'),
(1309, 1, 'Menambah data tugas', '2024-10-12 14:17:01'),
(1310, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:17:08'),
(1311, 1, 'Mengakses halaman penilaian', '2024-10-12 14:17:18'),
(1312, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:17:39'),
(1313, 1, 'Menambah data tugas', '2024-10-12 14:18:06'),
(1314, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:18:13'),
(1315, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:18:37'),
(1316, 1, 'Mengubah data tugas', '2024-10-12 14:18:49'),
(1317, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:18:57'),
(1318, 1, 'Merestore tugas yang diedit', '2024-10-12 14:19:19'),
(1319, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:19:27'),
(1320, 1, 'Menghapus data tugas', '2024-10-12 14:20:07'),
(1321, 1, 'Mengakses halaman manajemen tugas', '2024-10-12 14:20:14'),
(1322, 1, 'Mengakses halaman restore tugas', '2024-10-12 14:20:34'),
(1323, 1, 'Merestore tugas', '2024-10-12 14:20:43'),
(1324, 1, 'Mengakses halaman restore tugas', '2024-10-12 14:20:51'),
(1325, 1, 'Mengakses halaman penilaian', '2024-10-12 14:21:06'),
(1326, 1, 'Mengakses halaman penilaian', '2024-10-12 14:21:54'),
(1327, 1, 'Mengakses halaman laporan penilaian', '2024-10-12 14:23:06'),
(1328, 1, 'Mengakses halaman laporan penilaian', '2024-10-12 14:23:33'),
(1329, 1, 'Mengakses halaman setting', '2024-10-12 14:23:53'),
(1330, 1, 'Mengubah data setting', '2024-10-12 14:24:09'),
(1331, 1, 'Mengakses halaman setting', '2024-10-12 14:24:16'),
(1332, 1, 'Mengubah data setting', '2024-10-12 14:25:25'),
(1333, 1, 'Mengakses halaman setting', '2024-10-12 14:25:32'),
(1334, 1, 'Mengubah data setting', '2024-10-12 14:25:48'),
(1335, 1, 'Mengakses halaman setting', '2024-10-12 14:25:55'),
(1336, 1, 'Mengubah data setting', '2024-10-12 14:26:09'),
(1337, 1, 'Mengakses halaman setting', '2024-10-12 14:26:16'),
(1338, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:26:32'),
(1339, 1, 'Mereset password user', '2024-10-12 14:26:52'),
(1340, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:26:59'),
(1341, 1, 'Mereset password user', '2024-10-12 14:28:02'),
(1342, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:28:09'),
(1343, 1, 'Mengubah data user', '2024-10-12 14:29:18'),
(1344, 1, 'Mengakses halaman manajemen user', '2024-10-12 14:29:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `backup_kelas`
--

CREATE TABLE `backup_kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `isdelete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `backup_tugas`
--

CREATE TABLE `backup_tugas` (
  `id_tugas` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `nama_tugas` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `isdelete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `backup_user`
--

CREATE TABLE `backup_user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `nisn` varchar(11) DEFAULT NULL,
  `jk` varchar(20) DEFAULT NULL,
  `tgl_lhr` date DEFAULT NULL,
  `isdelete` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `backup_user`
--

INSERT INTO `backup_user` (`id_user`, `nama_user`, `password`, `level`, `id_kelas`, `foto`, `nis`, `nisn`, `jk`, `tgl_lhr`, `isdelete`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(25, 'siswa', 'c4ca4238a0b923820dcc509a6f75849b', 5, 7, 'default.jpg', '678', '678', 'Laki-laki', '2024-09-21', 0, '2024-09-21 08:15:48', NULL, NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) NOT NULL,
  `nama_kelas` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `isdelete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `isdelete`) VALUES
(7, 'RPL XII A', '2024-09-21 07:49:30', NULL, NULL, 1, NULL, NULL, 0),
(22, 'RPL XII B', '2024-10-12 21:12:04', '2024-10-12 21:12:27', NULL, 1, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_tugas` int(11) DEFAULT NULL,
  `nilai` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_tugas`, `nilai`, `id_user`, `id_kelas`, `updated_at`, `updated_by`, `created_at`, `created_by`) VALUES
(39, 12, 88, 25, 7, '2024-10-12 21:22:17', 1, '2024-10-11 19:15:13', 1),
(40, 12, 80, 32, 7, '2024-10-12 21:22:17', 1, '2024-10-11 19:15:13', 1),
(41, 16, 80, 25, 7, '2024-10-12 21:22:47', 1, '2024-10-12 21:22:47', 1),
(42, 16, 80, 32, 7, '2024-10-12 21:22:47', 1, '2024-10-12 21:22:47', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `id_setting` int(11) NOT NULL,
  `nama_setting` varchar(50) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nama_sekolah` text NOT NULL,
  `nohp` varchar(20) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`id_setting`, `nama_setting`, `logo`, `alamat`, `nama_sekolah`, `nohp`, `updated_by`, `updated_at`) VALUES
(1, 'PENUGASAN DAN PENILAIAN MODUL', 'logo_sph.png', 'Permata Baloi Blok F7 No.22B - 25C, Baloi Indah, Kec. Lubuk Baja, Kota Batam, Kepulauan Riau 29444', 'SEKOLAH PERMATA HARAPAN', '(0778) 431318', 1, '2024-10-12 21:26:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tugas`
--

CREATE TABLE `tugas` (
  `id_tugas` int(11) NOT NULL,
  `id_kelas` int(11) DEFAULT NULL,
  `nama_tugas` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `isdelete` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tugas`
--

INSERT INTO `tugas` (`id_tugas`, `id_kelas`, `nama_tugas`, `tanggal`, `id_user`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`, `isdelete`) VALUES
(12, 7, 'mengerjakan kuis di elearning', '2024-09-21', 1, '2024-09-21 08:17:38', NULL, NULL, 1, NULL, NULL, 0),
(16, 7, 'menyanyikan lagu indonesia raya', '2024-10-12', 1, '2024-10-12 21:17:02', NULL, NULL, 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `id_kelas` int(11) NOT NULL,
  `foto` text DEFAULT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `nisn` varchar(10) DEFAULT NULL,
  `tgl_lhr` date DEFAULT NULL,
  `jk` varchar(20) DEFAULT NULL,
  `isdelete` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `password`, `level`, `id_kelas`, `foto`, `nis`, `nisn`, `tgl_lhr`, `jk`, `isdelete`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 'admin', 'c4ca4238a0b923820dcc509a6f75849b', 1, 0, 'default.jpg', NULL, NULL, NULL, NULL, 0, NULL, '2024-10-11 19:10:14', NULL, NULL, 1, NULL),
(21, 'kepsek', 'c4ca4238a0b923820dcc509a6f75849b', 2, 0, 'default.jpg', '123', '123', '2024-09-21', 'Laki-laki', 0, '2024-09-21 07:48:11', NULL, NULL, 1, NULL, NULL),
(22, 'wakepsek', 'c4ca4238a0b923820dcc509a6f75849b', 3, 0, 'default.jpg', '234', '234', '2024-09-21', 'Perempuan', 0, '2024-09-21 07:48:33', NULL, NULL, 1, NULL, NULL),
(23, 'guru', 'c4ca4238a0b923820dcc509a6f75849b', 4, 0, 'default.jpg', '345', '345', '2024-09-21', 'Perempuan', 0, '2024-09-21 07:48:52', NULL, NULL, 1, NULL, NULL),
(25, 'siswa', 'c4ca4238a0b923820dcc509a6f75849b', 5, 7, '1728743359_12.png', '678', '678', '2024-09-21', 'Laki-laki', 0, '2024-09-21 08:15:48', '2024-10-12 21:29:19', NULL, 1, 1, NULL),
(32, 'siswa2', 'c4ca4238a0b923820dcc509a6f75849b', 5, 7, 'default.jpg', '1', '1', '2024-10-11', 'Perempuan', 0, '2024-10-11 19:11:19', '2024-10-12 21:28:02', NULL, 1, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `backup_kelas`
--
ALTER TABLE `backup_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `backup_tugas`
--
ALTER TABLE `backup_tugas`
  ADD PRIMARY KEY (`id_tugas`);

--
-- Indeks untuk tabel `backup_user`
--
ALTER TABLE `backup_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id_tugas`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1345;

--
-- AUTO_INCREMENT untuk tabel `backup_kelas`
--
ALTER TABLE `backup_kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `backup_tugas`
--
ALTER TABLE `backup_tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `backup_user`
--
ALTER TABLE `backup_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id_tugas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
