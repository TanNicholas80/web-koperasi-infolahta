<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasMasuk;
use App\Models\cash_in_trans;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasMasuksController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);
        // Retrieve all records
        $kasMasuk = cash_in_trans::all();

        // Extract IDs from the collection
        $ids = $kasMasuk->pluck('id_main_cash'); // Get all id_main_cash values

        $totalDebet = main_cash_trans::whereYear('trans_date', $year)
            ->whereMonth('trans_date', $month)
            ->whereIn('id', $ids)
            ->sum('debet_transaction');

        // Use the IDs to filter main_cash_trans
        $kasInduk = main_cash_trans::whereYear('trans_date', $year)
            ->whereMonth('trans_date', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get()
            ->map(function ($item) {
                // Memformat trans_date menjadi hanya tanggal (hari)
                $item->trans_date = \Carbon\Carbon::parse($item->trans_date)->format('d');
                return $item;
            });

        return view('kasMasuk.index', compact('kasInduk', 'totalDebet', 'year', 'month'));
    }

    public function exportKasMasuk(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasMasuk($year), 'KasMasuk-' . $year . '.xlsx');
    }
}
