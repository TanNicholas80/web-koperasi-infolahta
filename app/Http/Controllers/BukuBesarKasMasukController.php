<?php

namespace App\Http\Controllers;

use App\Exports\ExportBukuKasMasuk;
use App\Models\buku_besar_cash_ins;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BukuBesarKasMasukController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        $totals = buku_besar_cash_ins::join('main_cash_trans', 'buku_besar_cash_ins.id_main_cash_trans', '=', 'main_cash_trans.id')
        ->select(DB::raw('
        SUM(buku_besar_cash_ins.kas) as total_kas,
        SUM(buku_besar_cash_ins.bank_sp) as total_bank_sp,
        SUM(buku_besar_cash_ins.bank_induk) as total_bank_induk,
        SUM(buku_besar_cash_ins.piutang_uang) as total_piutang_uang,
        SUM(buku_besar_cash_ins.piutang_barang_toko) as total_piutang_barang_toko,
        SUM(buku_besar_cash_ins.dana_sosial) as total_dana_sosial,
        SUM(buku_besar_cash_ins.dana_dik) as total_dana_dik,
        SUM(buku_besar_cash_ins.dana_pdk) as total_dana_pdk,
        SUM(buku_besar_cash_ins.resiko_kredit) as total_resiko_kredit,
        SUM(buku_besar_cash_ins.simpanan_pokok) as total_simpanan_pokok,
        SUM(buku_besar_cash_ins.sipanan_wajib) as total_sipanan_wajib,
        SUM(buku_besar_cash_ins.sipanan_khusus) as total_sipanan_khusus,
        SUM(buku_besar_cash_ins.sipanan_tunai) as total_sipanan_tunai,
        SUM(buku_besar_cash_ins.jasa_sp) as total_jasa_sp,
        SUM(buku_besar_cash_ins.provinsi) as total_provinsi,
        SUM(buku_besar_cash_ins.shu_puskop) as total_shu_puskop,
        SUM(buku_besar_cash_ins.inv_usipa) as total_inv_usipa,
        SUM(buku_besar_cash_ins.lain_lain) as total_lain_lain
    '))
        ->whereYear('main_cash_trans.trans_date', $year)
        ->whereMonth('main_cash_trans.trans_date', $month)
        ->first();

        $bukuMasuk = buku_besar_cash_ins::all();

        $ids = $bukuMasuk->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereYear('trans_date', $year)
            ->whereMonth('trans_date', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuMasuk.index', compact('kasInduk', 'bukuMasuk', 'totals', 'year', 'month'));
    }

    public function exportBukuBesarKasMasuk(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportBukuKasMasuk($year), 'BukuBesarKasMasuk-' . $year . '.xlsx');
    }
}
