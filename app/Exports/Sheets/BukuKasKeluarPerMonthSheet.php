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
        $monthName = Carbon::create()->month($this->month)->translatedFormat('F');

        return "{$monthName} - {$this->year}";
    }
}
