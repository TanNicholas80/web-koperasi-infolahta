<?php

namespace App\Exports\Sheets;

use App\Models\cash_out_trans;
use App\Models\CashOutUsipa;
use App\Models\KasUsipaTrans;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// class KasKeluarUsipaPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
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
//         $sheet->getStyle('A1:F1')->applyFromArray([
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
//         ];
//     }

//     public function view(): View
//     {

//         $kasKeluar = CashOutUsipa::all();

//         $ids = $kasKeluar->pluck('id_kas_usipa_trans'); // Get all id_main_cash values

//         $totalKredit = KasUsipaTrans::whereYear('trans_date_usipa', $this->year)
//             ->whereMonth('trans_date_usipa', $this->month)
//             ->whereIn('id', $ids)
//             ->sum('kredit_transaction_usipa');

//         $kasUsipa = KasUsipaTrans::whereYear('trans_date_usipa', $this->year)
//             ->whereMonth('trans_date_usipa', $this->month)
//             ->whereIn('id', $ids)
//             ->orderBy('trans_date_usipa', 'asc')
//             ->get();

//         return view('kasKeluarUsipa.table_kas_keluar_usipa', [
//             'kasUsipa' => $kasUsipa,
//             'totalKredit' => $totalKredit
//         ]);
//     }

//     /**
//      * Define the sheet title
//      */
//     public function title(): string
//     // {
//     //     $monthName = Carbon::create()->month($this->month)->translatedFormat('F');

//     //     return "{$monthName} - {$this->year}";
//     // }
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
class KasKeluarUsipaPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
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

        // Atur teks agar rata kiri dan atas
        $sheet->getStyle('A2:Z1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A2:Z1000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        // Menambahkan background color hanya untuk header (baris pertama)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'], // Background color kuning
            ],
            'font' => [
                'bold' => true, // Membuat teks header menjadi tebal
            ],
        ]);

        // Mengatur agar kolom menyesuaikan dengan isi
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Mendapatkan baris dan kolom terakhir yang terisi data
        $highestRow = $sheet->getHighestRow(); // Mendapatkan baris terakhir yang berisi data
        $highestColumn = $sheet->getHighestColumn(); // Mendapatkan kolom terakhir yang berisi data

        // Menambahkan border hanya pada kolom dan baris yang terisi data
        $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Format kolom D, E, F sebagai currency Rupiah
        $rupiahFormat = 'Rp #,##0.00'; // Format mata uang Rupiah
        $sheet->getStyle("F2:F100{$highestRow}")->getNumberFormat()->setFormatCode($rupiahFormat);
    }

    public function columnWidths(): array
    {
        // Tidak perlu mengatur ukuran kolom secara manual, menggunakan auto-size di styles()
        return [];
    }

    public function view(): View
    {
        $kasKeluar = CashOutUsipa::all();
        $ids = $kasKeluar->pluck('id_kas_usipa_trans'); // Get all id_main_cash values

        $totalKredit = KasUsipaTrans::whereYear('trans_date_usipa', $this->year)
            ->whereMonth('trans_date_usipa', $this->month)
            ->whereIn('id', $ids)
            ->sum('kredit_transaction_usipa');

        $kasUsipa = KasUsipaTrans::whereYear('trans_date_usipa', $this->year)
            ->whereMonth('trans_date_usipa', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date_usipa', 'asc')
            ->get()
            ->map(function ($item) {
                // Memformat trans_date menjadi hanya tanggal (hari)
                $item->trans_date = \Carbon\Carbon::parse($item->trans_date)->format('d');
                return $item;
            });

        return view('kasKeluarUsipa.table_kas_keluar_usipa', [
            'kasUsipa' => $kasUsipa,
            'totalKredit' => $totalKredit
        ]);
    }

    public function title(): string
    {
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

        $monthName = $months[$this->month];

        return "{$monthName} - {$this->year}";
    }
}
