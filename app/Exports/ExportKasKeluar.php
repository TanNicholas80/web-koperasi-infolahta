<?php

namespace App\Exports;

use App\Exports\Sheets\KasKeluarPerMonthSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportKasKeluar implements WithMultipleSheets
{
    protected $year;

    public function __construct(int $year)
    {
        $this->year = $year;
    }

    /**
     * Return an array of sheets
     */
    public function sheets(): array
    {
        $sheets = [];

        for ($month = 1; $month <= 12; $month++) {
            $sheets[] = new KasKeluarPerMonthSheet($this->year, $month);
        }

        return $sheets;
    }
}
