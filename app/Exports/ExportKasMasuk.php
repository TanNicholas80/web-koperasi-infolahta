<?php

namespace App\Exports;

use App\Exports\Sheets\KasMasukPerMothSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportKasMasuk implements WithMultipleSheets
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
            $sheets[] = new KasMasukPerMothSheet($this->year, $month);
        }

        return $sheets;
    }
}
