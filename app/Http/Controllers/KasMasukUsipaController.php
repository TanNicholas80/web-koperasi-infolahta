<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasMasukUsipa;
use App\Models\CashInUsipa;
use App\Models\KasUsipaTrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasMasukUsipaController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);
        // Retrieve all records
        $kasMasuk = CashInUsipa::all();

        // Extract IDs from the collection
        $ids = $kasMasuk->pluck('id_kas_usipa_trans'); // Get all id_main_cash values

        $totalDebet = KasUsipaTrans::whereYear('trans_date_usipa', $year)
            ->whereMonth('trans_date_usipa', $month)
            ->whereIn('id', $ids)
            ->sum('debet_transaction_usipa');

        // Use the IDs to filter main_cash_trans
        $kasUsipa = KasUsipaTrans::whereYear('trans_date_usipa', $year)
            ->whereMonth('trans_date_usipa', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date_usipa', 'asc')
            ->get();

        return view('kasMasukUsipa.index', compact('kasUsipa', 'totalDebet', 'year', 'month'));
    }

    public function exportKasMasukUsipa(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasMasukUsipa($year), 'KasMasukUsipa-' . $year . '.xlsx');
    }
}
