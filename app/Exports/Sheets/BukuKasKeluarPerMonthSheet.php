<?php

namespace App\Exports\Sheets;

use App\Models\buku_besar_cash_outs;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

        $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Menambahkan border pada kolom A hingga E
        $sheet->getStyle('A1:Z1000')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

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
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Date
            'B' => 15,  // Date
            'C' => 25,  // Keterangan
            'D' => 15,  // Status
            'E' => 15,  // Periode
            'F' => 20,  // Kas
            'G' => 20,  // Bank SP
            'H' => 20,  // Bank Induk
            'I' => 20,  // Piutang Uang
            'J' => 20,  // Piutang Barang Toko
            'K' => 20,  // Dana Sosial
            'L' => 25,  // Dana Pendidikan (Dik)
            'M' => 25,  // Dana Pengembangan (PDK)
            'N' => 25,  // Resiko Kredit
            'O' => 25,  // Simpanan Pokok
            'P' => 25,  // Simpanan Wajib
            'Q' => 25,  // Simpanan Khusus
            'R' => 25,  // Simpanan Tunai
            'S' => 20,  // Jasa SP
            'T' => 20,  // Provinsi
            'U' => 20,  // SHU Puskop
            'V' => 20,  // Investasi USIPA
            'W' => 20,  // Lain-Lain
            'X' => 20,  // Lain-Lain
            'Y' => 20,  // Lain-Lain
            'Z' => 20,  // Lain-Lain
            'AA' => 20,  // Lain-Lain
            'AB' => 20,  // Lain-Lain
            'AC' => 20,  // Lain-Lain
            'AD' => 20,  // Lain-Lain
            'AE' => 20,  // Lain-Lain
            'AF' => 20,  // Lain-Lain
            'AG' => 20,  // Lain-Lain
            'AH' => 20,  // Lain-Lain
        ];
    }


    public function view(): View
    {
        $bukuKeluar = buku_besar_cash_outs::all();

        $ids = $bukuKeluar->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
            ->whereMonth('trans_date', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuKeluar.table_buku_keluar', [
            'kasInduk' => $kasInduk,
            'bukuKeluar' => $bukuKeluar
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
