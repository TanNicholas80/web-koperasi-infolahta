<?php

namespace App\Http\Controllers;

use App\Exports\ExportBukuKasKeluar;
use App\Models\buku_besar_cash_outs;
use App\Models\main_cash_trans;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BukuBesarKasKeluarController extends Controller
{
    public function index() {
        $bukuKeluar = buku_besar_cash_outs::all();

        $ids = $bukuKeluar->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereIn('id', $ids)->get();

        return view('bukuKeluar.index', compact('kasInduk', 'bukuKeluar'));
    }

    public function exportBukuBesarKasKeluar(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportBukuKasKeluar($year), 'BukuBesarKasKeluar-' . $year . '.xlsx');
    }
}
