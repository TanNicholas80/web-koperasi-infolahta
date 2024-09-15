<?php

namespace App\Exports\Sheets;

use App\Models\buku_besar_cash_outs;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;


class BukuKasKeluarPerMonthSheet implements FromView, WithTitle
{
    protected $year;
    protected $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
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
