<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasKeluar;
use App\Models\cash_out_trans;
use App\Models\main_cash_trans;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasKeluarsController extends Controller
{
    public function index()
    {
        $kasKeluar = cash_out_trans::all();

        $ids = $kasKeluar->pluck('id_main_cash'); // Get all id_main_cash values

        $kasInduk = main_cash_trans::whereIn('id', $ids)->get();

        return view('kasKeluar.index', compact('kasInduk'));
    }

    public function exportKasKeluar(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasKeluar($year), 'KasKeluar-' . $year . '.xlsx');
    }
}
