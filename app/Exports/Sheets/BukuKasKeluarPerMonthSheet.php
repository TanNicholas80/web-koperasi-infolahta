<?php

namespace App\Exports\Sheets;

use App\Models\buku_besar_cash_outs;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// class BukuKasKeluarPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
// {
//     protected $year;
//     protected $month;

//     public function __construct(int $year, int $month)
//     {
//         $this->year = $year;
//         $this->month = $month;
//     }

//     public function styles(Worksheet $sheet)
//     {
//         // Mengatur wrap text pada kolom tertentu
//         $sheet->getStyle('A2:Z1000')->getAlignment()->setWrapText(true);

//         // Mengatur tinggi baris secara otomatis sesuai isi
//         $sheet->getDefaultRowDimension()->setRowHeight(-1);

//         $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
//         $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

//         // Menambahkan background color hanya untuk header (baris pertama)
//         $sheet->getStyle('A1:AH1')->applyFromArray([
//             'fill' => [
//                 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
//                 'startColor' => ['argb' => 'FFFF00'], // Background color kuning
//             ],
//             'font' => [
//                 'bold' => true, // Membuat teks header menjadi tebal
//             ],
//         ]);
//     }

//     public function columnWidths(): array
//     {
//         return [
//             'A' => 15,  // Date
//             'B' => 15,  // Date
//             'C' => 25,  // Keterangan
//             'D' => 15,  // Status
//             'E' => 15,  // Periode
//             'F' => 20,  // Kas
//             'G' => 20,  // Bank SP
//             'H' => 20,  // Bank Induk
//             'I' => 20,  // Piutang Uang
//             'J' => 20,  // Piutang Barang Toko
//             'K' => 20,  // Dana Sosial
//             'L' => 25,  // Dana Pendidikan (Dik)
//             'M' => 25,  // Dana Pengembangan (PDK)
//             'N' => 25,  // Resiko Kredit
//             'O' => 25,  // Simpanan Pokok
//             'P' => 25,  // Simpanan Wajib
//             'Q' => 25,  // Simpanan Khusus
//             'R' => 25,  // Simpanan Tunai
//             'S' => 20,  // Jasa SP
//             'T' => 20,  // Provinsi
//             'U' => 20,  // SHU Puskop
//             'V' => 20,  // Investasi USIPA
//             'W' => 20,  // Lain-Lain
//             'X' => 20,  // Lain-Lain
//             'Y' => 20,  // Lain-Lain
//             'Z' => 20,  // Lain-Lain
//             'AA' => 20,  // Lain-Lain
//             'AB' => 20,  // Lain-Lain
//             'AC' => 20,  // Lain-Lain
//             'AD' => 20,  // Lain-Lain
//             'AE' => 20,  // Lain-Lain
//             'AF' => 20,  // Lain-Lain
//             'AG' => 20,  // Lain-Lain
//             'AH' => 20,  // Lain-Lain
//         ];
//     }


