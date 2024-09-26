<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasKeluarUsipa;
use App\Models\CashOutUsipa;
use App\Models\KasUsipaTrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasKeluarUsipaController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        $kasKeluar = CashOutUsipa::all();

        $ids = $kasKeluar->pluck('id_kas_usipa_trans'); // Get all id_main_cash values

        $totalKredit = KasUsipaTrans::whereYear('trans_date_usipa', $year)
            ->whereMonth('trans_date_usipa', $month)
            ->whereIn('id', $ids)
            ->sum('kredit_transaction_usipa');

        $kasUsipa = KasUsipaTrans::whereYear('trans_date_usipa', $year)
            ->whereMonth('trans_date_usipa', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date_usipa', 'asc')
            ->get()
            ->map(function ($item) {
                // Memformat trans_date menjadi hanya tanggal (hari)
                $item->trans_date_usipa = \Carbon\Carbon::parse($item->trans_date_usipa)->format('d');
                return $item;
            });

        return view('kasKeluarUsipa.index', compact('kasUsipa', 'totalKredit', 'month', 'year'));
    }

    public function exportKasKeluarUsipa(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasKeluarUsipa($year), 'KasKeluarUsipa-' . $year . '.xlsx');
    }
}
