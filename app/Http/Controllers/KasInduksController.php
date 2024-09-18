<?php

namespace App\Http\Controllers;

use App\Models\buku_besar_cash_ins;
use App\Models\buku_besar_cash_outs;
use App\Models\cash_in_trans;
use App\Models\cash_out_trans;
use App\Models\LogSaldo;
use App\Models\main_cash_trans;
use App\Models\main_cashs;
use App\Models\Saldo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KasInduksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data kas masuk beserta relasi transaction dan userCashIn
        $kasInduk = main_cashs::with('transactions')->get();

        // Kirim data ke view
        return view('kasInduk.index', compact('kasInduk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kasInduk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = Carbon::parse($request->date);

        $month_start = $date->format('m');
        $year_start = $date->format('Y');

        // Ambil saldo terakhir dari database
        $lastCash = main_cashs::latest('created_at')->first();
        $lastSaldo = $lastCash ? $lastCash->saldo : 0;
        $saldo_awal = Saldo::latest('created_at')->first();

        $mainCash = new main_cashs();
        $mainCash->date = $date->format('Y-m-d');

        // Cek apakah ada saldo sebelumnya di database
        if ($lastCash) {
            // Jika ada saldo sebelumnya, gunakan saldo terakhir dan tambahkan nilai baru
            $mainCash->saldo = $lastSaldo;  // Misalnya saldo baru ditambahkan
            $mainCash->saldo_before_trans = $lastSaldo;
        } else {
            // Jika tidak ada saldo sebelumnya, gunakan saldo_awal yang diinput user
            $mainCash->saldo = $saldo_awal->saldo_awal;
            $mainCash->saldo_before_trans = $saldo_awal->saldo_awal;
        }
        $mainCash->save();

        $periode = 1;

        foreach ($request->transactions as $transactionData) {
            $keterangan = $transactionData['jenis_transaksi'] .
                ' tgl ' . $request->date;

            $status = $transactionData['status'];

            $lastTransaction = main_cash_trans::where('status', $status)->orderBy('trans_date', 'desc')->first();

            if ($lastTransaction) {
                $lastTransDate = Carbon::parse($lastTransaction->trans_date);
                $lastMonth = $lastTransDate->format('m');
                $lastYear = $lastTransDate->format('Y');
                $lastStatus = $lastTransaction->status;

                // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
                if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
                    // Jika bulan dan tahun sama, increment periode dari entri terakhir
                    $periode = $lastTransaction->periode + 1;
                } else {
                    // Jika bulan atau tahun berbeda, reset periode ke 1
                    $periode = 1;
                }
            } else {
                // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
                $periode = 1;
            }

            // Simpan transaksi ke dalam tabel utama `transactions`
            $transaction = $mainCash->transactions()->create([
                'trans_date' => $request->date,
                'status' => $status,
                'jenis_transaksi' => $transactionData['jenis_transaksi'],
                'keterangan' => $keterangan,
                'periode' => $periode,
                'kategori_buku_besar' => $transactionData['kategori_buku_besar'],
                'debet_transaction' => $status == 'KM' ? $transactionData['debet_transaction'] : null, // Isi debet jika KM
                'kredit_transaction' => $status == 'KK' ? $transactionData['kredit_transaction'] : null, // Isi kredit jika KK
            ]);

            if ($status === 'KM') {

                $mainCash->update([
                    'saldo' => $mainCash->saldo + $transactionData['debet_transaction'],
                ]);

                $mainCashInTransId = $transaction->id;

                $cashInTrans = new cash_in_trans();
                $cashInTrans->id_main_cash = $mainCashInTransId;
                $cashInTrans->save();

                if (!empty($transactionData['kategori_buku_besar'])) {
                    $bukuBesarCashIn = new buku_besar_cash_ins();
                    $bukuBesarCashIn->id_main_cash_trans = $mainCashInTransId;
                    $shouldSave = false; // Flag untuk mengecek apakah data harus disimpan

                    switch ($transactionData['kategori_buku_besar']) {
                        case 'bank_sp':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->bank_sp = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->bank_induk = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'piutang_uang':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->piutang_uang = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'piutang_barang_toko':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->piutang_barang_toko = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->dana_sosial = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->dana_dik = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->dana_pdk = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'resiko_kredit':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->resiko_kredit = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'simpanan_pokok':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->simpanan_pokok = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'sipanan_wajib':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->sipanan_wajib = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'sipanan_khusus':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->sipanan_khusus = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'sipanan_tunai':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->sipanan_tunai = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'jasa_sp':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'provinsi':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->provinsi = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'shu_puskop':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->shu_puskop = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->inv_usipa = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->lain_lain = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        default:
                            // Jika tidak ada kategori yang sesuai, tidak lakukan penyimpanan
                            $shouldSave = false;
                            break;
                    }

                    // Hanya simpan jika ada kategori yang valid
                    if ($shouldSave) {
                        $bukuBesarCashIn->save();
                    }
                }
            } elseif ($status === 'KK') {

                $mainCash->update([
                    'saldo' => $mainCash->saldo - $transactionData['kredit_transaction'],
                ]);

                $mainCashOutTransId = $transaction->id;

                $cashOutTrans = new cash_out_trans();
                $cashOutTrans->id_main_cash = $mainCashOutTransId;
                $cashOutTrans->save();

                if (!empty($transactionData['kategori_buku_besar'])) {
                    $bukuBesarCashOut = new buku_besar_cash_outs();
                    $bukuBesarCashOut->id_main_cash_trans = $mainCashOutTransId;
                    $shouldSave = false;

                    switch ($transactionData['kategori_buku_besar']) {
                        case 'bank_sp':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->bank_sp = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->bank_induk = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'simpan_pinjam':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->simpan_pinjam = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'inventaris':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->inventaris = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'penyertaan_puskop':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->penyertaan_puskop = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'hutang_toko':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->hutang_toko = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_pengurus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->dana_pengurus = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_karyawan':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->dana_karyawan = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->dana_sosial = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->dana_dik = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->dana_pdk = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'simp_pokok':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->simp_pokok = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'simp_wajib':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->simp_wajib = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'simp_khusus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->simp_khusus = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'shu_angg':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->shu_angg = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'pembelian_toko':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->pembelian_toko = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_insentif':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_insentif = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_atk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_atk = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_transport':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_transport = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pembinaan':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_pembinaan = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pembungkus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_pembungkus = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_rat':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_rat = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_thr':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_thr = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pajak':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_pajak = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_admin':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_admin = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'biaya_training':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->biaya_training = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->inv_usipa = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->lain_lain = $transactionData['kredit_transaction'];
                            $shouldSave = true;
                            break;

                        default:
                            $shouldSave = false;
                            break;
                    }

                    if ($shouldSave) {
                        $bukuBesarCashOut->save();
                    }
                }
            } else {
                // Jika status tidak valid, kamu bisa menambahkan validasi atau error handling
                return response()->json(['error' => 'Status tidak valid'], 400);
            }
        }

        return redirect()->route('kasInduk.index')->with('success', 'Kas Induk berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kasInduk = main_cashs::with('transactions')->findOrFail($id);

        // Kirim data ke view
        return view('kasInduk.edit', compact('kasInduk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, string $id_trans)
    {
        // Get Main cash
        $mainCash = main_cashs::find($id);
        $mainCashAfter = main_cashs::where('id', '>', $id)->get();

        $date = Carbon::parse($request->date);

        $month_start = $date->format('m');
        $year_start = $date->format('Y');

        $mainCash->date = $date->format('Y-m-d');
        $mainCash->save();
        // Get Main cash Transaction
        $periode = 1;
        $mainCashTrans = main_cash_trans::where('id', $id_trans)->first();

        $keterangan = $request->jenis_transaksi .
            ' tgl ' . $request->date;

        $status = $request->status;

        $lastTransaction = main_cash_trans::where('status', $status)->orderBy('trans_date', 'desc')->first();

        if ($lastTransaction) {
            $lastTransDate = Carbon::parse($lastTransaction->trans_date);
            $lastMonth = $lastTransDate->format('m');
            $lastYear = $lastTransDate->format('Y');
            $lastStatus = $lastTransaction->status;

            // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
            if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
                // Jika bulan dan tahun sama, increment periode dari entri terakhir
                $periode = $lastTransaction->periode + 1;
            } else {
                // Jika bulan atau tahun berbeda, reset periode ke 1
                $periode = 1;
            }
        } else {
            // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
            $periode = 1;
        }

        if ($mainCashTrans->status !== $status) {
            // Hapus record dari tabel cash_in_trans
            $cashInTrans = cash_in_trans::where('id_main_cash', $mainCashTrans->id)->first();
            if ($cashInTrans) {
                $cashInTrans->delete();
            }

            // Hapus record dari tabel cash_out_trans
            $cashOutTrans = cash_out_trans::where('id_main_cash', $mainCashTrans->id)->first();
            if ($cashOutTrans) {
                $cashOutTrans->delete();
            }

            // Hapus record dari tabel buku_besar_cash_ins
            $bukuBesarCashIn = buku_besar_cash_ins::where('id_main_cash_trans', $mainCashTrans->id)->first();
            if ($bukuBesarCashIn) {
                $bukuBesarCashIn->delete();
            }

            // Hapus record dari tabel buku_besar_cash_outs
            $bukuBesarCashOut = buku_besar_cash_outs::where('id_main_cash_trans', $mainCashTrans->id)->first();
            if ($bukuBesarCashOut) {
                $bukuBesarCashOut->delete();
            }
        }

        // Update value MainCashTrans
        $mainCashTrans->trans_date = $request->date;
        $mainCashTrans->status = $status;
        $mainCashTrans->jenis_transaksi = $request->jenis_transaksi;
        $mainCashTrans->keterangan = $keterangan;
        $mainCashTrans->periode = $periode;
        $mainCashTrans->kategori_buku_besar = $request->kategori_buku_besar;
        $mainCashTrans->debet_transaction = $status == 'KM' ? $request->debet_transaction : null;
        $mainCashTrans->kredit_transaction = $status == 'KK' ? $request->kredit_transaction : null;
        $mainCashTrans->save();

        if ($status === 'KM') {
            $mainCash->update([
                'saldo' => $mainCash->saldo_before_trans + $request->debet_transaction,
            ]);

            // Update Setiap Saldo dan saldo before trans
            foreach ($mainCashAfter as $cashAfter) {
                $cashAfter->update([
                    'saldo' => $mainCash->saldo + $request->debet_transaction,
                    'saldo_before_trans' => $mainCash->saldo,
                ]);
            }
            // membuat kondisi cek apakah ID sudah maincashtrans sudah tercantum pada table Cash in trans
            $existsInCashIn = cash_in_trans::where('id_main_cash', $mainCashTrans->id)->exists();
            if (!$existsInCashIn) {
                // Insert ke cash_in_trans jika belum ada
                cash_in_trans::create([
                    'id_main_cash' => $mainCashTrans->id,
                ]);
            }
            // membuat kondisi cek apakah id maincashtrans sudah tercantum pada table buku_besar_cash_ins
            $existsInBukuCashIns = buku_besar_cash_ins::where('id_main_cash_trans', $mainCashTrans->id)->exists();
            if (!$existsInBukuCashIns) {
                if (!empty($request->kategori_buku_besar)) {
                    $bukuBesarCashIn = new buku_besar_cash_ins();
                    $bukuBesarCashIn->id_main_cash_trans = $mainCashTrans->id;
                    $shouldSave = false;

                    switch ($request->kategori_buku_besar) {
                        case 'bank_sp':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->bank_sp = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->bank_induk = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'piutang_uang':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->piutang_uang = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'piutang_barang_toko':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->piutang_barang_toko = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->dana_sosial = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->dana_dik = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->dana_pdk = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'resiko_kredit':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->resiko_kredit = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'simpanan_pokok':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->simpanan_pokok = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_wajib':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->sipanan_wajib = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_khusus':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->sipanan_khusus = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_tunai':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->sipanan_tunai = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'jasa_sp':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->jasa_sp = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'provinsi':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->provinsi = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'shu_puskop':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->shu_puskop = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->inv_usipa = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashIn->kas = $request->debet_transaction;
                            $bukuBesarCashIn->lain_lain = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        default:
                            // Jika tidak ada kategori yang sesuai, tidak lakukan penyimpanan
                            $shouldSave = false;
                            break;
                    }

                    // Hanya simpan jika ada kategori yang valid
                    if ($shouldSave) {
                        $bukuBesarCashIn->save();
                    }
                }
            } else {
                $bukuBesarCashIn = buku_besar_cash_ins::where('id_main_cash_trans', $mainCashTrans->id)->first();

                $bukuBesarCashIn->bank_sp = null;
                $bukuBesarCashIn->bank_induk = null;
                $bukuBesarCashIn->piutang_uang = null;
                $bukuBesarCashIn->piutang_barang_toko = null;
                $bukuBesarCashIn->dana_sosial = null;
                $bukuBesarCashIn->dana_dik = null;
                $bukuBesarCashIn->dana_pdk = null;
                $bukuBesarCashIn->resiko_kredit = null;
                $bukuBesarCashIn->simpanan_pokok = null;
                $bukuBesarCashIn->sipanan_wajib = null;
                $bukuBesarCashIn->sipanan_khusus = null;
                $bukuBesarCashIn->sipanan_tunai = null;
                $bukuBesarCashIn->jasa_sp = null;
                $bukuBesarCashIn->provinsi = null;
                $bukuBesarCashIn->shu_puskop = null;
                $bukuBesarCashIn->inv_usipa = null;
                $bukuBesarCashIn->lain_lain = null;

                // Simpan perubahan kosongkan kategori
                $bukuBesarCashIn->save();

                if (!empty($request->kategori_buku_besar)) {
                    $shouldSave = false;

                    switch ($request->kategori_buku_besar) {
                        case 'bank_sp':
                            $bukuBesarCashIn->bank_sp = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashIn->bank_induk = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'piutang_uang':
                            $bukuBesarCashIn->piutang_uang = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'piutang_barang_toko':
                            $bukuBesarCashIn->piutang_barang_toko = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashIn->dana_sosial = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashIn->dana_dik = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashIn->dana_pdk = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'resiko_kredit':
                            $bukuBesarCashIn->resiko_kredit = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'simpanan_pokok':
                            $bukuBesarCashIn->simpanan_pokok = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_wajib':
                            $bukuBesarCashIn->sipanan_wajib = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_khusus':
                            $bukuBesarCashIn->sipanan_khusus = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'sipanan_tunai':
                            $bukuBesarCashIn->sipanan_tunai = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'jasa_sp':
                            $bukuBesarCashIn->jasa_sp = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'provinsi':
                            $bukuBesarCashIn->provinsi = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'shu_puskop':
                            $bukuBesarCashIn->shu_puskop = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashIn->inv_usipa = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashIn->lain_lain = $request->debet_transaction;
                            $shouldSave = true;
                            break;

                        default:
                            // Jika tidak ada kategori yang sesuai, tidak lakukan penyimpanan
                            $shouldSave = false;
                            break;
                    }

                    if ($shouldSave) {
                        $bukuBesarCashIn->save();
                    }
                }
            }
        } elseif ($status === 'KK') {
            $mainCash->update([
                'saldo' => $mainCash->saldo_before_trans - $request->kredit_transaction,
            ]);

            // Update Setiap Saldo dan saldo before trans
            foreach ($mainCashAfter as $cashAfter) {
                $cashAfter->update([
                    'saldo' => $mainCash->saldo - $request->kredit_transaction,
                    'saldo_before_trans' => $mainCash->saldo,
                ]);
            }
            // membuat kondisi cek apakah ID maincashtrans sudah tercantum pada table Cash out trans
            $existsInCashOut = cash_out_trans::where('id_main_cash', $mainCashTrans->id)->exists();
            if (!$existsInCashOut) {
                // Insert ke cash_out_trans jika belum ada
                cash_out_trans::create([
                    'id_main_cash' => $mainCash->id,
                ]);
            }
            // membuat kondisi cek apakah id maincashtrans sudah tercantum pada table buku_besar_cash_outs
            $existsInBukuCashOuts = buku_besar_cash_outs::where('id_main_cash_trans', $mainCashTrans->id)->exists();
            if (!$existsInBukuCashOuts) {
                if (!empty($request->kategori_buku_besar)) {
                    $bukuBesarCashOut = new buku_besar_cash_outs();
                    $bukuBesarCashOut->id_main_cash_trans = $mainCashTrans->id;
                    $shouldSave = false;

                    switch ($request->kategori_buku_besar) {
                        case 'bank_sp':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->bank_sp = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->bank_induk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simpan_pinjam':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->simpan_pinjam = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'inventaris':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->inventaris = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'penyertaan_puskop':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->penyertaan_puskop = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'hutang_toko':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->hutang_toko = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pengurus':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->dana_pengurus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_karyawan':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->dana_karyawan = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->dana_sosial = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->dana_dik = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->dana_pdk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_pokok':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->simp_pokok = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_wajib':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->simp_wajib = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_khusus':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->simp_khusus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'shu_angg':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->shu_angg = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'pembelian_toko':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->pembelian_toko = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_insentif':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_insentif = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_atk':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_atk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_transport':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_transport = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pembinaan':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_pembinaan = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pembungkus':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_pembungkus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_rat':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_rat = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_thr':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_thr = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pajak':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_pajak = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_admin':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_admin = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_training':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->biaya_training = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->inv_usipa = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashOut->kas = $request->kredit_transaction;
                            $bukuBesarCashOut->lain_lain = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        default:
                            $shouldSave = false;
                            break;
                    }

                    if ($shouldSave) {
                        $bukuBesarCashOut->save();
                    }
                }
            } else {
                $bukuBesarCashOut = buku_besar_cash_outs::where('id_main_cash_trans', $mainCashTrans->id)->first();

                $bukuBesarCashOut->bank_sp = null;
                $bukuBesarCashOut->bank_induk = null;
                $bukuBesarCashOut->simpan_pinjam = null;
                $bukuBesarCashOut->inventaris = null;
                $bukuBesarCashOut->penyertaan_puskop = null;
                $bukuBesarCashOut->hutang_toko = null;
                $bukuBesarCashOut->dana_pengurus = null;
                $bukuBesarCashOut->dana_karyawan = null;
                $bukuBesarCashOut->dana_sosial = null;
                $bukuBesarCashOut->dana_dik = null;
                $bukuBesarCashOut->dana_pdk = null;
                $bukuBesarCashOut->simp_pokok = null;
                $bukuBesarCashOut->simp_wajib = null;
                $bukuBesarCashOut->simp_khusus = null;
                $bukuBesarCashOut->shu_angg = null;
                $bukuBesarCashOut->pembelian_toko = null;
                $bukuBesarCashOut->biaya_insentif = null;
                $bukuBesarCashOut->biaya_atk = null;
                $bukuBesarCashOut->biaya_transport = null;
                $bukuBesarCashOut->biaya_pembinaan = null;
                $bukuBesarCashOut->biaya_pembungkus = null;
                $bukuBesarCashOut->biaya_rat = null;
                $bukuBesarCashOut->biaya_thr = null;
                $bukuBesarCashOut->biaya_pajak = null;
                $bukuBesarCashOut->biaya_admin = null;
                $bukuBesarCashOut->biaya_training = null;
                $bukuBesarCashOut->inv_usipa = null;
                $bukuBesarCashOut->lain_lain = null;

                // Simpan perubahan kosongkan kategori
                $bukuBesarCashOut->save();
                if (!empty($request->kategori_buku_besar)) {
                    $shouldSave = false;
                    switch ($request->kategori_buku_besar) {
                        case 'bank_sp':
                            $bukuBesarCashOut->bank_sp = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashOut->bank_induk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simpan_pinjam':
                            $bukuBesarCashOut->simpan_pinjam = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'inventaris':
                            $bukuBesarCashOut->inventaris = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'penyertaan_puskop':
                            $bukuBesarCashOut->penyertaan_puskop = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'hutang_toko':
                            $bukuBesarCashOut->hutang_toko = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pengurus':
                            $bukuBesarCashOut->dana_pengurus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_karyawan':
                            $bukuBesarCashOut->dana_karyawan = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashOut->dana_sosial = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashOut->dana_dik = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashOut->dana_pdk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_pokok':
                            $bukuBesarCashOut->simp_pokok = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_wajib':
                            $bukuBesarCashOut->simp_wajib = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'simp_khusus':
                            $bukuBesarCashOut->simp_khusus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'shu_angg':
                            $bukuBesarCashOut->shu_angg = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'pembelian_toko':
                            $bukuBesarCashOut->pembelian_toko = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_insentif':
                            $bukuBesarCashOut->biaya_insentif = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_atk':
                            $bukuBesarCashOut->biaya_atk = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_transport':
                            $bukuBesarCashOut->biaya_transport = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pembinaan':
                            $bukuBesarCashOut->biaya_pembinaan = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pembungkus':
                            $bukuBesarCashOut->biaya_pembungkus = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_rat':
                            $bukuBesarCashOut->biaya_rat = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_thr':
                            $bukuBesarCashOut->biaya_thr = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_pajak':
                            $bukuBesarCashOut->biaya_pajak = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_admin':
                            $bukuBesarCashOut->biaya_admin = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'biaya_training':
                            $bukuBesarCashOut->biaya_training = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'inv_usipa':
                            $bukuBesarCashOut->inv_usipa = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashOut->lain_lain = $request->kredit_transaction;
                            $shouldSave = true;
                            break;

                        default:
                            $shouldSave = false;
                            break;
                    }
                }

                if ($shouldSave) {
                    $bukuBesarCashOut->save();
                }
            }
        } else {
            return response()->json(['error' => 'Status tidak valid'], 400);
        }

        return redirect()->route('kasInduk.index')->with('success', 'Kas Induk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
