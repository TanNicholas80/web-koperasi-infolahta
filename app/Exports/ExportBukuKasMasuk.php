<?php

namespace App\Exports;

use App\Exports\Sheets\BukuKasMasukPerMonthSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportBukuKasMasuk implements WithMultipleSheets
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
            $sheets[] = new BukuKasMasukPerMonthSheet($this->year, $month);
        }

        return $sheets;
    }
}
