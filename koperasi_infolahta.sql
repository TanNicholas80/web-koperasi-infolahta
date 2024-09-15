-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Sep 2024 pada 20.29
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
-- Database: `koperasi_infolahta`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_besar_cash_ins`
--

CREATE TABLE `buku_besar_cash_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_main_cash_trans` int(11) DEFAULT NULL,
  `kas` double DEFAULT 0,
  `bank_sp` double DEFAULT 0,
  `bank_induk` double DEFAULT 0,
  `piutang_uang` double DEFAULT 0,
  `piutang_barang_toko` double DEFAULT 0,
  `dana_sosial` double DEFAULT 0,
  `dana_dik` double DEFAULT 0,
  `dana_pdk` double DEFAULT 0,
  `resiko_kredit` double DEFAULT 0,
  `simpanan_pokok` double DEFAULT 0,
  `sipanan_wajib` double DEFAULT 0,
  `sipanan_khusus` double DEFAULT 0,
  `sipanan_tunai` double DEFAULT 0,
  `jasa_sp` double DEFAULT 0,
  `provinsi` double DEFAULT 0,
  `shu_puskop` double DEFAULT 0,
  `inv_usipa` double DEFAULT 0,
  `lain_lain` double DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `buku_besar_cash_ins`
--

INSERT INTO `buku_besar_cash_ins` (`id`, `id_main_cash_trans`, `kas`, `bank_sp`, `bank_induk`, `piutang_uang`, `piutang_barang_toko`, `dana_sosial`, `dana_dik`, `dana_pdk`, `resiko_kredit`, `simpanan_pokok`, `sipanan_wajib`, `sipanan_khusus`, `sipanan_tunai`, `jasa_sp`, `provinsi`, `shu_puskop`, `inv_usipa`, `lain_lain`, `created_at`, `updated_at`) VALUES
(1, 1, 100000, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 16:48:06', '2024-09-12 16:48:06'),
(2, 2, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 17:02:45', '2024-09-12 17:02:45'),
(3, 6, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(4, 7, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(5, 14, 1000000, 1000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:07:11', '2024-09-12 18:07:11'),
(6, 15, 300000, 300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:11:00', '2024-09-12 18:11:00'),
(7, 16, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:11:00', '2024-09-12 18:11:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_besar_cash_outs`
--

CREATE TABLE `buku_besar_cash_outs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_main_cash_trans` int(11) DEFAULT NULL,
  `kas` double DEFAULT 0,
  `bank_sp` double DEFAULT 0,
  `bank_induk` double DEFAULT 0,
  `simpan_pinjam` double DEFAULT 0,
  `inventaris` double DEFAULT 0,
  `penyertaan_puskop` double DEFAULT 0,
  `hutang_toko` double DEFAULT 0,
  `dana_pengurus` double DEFAULT 0,
  `dana_karyawan` double DEFAULT 0,
  `dana_sosial` double DEFAULT 0,
  `dana_dik` double DEFAULT 0,
  `dana_pdk` double DEFAULT 0,
  `simp_pokok` double DEFAULT 0,
  `simp_wajib` double DEFAULT 0,
  `simp_khusus` double DEFAULT 0,
  `shu_angg` double DEFAULT 0,
  `pembelian_toko` double DEFAULT 0,
  `biaya_insentif` double DEFAULT 0,
  `biaya_atk` double DEFAULT 0,
  `biaya_transport` double DEFAULT 0,
  `biaya_pembinaan` double DEFAULT 0,
  `biaya_pembungkus` double DEFAULT 0,
  `biaya_rat` double DEFAULT 0,
  `biaya_thr` double DEFAULT 0,
  `biaya_pajak` double DEFAULT 0,
  `biaya_admin` double DEFAULT 0,
  `biaya_training` double DEFAULT 0,
  `inv_usipa` double DEFAULT 0,
  `lain_lain` double DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `buku_besar_cash_outs`
--

