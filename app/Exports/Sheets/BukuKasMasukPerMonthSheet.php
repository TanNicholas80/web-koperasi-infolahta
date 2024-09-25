<?php

namespace App\Exports\Sheets;

use App\Models\buku_besar_cash_ins;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// class BukuKasMasukPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
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
//         $sheet->getStyle('A1:W1')->applyFromArray([
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
//         ];
//     }

//     public function view(): View
//     {
//         $totals = buku_besar_cash_ins::join('main_cash_trans', 'buku_besar_cash_ins.id_main_cash_trans', '=', 'main_cash_trans.id')
//             ->select(DB::raw('
//             SUM(buku_besar_cash_ins.kas) as total_kas,
//             SUM(buku_besar_cash_ins.bank_sp) as total_bank_sp,
//             SUM(buku_besar_cash_ins.bank_induk) as total_bank_induk,
//             SUM(buku_besar_cash_ins.piutang_uang) as total_piutang_uang,
//             SUM(buku_besar_cash_ins.piutang_barang_toko) as total_piutang_barang_toko,
//             SUM(buku_besar_cash_ins.dana_sosial) as total_dana_sosial,
//             SUM(buku_besar_cash_ins.dana_dik) as total_dana_dik,
//             SUM(buku_besar_cash_ins.dana_pdk) as total_dana_pdk,
//             SUM(buku_besar_cash_ins.resiko_kredit) as total_resiko_kredit,
//             SUM(buku_besar_cash_ins.simpanan_pokok) as total_simpanan_pokok,
//             SUM(buku_besar_cash_ins.sipanan_wajib) as total_sipanan_wajib,
//             SUM(buku_besar_cash_ins.sipanan_khusus) as total_sipanan_khusus,
//             SUM(buku_besar_cash_ins.sipanan_tunai) as total_sipanan_tunai,
//             SUM(buku_besar_cash_ins.jasa_sp) as total_jasa_sp,
//             SUM(buku_besar_cash_ins.provinsi) as total_provinsi,
//             SUM(buku_besar_cash_ins.shu_puskop) as total_shu_puskop,
//             SUM(buku_besar_cash_ins.inv_usipa) as total_inv_usipa,
//             SUM(buku_besar_cash_ins.lain_lain) as total_lain_lain
//         '))
//             ->whereYear('main_cash_trans.trans_date', $this->year)
//             ->whereMonth('main_cash_trans.trans_date', $this->month)
//             ->first();

//         $bukuMasuk = buku_besar_cash_ins::all();

//         $ids = $bukuMasuk->pluck('id_main_cash_trans');

//         $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
//             ->whereMonth('trans_date', $this->month)
//             ->whereIn('id', $ids)
//             ->orderBy('trans_date', 'asc')
//             ->get();

//         return view('bukuMasuk.table_buku_masuk', [
//             'kasInduk' => $kasInduk,
//             'bukuMasuk' => $bukuMasuk,
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
class BukuKasMasukPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
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

        // Mengatur alignment menjadi rata kiri atas
        $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A2:Z1000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Menambahkan background color hanya untuk header (baris pertama)
        $sheet->getStyle('A1:W1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'], // Background color kuning
            ],
            'font' => [
                'bold' => true, // Membuat teks header menjadi tebal
            ],
        ]);

        // Mengubah format currency untuk kolom F sampai W ke format Rupiah
        $currencyColumns = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W'];
        foreach ($currencyColumns as $column) {
            $sheet->getStyle($column . '2:' . $column . '1000')->getNumberFormat()->setFormatCode('[$Rp-421] #,##0.00');
        }
        foreach (range('A', 'W') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // Date
            'B' => 10,  // Date
            'C' => 20,  // Keterangan
            'D' => 10,  // Status
            'E' => 10,  // Periode
            'F' => 15,  // Kas
            'G' => 15,  // Bank SP
            'H' => 15,  // Bank Induk
            'I' => 15,  // Piutang Uang
            'J' => 15,  // Piutang Barang Toko
            'K' => 15,  // Dana Sosial
            'L' => 20,  // Dana Pendidikan (Dik)
            'M' => 20,  // Dana Pengembangan (PDK)
            'N' => 20,  // Resiko Kredit
            'O' => 20,  // Simpanan Pokok
            'P' => 20,  // Simpanan Wajib
            'Q' => 20,  // Simpanan Khusus
            'R' => 20,  // Simpanan Tunai
            'S' => 15,  // Jasa SP
            'T' => 15,  // Provinsi
            'U' => 15,  // SHU Puskop
            'V' => 15,  // Investasi USIPA
            'W' => 15,  // Lain-Lain
        ];
    }

    public function view(): View
    {
        $totals = buku_besar_cash_ins::join('main_cash_trans', 'buku_besar_cash_ins.id_main_cash_trans', '=', 'main_cash_trans.id')
            ->select(DB::raw('
            SUM(buku_besar_cash_ins.kas) as total_kas,
            SUM(buku_besar_cash_ins.bank_sp) as total_bank_sp,
            SUM(buku_besar_cash_ins.bank_induk) as total_bank_induk,
            SUM(buku_besar_cash_ins.piutang_uang) as total_piutang_uang,
            SUM(buku_besar_cash_ins.piutang_barang_toko) as total_piutang_barang_toko,
            SUM(buku_besar_cash_ins.dana_sosial) as total_dana_sosial,
            SUM(buku_besar_cash_ins.dana_dik) as total_dana_dik,
            SUM(buku_besar_cash_ins.dana_pdk) as total_dana_pdk,
            SUM(buku_besar_cash_ins.resiko_kredit) as total_resiko_kredit,
            SUM(buku_besar_cash_ins.simpanan_pokok) as total_simpanan_pokok,
            SUM(buku_besar_cash_ins.sipanan_wajib) as total_sipanan_wajib,
            SUM(buku_besar_cash_ins.sipanan_khusus) as total_sipanan_khusus,
            SUM(buku_besar_cash_ins.penjualan_tunai) as total_penjualan_tunai,
            SUM(buku_besar_cash_ins.jasa_sp) as total_jasa_sp,
            SUM(buku_besar_cash_ins.provinsi) as total_provinsi,
            SUM(buku_besar_cash_ins.shu_puskop) as total_shu_puskop,
            SUM(buku_besar_cash_ins.inv_usipa) as total_inv_usipa,
            SUM(buku_besar_cash_ins.lain_lain) as total_lain_lain
        '))
            ->whereYear('main_cash_trans.trans_date', $this->year)
            ->whereMonth('main_cash_trans.trans_date', $this->month)
            ->first();

        $bukuMasuk = buku_besar_cash_ins::all();

        $ids = $bukuMasuk->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
            ->whereMonth('trans_date', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuMasuk.table_buku_masuk', [
            'kasInduk' => $kasInduk,
            'bukuMasuk' => $bukuMasuk,
            'totals' => $totals
        ]);
    }

    /**
     * Define the sheet title
     */
    public function title(): string
    {
        // Array singkatan bulan dalam bahasa Indonesia
        $months = [
            1 => 'JAN',
            2 => 'FEB',
            3 => 'MAR',
            4 => 'APR',
            5 => 'MEI',
            6 => 'JUN',
            7 => 'JUL',
            8 => 'AGU',
            9 => 'SEP',
            10 => 'OKT',
            11 => 'NOV',
            12 => 'DES'
        ];

        // Mengambil singkatan bulan berdasarkan month yang diterima
        $monthName = $months[$this->month];

        // Mengembalikan singkatan bulan beserta tahun
        return "{$monthName}";
    }
}
?>