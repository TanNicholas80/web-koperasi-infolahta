<?php

namespace App\Http\Controllers;

use App\Exports\ExportBukuKasKeluar;
use App\Models\buku_besar_cash_outs;
use App\Models\main_cash_trans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BukuBesarKasKeluarController extends Controller
{
    public function index(Request $req)
    {
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        $totals = buku_besar_cash_outs::join('main_cash_trans', 'buku_besar_cash_outs.id_main_cash_trans', '=', 'main_cash_trans.id')
            ->select(DB::raw('
            SUM(buku_besar_cash_outs.kas) as total_kas,
            SUM(buku_besar_cash_outs.bank_sp) as total_bank_sp,
            SUM(buku_besar_cash_outs.bank_induk) as total_bank_induk,
            SUM(buku_besar_cash_outs.simpan_pinjam) as total_simpan_pinjam,
            SUM(buku_besar_cash_outs.inventaris) as total_inventaris,
            SUM(buku_besar_cash_outs.penyertaan_puskop) as total_penyertaan_puskop,
            SUM(buku_besar_cash_outs.hutang_toko) as total_hutang_toko,
            SUM(buku_besar_cash_outs.dana_pengurus) as total_dana_pengurus,
            SUM(buku_besar_cash_outs.dana_karyawan) as total_dana_karyawan,
            SUM(buku_besar_cash_outs.dana_sosial) as total_dana_sosial,
            SUM(buku_besar_cash_outs.dana_dik) as total_dana_dik,
            SUM(buku_besar_cash_outs.dana_pdk) as total_dana_pdk,
            SUM(buku_besar_cash_outs.simp_pokok) as total_simp_pokok,
            SUM(buku_besar_cash_outs.simp_wajib) as total_simp_wajib,
            SUM(buku_besar_cash_outs.simp_khusus) as total_simp_khusus,
            SUM(buku_besar_cash_outs.shu_angg) as total_shu_angg,
            SUM(buku_besar_cash_outs.pembelian_toko) as total_pembelian_toko,
            SUM(buku_besar_cash_outs.biaya_insentif) as total_biaya_insentif,
            SUM(buku_besar_cash_outs.biaya_atk) as total_biaya_atk,
            SUM(buku_besar_cash_outs.biaya_transport) as total_biaya_transport,
            SUM(buku_besar_cash_outs.biaya_pembinaan) as total_biaya_pembinaan,
            SUM(buku_besar_cash_outs.biaya_pembungkus) as total_biaya_pembungkus,
            SUM(buku_besar_cash_outs.biaya_rat) as total_biaya_rat,
            SUM(buku_besar_cash_outs.biaya_thr) as total_biaya_thr,
            SUM(buku_besar_cash_outs.biaya_pajak) as total_biaya_pajak,
            SUM(buku_besar_cash_outs.biaya_admin) as total_biaya_admin,
            SUM(buku_besar_cash_outs.biaya_training) as total_biaya_training,
            SUM(buku_besar_cash_outs.inv_usipa) as total_inv_usipa,
            SUM(buku_besar_cash_outs.lain_lain) as total_lain_lain
        '))
            ->whereYear('main_cash_trans.trans_date', $year)
            ->whereMonth('main_cash_trans.trans_date', $month)
            ->first();

        $bukuKeluar = buku_besar_cash_outs::all();

        $ids = $bukuKeluar->pluck('id_main_cash_trans');

        $kasInduk = main_cash_trans::whereYear('trans_date', $year)
            ->whereMonth('trans_date', $month)
            ->whereIn('id', $ids)
            ->orderBy('trans_date', 'asc')
            ->get();

        return view('bukuKeluar.index', compact('kasInduk', 'bukuKeluar', 'totals', 'year', 'month'));
    }

    public function exportBukuBesarKasKeluar(Request $req)
    {
        $year = $req->input('year');

        return Excel::download(new ExportBukuKasKeluar($year), 'BukuBesarKasKeluar-' . $year . '.xlsx');
    }
}
