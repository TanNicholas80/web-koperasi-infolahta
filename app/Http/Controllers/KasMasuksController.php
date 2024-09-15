<?php

namespace App\Http\Controllers;

use App\Exports\ExportKasMasuk;
use App\Models\cash_in_trans;
use App\Models\main_cash_trans;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KasMasuksController extends Controller
{
    public function index()
    {
        // Retrieve all records
        $kasMasuk = cash_in_trans::all();

        // Extract IDs from the collection
        $ids = $kasMasuk->pluck('id_main_cash'); // Get all id_main_cash values

        // Use the IDs to filter main_cash_trans
        $kasInduk = main_cash_trans::whereIn('id', $ids)->get();

        return view('kasMasuk.index', compact('kasInduk'));
    }

    public function exportKasMasuk(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportKasMasuk($year), 'KasMasuk-' . $year . '.xlsx');
    }
}