INSERT INTO `buku_besar_cash_outs` (`id`, `id_main_cash_trans`, `kas`, `bank_sp`, `bank_induk`, `simpan_pinjam`, `inventaris`, `penyertaan_puskop`, `hutang_toko`, `dana_pengurus`, `dana_karyawan`, `dana_sosial`, `dana_dik`, `dana_pdk`, `simp_pokok`, `simp_wajib`, `simp_khusus`, `shu_angg`, `pembelian_toko`, `biaya_insentif`, `biaya_atk`, `biaya_transport`, `biaya_pembinaan`, `biaya_pembungkus`, `biaya_rat`, `biaya_thr`, `biaya_pajak`, `biaya_admin`, `biaya_training`, `inv_usipa`, `lain_lain`, `created_at`, `updated_at`) VALUES
(1, 3, 200000, 0, 0, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 17:11:36', '2024-09-12 17:11:36'),
(2, 4, 100000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 17:17:53', '2024-09-12 17:17:53'),
(6, 10, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(7, 11, 100000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(8, 12, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(9, 13, 100000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-12 18:03:27', '2024-09-12 18:03:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cash_in_trans`
--

CREATE TABLE `cash_in_trans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_main_cash` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cash_in_trans`
--

INSERT INTO `cash_in_trans` (`id`, `id_main_cash`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-09-12 16:48:06', '2024-09-12 16:48:06'),
(2, 2, '2024-09-12 17:02:45', '2024-09-12 17:02:45'),
(3, 6, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(4, 7, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(5, 14, '2024-09-12 18:07:11', '2024-09-12 18:07:11'),
(6, 15, '2024-09-12 18:11:00', '2024-09-12 18:11:00'),
(7, 16, '2024-09-12 18:11:00', '2024-09-12 18:11:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cash_out_trans`
--

CREATE TABLE `cash_out_trans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_main_cash` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cash_out_trans`
--

INSERT INTO `cash_out_trans` (`id`, `id_main_cash`, `created_at`, `updated_at`) VALUES
(1, 3, '2024-09-12 17:11:36', '2024-09-12 17:11:36'),
(2, 4, '2024-09-12 17:17:53', '2024-09-12 17:17:53'),
(6, 10, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(7, 11, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(8, 12, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(9, 13, '2024-09-12 18:03:27', '2024-09-12 18:03:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `main_cashs`
--

CREATE TABLE `main_cashs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('KM','KK') NOT NULL,
  `saldo` double NOT NULL,
  `saldo_awal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `main_cashs`
--

INSERT INTO `main_cashs` (`id`, `date`, `status`, `saldo`, `saldo_awal`, `created_at`, `updated_at`) VALUES
(1, '2024-08-01', 'KM', 400000, NULL, '2024-09-12 16:48:06', '2024-09-12 16:48:06'),
(2, '2024-08-12', 'KM', 600000, NULL, '2024-09-12 17:02:45', '2024-09-12 17:02:45'),
(3, '2024-08-16', 'KK', 400000, NULL, '2024-09-12 17:11:36', '2024-09-12 17:11:36'),
(4, '2024-09-02', 'KK', 300000, NULL, '2024-09-12 17:17:53', '2024-09-12 17:17:53'),
(6, '2024-09-13', 'KM', 700000, NULL, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(8, '2024-10-13', 'KK', 400000, NULL, '2024-09-12 18:03:26', '2024-09-12 18:03:27'),
(9, '2024-10-13', 'KK', 200000, NULL, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(10, '2024-10-30', 'KM', 1200000, NULL, '2024-09-12 18:07:11', '2024-09-12 18:07:11'),
(11, '2024-10-15', 'KM', 1700000, NULL, '2024-09-12 18:11:00', '2024-09-12 18:11:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `main_cash_trans`
--

CREATE TABLE `main_cash_trans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `main_cash_id` bigint(20) UNSIGNED NOT NULL,
  `trans_date` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `periode` int(11) NOT NULL,
  `jenis_transaksi` varchar(255) DEFAULT NULL,
  `kategori_buku_besar` varchar(255) DEFAULT NULL,
  `kredit_transaction` double DEFAULT NULL,
  `debet_transaction` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `main_cash_trans`
--

INSERT INTO `main_cash_trans` (`id`, `main_cash_id`, `trans_date`, `keterangan`, `periode`, `jenis_transaksi`, `kategori_buku_besar`, `kredit_transaction`, `debet_transaction`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-08-01', 'Penjualan Barang Toko tgl 2024-08-01', 1, 'Penjualan Barang Toko', 'bank_sp', NULL, 100000, '2024-09-12 16:48:06', '2024-09-12 16:48:06'),
(2, 2, '2024-08-12', 'Penjualan Barang Toko tgl 2024-08-12', 1, 'Penjualan Barang Toko', 'bank_sp', NULL, 200000, '2024-09-12 17:02:45', '2024-09-12 17:02:45'),
(3, 3, '2024-08-16', 'Pengeluaran Simpanan tgl 2024-08-16', 1, 'Pengeluaran Simpanan', 'simpan_pinjam', 200000, NULL, '2024-09-12 17:11:36', '2024-09-12 17:11:36'),
(4, 4, '2024-09-02', 'Pengeluaran Belanja Toko tgl 2024-09-02', 2, 'Pengeluaran Belanja Toko', 'hutang_toko', 100000, NULL, '2024-09-12 17:17:53', '2024-09-12 17:17:53'),
(6, 6, '2024-09-13', 'Penjualan Barang Toko tgl 2024-09-13', 3, 'Penjualan Barang Toko', 'bank_sp', NULL, 200000, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(7, 6, '2024-09-13', 'Penerimaan Angsuran tgl 2024-09-13', 4, 'Penerimaan Angsuran', 'bank_sp', NULL, 200000, '2024-09-12 17:38:07', '2024-09-12 17:38:07'),
(10, 8, '2024-10-13', 'Pengeluaran Simpanan tgl 2024-10-13', 1, 'Pengeluaran Simpanan', 'simp_wajib', 100000, NULL, '2024-09-12 18:03:26', '2024-09-12 18:03:26'),
(11, 8, '2024-10-13', 'Pembayaran Hutang Toko tgl 2024-10-13', 1, 'Pembayaran Hutang Toko', 'hutang_toko', 100000, NULL, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(12, 9, '2024-10-13', 'Pengeluaran Simpanan tgl 2024-10-13', 1, 'Pengeluaran Simpanan', 'simp_wajib', 100000, NULL, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(13, 9, '2024-10-13', 'Pembayaran Hutang Toko tgl 2024-10-13', 1, 'Pembayaran Hutang Toko', 'hutang_toko', 100000, NULL, '2024-09-12 18:03:27', '2024-09-12 18:03:27'),
(14, 10, '2024-10-30', 'Penjualan Barang Toko tgl 2024-10-30', 1, 'Penjualan Barang Toko', 'bank_sp', NULL, 1000000, '2024-09-12 18:07:11', '2024-09-12 18:07:11'),
(15, 11, '2024-10-15', 'Penjualan Barang Toko tgl 2024-10-15', 2, 'Penjualan Barang Toko', 'bank_sp', NULL, 300000, '2024-09-12 18:11:00', '2024-09-12 18:11:00'),
(16, 11, '2024-10-15', 'Penerimaan Angsuran tgl 2024-10-15', 2, 'Penerimaan Angsuran', 'bank_sp', NULL, 200000, '2024-09-12 18:11:00', '2024-09-12 18:11:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_10_044816_create_main_cashs_table', 1),
(6, '2024_09_10_092550_create_cash_in_trans_table', 1),
(7, '2024_09_10_092559_create_cash_out_trans_table', 1),
(8, '2024_09_10_174739_create_buku_besar_cash_outs_table', 1),
(9, '2024_09_10_174748_create_buku_besar_cash_ins_table', 1),
(10, '2024_09_12_151806_create_main_cash_trans_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `about_me` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `location`, `about_me`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'nicholas', 'tannicholas54@gmail.com', '$2y$10$BHlpW2eo9vUtjAU6xXFW3O39N.jIyiBe7nKBYZT9c7IzFVWQHfcsy', NULL, NULL, NULL, NULL, '2024-09-12 14:40:16', '2024-09-12 14:40:16');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku_besar_cash_ins`
--
ALTER TABLE `buku_besar_cash_ins`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `buku_besar_cash_outs`
--
ALTER TABLE `buku_besar_cash_outs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cash_in_trans`
--
ALTER TABLE `cash_in_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cash_out_trans`
--
ALTER TABLE `cash_out_trans`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `main_cashs`
--
ALTER TABLE `main_cashs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `main_cash_trans`
--
ALTER TABLE `main_cash_trans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `main_cash_trans_main_cash_id_foreign` (`main_cash_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku_besar_cash_ins`
--
ALTER TABLE `buku_besar_cash_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `buku_besar_cash_outs`
--
ALTER TABLE `buku_besar_cash_outs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `cash_in_trans`
--
ALTER TABLE `cash_in_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `cash_out_trans`
--
ALTER TABLE `cash_out_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `main_cashs`
--
ALTER TABLE `main_cashs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `main_cash_trans`
--
ALTER TABLE `main_cash_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `main_cash_trans`
--
ALTER TABLE `main_cash_trans`
  ADD CONSTRAINT `main_cash_trans_main_cash_id_foreign` FOREIGN KEY (`main_cash_id`) REFERENCES `main_cashs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
