<?php

namespace App\Exports\Sheets;

use App\Models\buku_besar_cash_ins;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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
        $sheet->getStyle('A1:V1')->applyFromArray([
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
            'B' => 25,  // Keterangan
            'C' => 15,  // Status
            'D' => 15,  // Periode
            'E' => 20,  // Kas
            'F' => 20,  // Bank SP
            'G' => 20,  // Bank Induk
            'H' => 20,  // Piutang Uang
            'I' => 20,  // Piutang Barang Toko
            'J' => 20,  // Dana Sosial
            'K' => 25,  // Dana Pendidikan (Dik)
            'L' => 25,  // Dana Pengembangan (PDK)
            'M' => 25,  // Resiko Kredit
            'N' => 25,  // Simpanan Pokok
            'O' => 25,  // Simpanan Wajib
            'P' => 25,  // Simpanan Khusus
            'Q' => 25,  // Simpanan Tunai
            'R' => 20,  // Jasa SP
            'S' => 20,  // Provinsi
            'T' => 20,  // SHU Puskop
            'U' => 20,  // Investasi USIPA
            'V' => 20,  // Lain-Lain
        ];
    }

    public function view(): View
    {
        $bukuMasuk = buku_besar_cash_ins::all();

        $ids = $bukuMasuk->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
            ->whereMonth('trans_date', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuMasuk.table_buku_masuk', [
            'kasInduk' => $kasInduk,
            'bukuMasuk' => $bukuMasuk
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
