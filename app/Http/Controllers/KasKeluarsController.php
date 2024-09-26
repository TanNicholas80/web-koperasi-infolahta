<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasKeluar;
use App\Models\cash_out_trans;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasKeluarsController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        $kasKeluar = cash_out_trans::all();

        $ids = $kasKeluar->pluck('id_main_cash'); // Get all id_main_cash values

        $totalKredit = main_cash_trans::whereYear('trans_date', $year)
            ->whereMonth('trans_date', $month)
            ->whereIn('id', $ids)
            ->sum('kredit_transaction');

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

        return view('kasKeluar.index', compact('kasInduk', 'totalKredit', 'month', 'year'));
    }

    public function exportKasKeluar(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasKeluar($year), 'KasKeluar-' . $year . '.xlsx');
    }
}
