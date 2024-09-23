<?php

namespace App\Http\Controllers;

use App\Exports\ExportBukuKasMasukUsipa;
use App\Models\BukuBesarUsipaCashIn;
use App\Models\KasUsipaTrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BukuBesarKasMasukUsipaController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        $totals = BukuBesarUsipaCashIn::join('kas_usipa_trans', 'buku_besar_usipa_cash_ins.id_kas_usipa_trans', '=', 'kas_usipa_trans.id')
            ->select(DB::raw('
        SUM(buku_besar_usipa_cash_ins.kas) as total_kas,
        SUM(buku_besar_usipa_cash_ins.bank_sp) as total_bank_sp,
        SUM(buku_besar_usipa_cash_ins.bank_induk) as total_bank_induk,
        SUM(buku_besar_usipa_cash_ins.piutang_uang) as total_piutang_uang,
        SUM(buku_besar_usipa_cash_ins.piutang_brg_toko) as total_piutang_brg_toko,
        SUM(buku_besar_usipa_cash_ins.dana_sosial) as total_dana_sosial,
        SUM(buku_besar_usipa_cash_ins.dana_dik) as total_dana_dik,
        SUM(buku_besar_usipa_cash_ins.dana_pdk) as total_dana_pdk,
        SUM(buku_besar_usipa_cash_ins.resiko_kredit) as total_resiko_kredit,
        SUM(buku_besar_usipa_cash_ins.simp_pokok) as total_simp_pokok,
        SUM(buku_besar_usipa_cash_ins.simp_wajib) as total_simp_wajib,
        SUM(buku_besar_usipa_cash_ins.simp_khusus) as total_simp_khusus,
        SUM(buku_besar_usipa_cash_ins.penjualan_tunai) as total_penjualan_tunai,
        SUM(buku_besar_usipa_cash_ins.jasa_sp) as total_jasa_sp,
        SUM(buku_besar_usipa_cash_ins.provinsi) as total_provinsi,
        SUM(buku_besar_usipa_cash_ins.shu_puskop) as total_shu_puskop,
        SUM(buku_besar_usipa_cash_ins.modal_disetor) as total_modal_disetor
    '))
            ->whereYear('kas_usipa_trans.trans_date_usipa', $year)
            ->whereMonth('kas_usipa_trans.trans_date_usipa', $month)
            ->first();

        $bukuMasuk = BukuBesarUsipaCashIn::all();

        $ids = $bukuMasuk->pluck('id_kas_usipa_trans');

        $kasUsipa = KasUsipaTrans::whereYear('trans_date_usipa', $year)
            ->whereMonth('trans_date_usipa', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date_usipa', 'asc')
            ->get();

        return view('bukuMasukUsipa.index', compact('kasUsipa', 'bukuMasuk', 'totals', 'year', 'month'));
    }

    public function exportBukuBesarKasMasukUsipa(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportBukuKasMasukUsipa($year), 'BukuBesarKasMasukUsipa-' . $year . '.xlsx');
    }
}