//     public function view(): View
//     {
//         $totals = buku_besar_cash_outs::join('main_cash_trans', 'buku_besar_cash_outs.id_main_cash_trans', '=', 'main_cash_trans.id')
//             ->select(DB::raw('
//             SUM(buku_besar_cash_outs.kas) as total_kas,
//             SUM(buku_besar_cash_outs.bank_sp) as total_bank_sp,
//             SUM(buku_besar_cash_outs.bank_induk) as total_bank_induk,
//             SUM(buku_besar_cash_outs.simpan_pinjam) as total_simpan_pinjam,
//             SUM(buku_besar_cash_outs.inventaris) as total_inventaris,
//             SUM(buku_besar_cash_outs.penyertaan_puskop) as total_penyertaan_puskop,
//             SUM(buku_besar_cash_outs.hutang_toko) as total_hutang_toko,
//             SUM(buku_besar_cash_outs.dana_pengurus) as total_dana_pengurus,
//             SUM(buku_besar_cash_outs.dana_karyawan) as total_dana_karyawan,
//             SUM(buku_besar_cash_outs.dana_sosial) as total_dana_sosial,
//             SUM(buku_besar_cash_outs.dana_dik) as total_dana_dik,
//             SUM(buku_besar_cash_outs.dana_pdk) as total_dana_pdk,
//             SUM(buku_besar_cash_outs.simp_pokok) as total_simp_pokok,
//             SUM(buku_besar_cash_outs.simp_wajib) as total_simp_wajib,
//             SUM(buku_besar_cash_outs.simp_khusus) as total_simp_khusus,
//             SUM(buku_besar_cash_outs.shu_angg) as total_shu_angg,
//             SUM(buku_besar_cash_outs.pembelian_toko) as total_pembelian_toko,
//             SUM(buku_besar_cash_outs.biaya_insentif) as total_biaya_insentif,
//             SUM(buku_besar_cash_outs.biaya_atk) as total_biaya_atk,
//             SUM(buku_besar_cash_outs.biaya_transport) as total_biaya_transport,
//             SUM(buku_besar_cash_outs.biaya_pembinaan) as total_biaya_pembinaan,
//             SUM(buku_besar_cash_outs.biaya_pembungkus) as total_biaya_pembungkus,
//             SUM(buku_besar_cash_outs.biaya_rat) as total_biaya_rat,
//             SUM(buku_besar_cash_outs.biaya_thr) as total_biaya_thr,
//             SUM(buku_besar_cash_outs.biaya_pajak) as total_biaya_pajak,
//             SUM(buku_besar_cash_outs.biaya_admin) as total_biaya_admin,
//             SUM(buku_besar_cash_outs.biaya_training) as total_biaya_training,
//             SUM(buku_besar_cash_outs.inv_usipa) as total_inv_usipa,
//             SUM(buku_besar_cash_outs.lain_lain) as total_lain_lain
//         '))
//             ->whereYear('main_cash_trans.trans_date', $this->year)
//             ->whereMonth('main_cash_trans.trans_date', $this->month)
//             ->first();

//         $bukuKeluar = buku_besar_cash_outs::all();

//         $ids = $bukuKeluar->pluck('id_main_cash_trans');

//         $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
//             ->whereMonth('trans_date', $this->month)
//             ->whereIn('id', $ids)
//             ->orderBy('trans_date', 'asc')
//             ->get();

//         return view('bukuKeluar.table_buku_keluar', [
//             'kasInduk' => $kasInduk,
//             'bukuKeluar' => $bukuKeluar,
//             'totals' => $totals
//         ]);
//     }

//     /**
//      * Define the sheet title
//      */
//     public function title(): string
//     {
//         // Array singkatan bulan dalam bahasa Indonesia
//         $months = [
//             1 => 'JAN',
//             2 => 'FEB',
//             3 => 'MAR',
//             4 => 'APR',
//             5 => 'MEI',
//             6 => 'JUN',
//             7 => 'JUL',
//             8 => 'AGU',
//             9 => 'SEP',
//             10 => 'OKT',
//             11 => 'NOV',
//             12 => 'DES'
//         ];

//         // Mengambil singkatan bulan berdasarkan month yang diterima
//         $monthName = $months[$this->month];

//         // Mengembalikan singkatan bulan beserta tahun
//         return "{$monthName}";
//     }
// }

class BukuKasKeluarPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
{
    protected $year;
    protected $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur wrap text pada kolom tertentu
        $sheet->getStyle('A2:Z1000')->getAlignment()->setWrapText(true);

