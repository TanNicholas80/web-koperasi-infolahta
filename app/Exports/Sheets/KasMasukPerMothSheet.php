<?php

namespace App\Exports\Sheets;

use App\Models\cash_in_trans;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class KasMasukPerMothSheet implements FromView, WithTitle
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
        $kasMasuk = cash_in_trans::all();
        
        // Extract IDs from the collection
        $ids = $kasMasuk->pluck('id_main_cash');

        // Use the IDs to filter main_cash_trans
        $kasInduk = main_cash_trans::whereYear('trans_date', $this->year)
        ->whereMonth('trans_date', $this->month)
        ->whereIn('id', $ids)
        ->orderBy('trans_date', 'asc')
        ->get();

        return view('kasMasuk.table_kas_masuk', [
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
