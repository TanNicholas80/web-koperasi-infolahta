<?php

namespace App\Exports\Sheets;

use App\Models\BukuBesarUsipaCashIn;
use App\Models\KasUsipaTrans;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuKasMasukUsipaPerMonthSheet implements FromView, WithTitle, WithColumnWidths, WithStyles
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
        $currencyColumns = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V'];
        foreach ($currencyColumns as $column) {
            $sheet->getStyle($column . '2:' . $column . '1000')->getNumberFormat()->setFormatCode('[$Rp-421] #,##0.00');
        }
        foreach (range('A', 'V') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
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
            'T' => 20,  // Provisi
            'U' => 20,  // SHU Puskop
            'V' => 20,  // Investasi USIPA
        ];
    }

    public function view(): View
    {
        $totals = BukuBesarUsipaCashIn::join('kas_usipa_trans', 'buku_besar_usipa_cash_ins.id_kas_usipa_trans', '=', 'kas_usipa_trans.id')
            ->select(DB::raw('
        SUM(buku_besar_usipa_cash_ins.kas) as total_kas,
        SUM(buku_besar_usipa_cash_ins.bank_sp) as total_bank_sp,
        SUM(buku_besar_usipa_cash_ins.bank_induk) as total_bank_induk,
        SUM(buku_besar_usipa_cash_ins.piutang_uang) as total_piutang_uang,
        SUM(buku_besar_usipa_cash_ins.piutang_brg_toko) as total_piutang_brg_toko,
        SUM(buku_besar_usipa_cash_ins.dana_sosial) as total_dana_sosial,
        SUM(buku_besar_usipa_cash_ins.dana_dik) as total_dana_dik,
        SUM(buku_besar_usipa_cash_ins.dana_pdk) as total_dana_pdk,
        SUM(buku_besar_usipa_cash_ins.resiko_kredit) as total_resiko_kredit,
        SUM(buku_besar_usipa_cash_ins.simp_pokok) as total_simp_pokok,
        SUM(buku_besar_usipa_cash_ins.simp_wajib) as total_simp_wajib,
        SUM(buku_besar_usipa_cash_ins.simp_khusus) as total_simp_khusus,
        SUM(buku_besar_usipa_cash_ins.penjualan_tunai) as total_penjualan_tunai,
        SUM(buku_besar_usipa_cash_ins.jasa_sp) as total_jasa_sp,
        SUM(buku_besar_usipa_cash_ins.provisi) as total_provisi,
        SUM(buku_besar_usipa_cash_ins.shu_puskop) as total_shu_puskop,
        SUM(buku_besar_usipa_cash_ins.modal_disetor) as total_modal_disetor
    '))
            ->whereYear('kas_usipa_trans.trans_date_usipa', $this->year)
            ->whereMonth('kas_usipa_trans.trans_date_usipa', $this->month)
            ->first();

        $bukuMasuk = BukuBesarUsipaCashIn::all();

        $ids = $bukuMasuk->pluck('id_kas_usipa_trans');

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

        return view('bukuMasukUsipa.table_buku_masuk_usipa', [
            'kasUsipa' => $kasUsipa,
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