        // Mengatur tinggi baris secara otomatis sesuai isi
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Mengatur teks rata kiri dan atas
        $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A2:Z1000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Menambahkan background color hanya untuk header (baris pertama)
        $sheet->getStyle('A1:AH1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'], // Background color kuning
            ],
            'font' => [
                'bold' => true, // Membuat teks header menjadi tebal
            ],
        ]);

        // Mendapatkan baris dan kolom terakhir yang terisi data
        $highestRow = $sheet->getHighestRow(); // Baris terakhir yang berisi data

        // Mengatur auto-size agar kolom menjadi rapat dengan teks
        foreach (range('A', 'AH') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Format kolom F hingga AH sebagai currency Rupiah
        $rupiahFormat = 'Rp #,##0.00'; // Format mata uang Rupiah
        $sheet->getStyle("F2:AH{$highestRow}")->getNumberFormat()->setFormatCode($rupiahFormat);
    }

    public function columnWidths(): array
    {
        // Tidak perlu mengatur ukuran kolom secara manual, menggunakan auto-size di styles()
        return [];
    }

    public function view(): View
    {
        $totals = buku_besar_cash_outs::join('main_cash_trans', 'buku_besar_cash_outs.id_main_cash_trans', '=', 'main_cash_trans.id')
            ->select(DB::raw('
            SUM(buku_besar_cash_outs.kas) as total_kas,
            SUM(buku_besar_cash_outs.bank_sp) as total_bank_sp,
            SUM(buku_besar_cash_outs.bank_induk) as total_bank_induk,
            SUM(buku_besar_cash_outs.piutang_uang) as total_piutang_uang,
            SUM(buku_besar_cash_outs.inventaris) as total_inventaris,
            SUM(buku_besar_cash_outs.penyertaan_puskop) as total_penyertaan_puskop,
            SUM(buku_besar_cash_outs.hutang_toko) as total_hutang_toko,
            SUM(buku_besar_cash_outs.dana_pengurus) as total_dana_pengurus,
            SUM(buku_besar_cash_outs.dana_karyawan) as total_dana_karyawan,
            SUM(buku_besar_cash_outs.dana_sosial) as total_dana_sosial,
            SUM(buku_besar_cash_outs.dana_dik) as total_dana_dik,
            SUM(buku_besar_cash_outs.dana_pdk) as total_dana_pdk,
            SUM(buku_besar_cash_outs.simp_pokok) as total_simp_pokok,
            SUM(buku_besar_cash_outs.simp_wajib) as total_simp_wajib,
            SUM(buku_besar_cash_outs.simp_khusus) as total_simp_khusus,
            SUM(buku_besar_cash_outs.shu_angg) as total_shu_angg,
            SUM(buku_besar_cash_outs.pembelian_toko) as total_pembelian_toko,
            SUM(buku_besar_cash_outs.biaya_insentif) as total_biaya_insentif,
            SUM(buku_besar_cash_outs.biaya_atk) as total_biaya_atk,
            SUM(buku_besar_cash_outs.biaya_transport) as total_biaya_transport,
            SUM(buku_besar_cash_outs.biaya_pembinaan) as total_biaya_pembinaan,
            SUM(buku_besar_cash_outs.biaya_pembungkus) as total_biaya_pembungkus,
            SUM(buku_besar_cash_outs.biaya_rat) as total_biaya_rat,
            SUM(buku_besar_cash_outs.biaya_thr) as total_biaya_thr,
            SUM(buku_besar_cash_outs.biaya_pajak) as total_biaya_pajak,
            SUM(buku_besar_cash_outs.biaya_admin) as total_biaya_admin,
            SUM(buku_besar_cash_outs.biaya_training) as total_biaya_training,
            SUM(buku_besar_cash_outs.inv_usipa) as total_inv_usipa,
            SUM(buku_besar_cash_outs.lain_lain) as total_lain_lain
        '))
            ->whereYear('main_cash_trans.trans_date', $this->year)
            ->whereMonth('main_cash_trans.trans_date', $this->month)
            ->first();

        $bukuKeluar = buku_besar_cash_outs::all();
        $ids = $bukuKeluar->pluck('id_main_cash_trans');
        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
            ->whereMonth('trans_date', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuKeluar.table_buku_keluar', [
            'kasInduk' => $kasInduk,
            'bukuKeluar' => $bukuKeluar,
            'totals' => $totals
        ]);
    }

    public function title(): string
    {
        $months = [
            1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI',
            6 => 'JUN', 7 => 'JUL', 8 => 'AGU', 9 => 'SEP', 10 => 'OKT',
            11 => 'NOV', 12 => 'DES'
        ];

        $monthName = $months[$this->month];
        return "{$monthName} - {$this->year}";
    }
}