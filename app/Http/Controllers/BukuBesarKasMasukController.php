<?php

namespace App\Http\Controllers;

use App\Exports\ExportBukuKasMasuk;
use App\Models\buku_besar_cash_ins;
use App\Models\main_cash_trans;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BukuBesarKasMasukController extends Controller
{
    public function index() {
        $bukuMasuk = buku_besar_cash_ins::all();

        $ids = $bukuMasuk->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereIn('id', $ids)->get();

        return view('bukuMasuk.index', compact('kasInduk', 'bukuMasuk'));
    }

    public function exportBukuBesarKasMasuk(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportBukuKasMasuk($year), 'BukuBesarKasMasuk-' . $year . '.xlsx');
    }
}
