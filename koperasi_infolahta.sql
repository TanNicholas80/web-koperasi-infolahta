-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Sep 2024 pada 16.35
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
(1, 1, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 09:31:37', '2024-09-13 09:31:37'),
(2, 2, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(3, 4, 200000, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 10:29:54', '2024-09-13 10:29:54'),
(4, 6, 500000, 500000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 10:57:52', '2024-09-13 10:57:52'),
(5, 7, 300000, 300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-14 14:31:04', '2024-09-14 14:31:04');

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
(1, 3, 100000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(2, 5, 100000, 0, 0, 0, 0, 0, 100000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-13 10:31:25', '2024-09-13 10:31:25'),
(3, 8, 200000, 0, 0, 0, 0, 0, 200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-15 02:24:26', '2024-09-15 02:24:26'),
(4, 9, 300000, 0, 0, 0, 0, 0, 300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2024-09-15 02:24:26', '2024-09-15 02:24:26');

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
(1, 1, '2024-09-13 09:31:37', '2024-09-13 09:31:37'),
(2, 2, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(3, 4, '2024-09-13 10:29:54', '2024-09-13 10:29:54'),
(4, 6, '2024-09-13 10:57:52', '2024-09-13 10:57:52'),
(5, 7, '2024-09-14 14:31:04', '2024-09-14 14:31:04');

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
(1, 3, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(2, 5, '2024-09-13 10:31:25', '2024-09-13 10:31:25'),
(3, 8, '2024-09-15 02:24:26', '2024-09-15 02:24:26'),
(4, 9, '2024-09-15 02:24:26', '2024-09-15 02:24:26');

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
-- Struktur dari tabel `log_saldos`
--

CREATE TABLE `log_saldos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `main_cash_id` int(11) DEFAULT NULL,
  `main_cash_trans_id` int(11) DEFAULT NULL,
  `old_saldo` double DEFAULT NULL,
  `new_saldo` double DEFAULT NULL,
  `action_type` enum('insert','update','delete') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_saldos`
--

INSERT INTO `log_saldos` (`id`, `main_cash_id`, `main_cash_trans_id`, `old_saldo`, `new_saldo`, `action_type`, `created_at`, `updated_at`) VALUES
(1, 15, NULL, 1200000, 1500000, 'insert', '2024-09-14 14:31:04', '2024-09-14 14:31:04'),
(2, 16, NULL, 1500000, 1300000, 'insert', '2024-09-15 02:24:26', '2024-09-15 02:24:26'),
(3, 16, NULL, 1300000, 1000000, 'insert', '2024-09-15 02:24:26', '2024-09-15 02:24:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `main_cashs`
--

CREATE TABLE `main_cashs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `saldo` double NOT NULL,
  `saldo_awal` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `main_cashs`
--

INSERT INTO `main_cashs` (`id`, `date`, `saldo`, `saldo_awal`, `created_at`, `updated_at`) VALUES
(10, '2024-08-13', 500000, 300000, '2024-09-13 09:31:37', '2024-09-13 09:31:37'),
(11, '2024-08-21', 600000, 500000, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(12, '2024-08-26', 800000, 600000, '2024-09-13 10:29:54', '2024-09-13 10:29:54'),
(13, '2024-08-30', 700000, 800000, '2024-09-13 10:31:25', '2024-09-13 10:31:25'),
(14, '2024-09-03', 1200000, 700000, '2024-09-13 10:57:52', '2024-09-13 10:57:52'),
(15, '2024-09-18', 1500000, 1200000, '2024-09-14 14:31:04', '2024-09-14 14:31:04'),
(16, '2024-09-20', 1000000, 1500000, '2024-09-15 02:24:26', '2024-09-15 02:24:26');

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
  `status` enum('KM','KK') NOT NULL,
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

INSERT INTO `main_cash_trans` (`id`, `main_cash_id`, `trans_date`, `keterangan`, `periode`, `status`, `jenis_transaksi`, `kategori_buku_besar`, `kredit_transaction`, `debet_transaction`, `created_at`, `updated_at`) VALUES
(1, 10, '2024-08-13', 'Penjualan Barang Toko tgl 2024-08-13', 1, 'KM', 'Penjualan Barang Toko', 'bank_sp', NULL, 200000, '2024-09-13 09:31:37', '2024-09-13 09:31:37'),
(2, 11, '2024-08-21', 'Penjualan Barang Toko tgl 2024-08-21', 2, 'KM', 'Penjualan Barang Toko', 'bank_sp', NULL, 200000, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(3, 11, '2024-08-21', 'Pembayaran Hutang Toko tgl 2024-08-21', 1, 'KK', 'Pembayaran Hutang Toko', 'hutang_toko', 100000, NULL, '2024-09-13 09:42:33', '2024-09-13 09:42:33'),
(4, 12, '2024-08-26', 'Penjualan Barang Toko tgl 2024-08-26', 3, 'KM', 'Penjualan Barang Toko', 'bank_sp', NULL, 200000, '2024-09-13 10:29:54', '2024-09-13 10:29:54'),
(5, 13, '2024-08-30', 'Pengeluaran Belanja Toko tgl 2024-08-30', 2, 'KK', 'Pengeluaran Belanja Toko', 'hutang_toko', 100000, NULL, '2024-09-13 10:31:25', '2024-09-13 10:31:25'),
(6, 14, '2024-09-03', 'Penjualan Barang Toko tgl 2024-09-03', 1, 'KM', 'Penjualan Barang Toko', 'bank_sp', NULL, 500000, '2024-09-13 10:57:52', '2024-09-13 10:57:52'),
(7, 15, '2024-09-18', 'Penjualan Barang Toko tgl 2024-09-18', 2, 'KM', 'Penjualan Barang Toko', 'bank_sp', NULL, 300000, '2024-09-14 14:31:04', '2024-09-14 14:31:04'),
(8, 16, '2024-09-20', 'Pengeluaran Belanja Toko tgl 2024-09-20', 1, 'KK', 'Pengeluaran Belanja Toko', 'hutang_toko', 200000, NULL, '2024-09-15 02:24:26', '2024-09-15 02:24:26'),
(9, 16, '2024-09-20', 'Pembayaran Hutang Toko tgl 2024-09-20', 2, 'KK', 'Pembayaran Hutang Toko', 'hutang_toko', 300000, NULL, '2024-09-15 02:24:26', '2024-09-15 02:24:26');

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
(10, '2024_09_12_151806_create_main_cash_trans_table', 1),
(11, '2024_09_14_091750_create_log_saldos_table', 2);

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
(1, 'nicholas', 'tannicholas54@gmail.com', '$2y$10$fhixCYsgMR5Au4NcHqS92O2jHT8afMFlcam5BmFwJTq2nKP7pgXu2', NULL, NULL, NULL, NULL, '2024-09-13 08:53:25', '2024-09-13 08:53:25');

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
-- Indeks untuk tabel `log_saldos`
--
ALTER TABLE `log_saldos`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `buku_besar_cash_outs`
--
ALTER TABLE `buku_besar_cash_outs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `cash_in_trans`
--
ALTER TABLE `cash_in_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `cash_out_trans`
--
ALTER TABLE `cash_out_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_saldos`
--
ALTER TABLE `log_saldos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `main_cashs`
--
ALTER TABLE `main_cashs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `main_cash_trans`
--
ALTER TABLE `main_cash_trans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
