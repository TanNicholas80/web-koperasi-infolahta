<?php

namespace App\Exports\Sheets;

use App\Models\cash_out_trans;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class KasKeluarPerMonthSheet implements FromView, WithTitle
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
        // Get the `cash_in_trans` data
        $kasKeluar = cash_out_trans::all();

        // Extract IDs from the collection
        $ids = $kasKeluar->pluck('id_main_cash');

        // Use the IDs to filter main_cash_trans
        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
            ->whereMonth('trans_date', $this->month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('kasKeluar.table_kas_keluar', [
            'kasInduk' => $kasInduk
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
