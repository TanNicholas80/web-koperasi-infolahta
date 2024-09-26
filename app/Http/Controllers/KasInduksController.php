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

    // public function index()
    // {
    //     // Mengambil semua data kas masuk beserta relasi transaction dan userCashIn
    //     $kasInduk = main_cashs::with('transactions')->orderBy('created_at', 'desc')->get();

    //     // Kirim data ke view
    //     return view('kasInduk.index', compact('kasInduk'));
    // }

    public function index(Request $req)
    {
        // Ambil tahun dan bulan dari input request, default ke tahun dan bulan saat ini
        $year = $req->input('year', Carbon::now()->year);
        $month = $req->input('month', Carbon::now()->month);

        // Mengambil data kas masuk beserta relasi transactions dan userCashIn
        $kasInduk = main_cashs::with('transactions')
            ->whereYear('date', $year) // Filter berdasarkan tahun
            ->whereMonth('date', $month) // Filter berdasarkan bulan
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at
            ->get();

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

        // $month_start = $date->format('m');
        // $year_start = $date->format('Y');

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

        foreach ($request->transactions as $transactionData) {
            $keterangan = $transactionData['jenis_transaksi'];

            $status = $transactionData['status'];

            $lastTransaction = main_cash_trans::where('status', $status)->orderBy('trans_date', 'desc')->first();

            // if ($lastTransaction) {
            //     $lastTransDate = Carbon::parse($lastTransaction->trans_date);
            //     $lastMonth = $lastTransDate->format('m');
            //     $lastYear = $lastTransDate->format('Y');
            //     $lastStatus = $lastTransaction->status;

            //     // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
            //     if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
            //         // Jika bulan dan tahun sama, increment periode dari entri terakhir
            //         $periode = $lastTransaction->periode + 1;
            //     } else {
            //         // Jika bulan atau tahun berbeda, reset periode ke 1
            //         $periode = 1;
            //     }
            // } else {
            //     // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
            //     $periode = 1;
            // }

            // Simpan transaksi ke dalam tabel utama `transactions`
            $transaction = $mainCash->transactions()->create([
                'trans_date' => $request->date,
                'status' => $status,
                'jenis_transaksi' => $transactionData['jenis_transaksi'],
                'keterangan' => $keterangan,
                'periode' => $transactionData['periode'],
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

                        case 'penjualan_tunai':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'jasa_sp':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction'];
                            $shouldSave = true;
                            break;

                        case 'provisi':
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                            $bukuBesarCashIn->provisi = $transactionData['debet_transaction'];
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

                        case 'piutang_uang':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                            $bukuBesarCashOut->piutang_uang = $transactionData['kredit_transaction'];
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
    public function update(Request $request, string $id)
    {
        // Get Main cash
        $mainCash = main_cashs::find($id);
        $mainCashAfter = main_cashs::where('id', '>', $id)->with('transactions')->get();

        $date = Carbon::parse($request->date);

        // $month_start = $date->format('m');
        // $year_start = $date->format('Y');

        $mainCash->date = $date->format('Y-m-d');
        $mainCash->save();

        foreach ($request->transactions as $transactionData) {
            // Dapatkan transaksi berdasarkan ID dari form
            $transaction = main_cash_trans::findOrFail($transactionData['id']);

            $keterangan = $transactionData['jenis_transaksi'];

            $status = $transactionData['status'];

            // $lastTransaction = main_cash_trans::where('status', $status)->orderBy('trans_date', 'desc')->first();

            // if ($lastTransaction) {
            //     $lastTransDate = Carbon::parse($lastTransaction->trans_date);
            //     $lastMonth = $lastTransDate->format('m');
            //     $lastYear = $lastTransDate->format('Y');
            //     $lastStatus = $lastTransaction->status;

            //     // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
            //     if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
            //         // Jika bulan dan tahun sama, increment periode dari entri terakhir
            //         $periode = $transaction->periode;
            //     } else {
            //         // Jika bulan atau tahun berbeda, reset periode ke 1
            //         $periode = 1;
            //     }
            // } else {
            //     // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
            //     $periode = 1;
            // }

            if ($transaction->status !== $status) {
                // Hapus record dari tabel cash_in_trans
                $cashInTrans = cash_in_trans::where('id_main_cash', $transaction->id)->first();
                if ($cashInTrans) {
                    $cashInTrans->delete();
                }

                // Hapus record dari tabel cash_out_trans
                $cashOutTrans = cash_out_trans::where('id_main_cash', $transaction->id)->first();
                if ($cashOutTrans) {
                    $cashOutTrans->delete();
                }

                // Hapus record dari tabel buku_besar_cash_ins
                $bukuBesarCashIn = buku_besar_cash_ins::where('id_main_cash_trans', $transaction->id)->first();
                if ($bukuBesarCashIn) {
                    $bukuBesarCashIn->delete();
                }

                // Hapus record dari tabel buku_besar_cash_outs
                $bukuBesarCashOut = buku_besar_cash_outs::where('id_main_cash_trans', $transaction->id)->first();
                if ($bukuBesarCashOut) {
                    $bukuBesarCashOut->delete();
                }
            }

            // Update value MainCashTrans
            $transaction->trans_date = $request->date;
            $transaction->status = $status;
            $transaction->jenis_transaksi = $transactionData['jenis_transaksi'];
            $transaction->keterangan = $keterangan;
            $transaction->periode = $transactionData['periode'];
            $transaction->kategori_buku_besar = $transactionData['kategori_buku_besar'];
            $transaction->debet_transaction = $status == 'KM' ? $transactionData['debet_transaction'] : null;
            $transaction->kredit_transaction = $status == 'KK' ? $transactionData['kredit_transaction'] : null;
            $transaction->save();

            if ($status === 'KM') {
                $totalDebetTransactionNow = main_cash_trans::where('main_cash_id', $mainCash->id)
                    ->sum('debet_transaction');
                $totalKreditTransactionNow = main_cash_trans::where('main_cash_id', $mainCash->id)->sum('kredit_transaction');
                $mainCash->update([
                    'saldo' => $mainCash->saldo_before_trans + $totalDebetTransactionNow - $totalKreditTransactionNow,
                ]);

                // Update Setiap Saldo dan saldo before trans
                $currentSaldo = $mainCash->saldo;
                foreach ($mainCashAfter as $cashAfter) {
                    // Inisialisasi variabel untuk menyimpan total debet transaksi
                    $totalDebetTransactionAfter = 0;
                    $totalKreditTransactionAfter = 0;

                    // Loop untuk menjumlahkan semua debet transaction pada cashAfter
                    foreach ($cashAfter->transactions as $transAfter) {
                        $totalDebetTransactionAfter += $transAfter->debet_transaction;
                        $totalKreditTransactionAfter += $transAfter->kredit_transaction;
                    }

                    // Update saldo pada cashAfter dengan menambahkan total debet transaksi
                    $cashAfter->update([
                        'saldo' => $currentSaldo + $totalDebetTransactionAfter - $totalKreditTransactionAfter, // Tambahkan semua debet transaksi
                        'saldo_before_trans' => $currentSaldo,
                    ]);

                    // Set currentSaldo menjadi saldo yang baru diperbarui
                    $currentSaldo = $cashAfter->saldo;
                }
                // membuat kondisi cek apakah ID sudah maincashtrans sudah tercantum pada table Cash in trans
                $existsInCashIn = cash_in_trans::where('id_main_cash', $transaction->id)->exists();
                if (!$existsInCashIn) {
                    // Insert ke cash_in_trans jika belum ada
                    cash_in_trans::create([
                        'id_main_cash' => $transaction->id,
                    ]);
                }
                // membuat kondisi cek apakah id maincashtrans sudah tercantum pada table buku_besar_cash_ins
                $existsInBukuCashIns = buku_besar_cash_ins::where('id_main_cash_trans', $transaction->id)->exists();
                if (!$existsInBukuCashIns) {
                    if (!empty($transactionData['kategori_buku_besar'])) {
                        $bukuBesarCashIn = new buku_besar_cash_ins();
                        $bukuBesarCashIn->id_main_cash_trans = $transaction->id;
                        $shouldSave = false;

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
                                $bukuBesarCashIn->kas = $$transactionData['debet_transaction'];
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

                            case 'penjualan_tunai':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction'];
                                $shouldSave = true;
                                break;

                            case 'jasa_sp':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction'];
                                $shouldSave = true;
                                break;

                            case 'provisi':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->provisi = $transactionData['debet_transaction'];
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
                } else {
                    $bukuBesarCashIn = buku_besar_cash_ins::where('id_main_cash_trans', $transaction->id)->first();

                    $bukuBesarCashIn->bank_sp = 0;
                    $bukuBesarCashIn->bank_induk = 0;
                    $bukuBesarCashIn->piutang_uang = 0;
                    $bukuBesarCashIn->piutang_barang_toko = 0;
                    $bukuBesarCashIn->dana_sosial = 0;
                    $bukuBesarCashIn->dana_dik = 0;
                    $bukuBesarCashIn->dana_pdk = 0;
                    $bukuBesarCashIn->resiko_kredit = 0;
                    $bukuBesarCashIn->simpanan_pokok = 0;
                    $bukuBesarCashIn->sipanan_wajib = 0;
                    $bukuBesarCashIn->sipanan_khusus = 0;
                    $bukuBesarCashIn->penjualan_tunai = 0;
                    $bukuBesarCashIn->jasa_sp = 0;
                    $bukuBesarCashIn->provisi = 0;
                    $bukuBesarCashIn->shu_puskop = 0;
                    $bukuBesarCashIn->inv_usipa = 0;
                    $bukuBesarCashIn->lain_lain = 0;

                    // Simpan perubahan kosongkan kategori
                    $bukuBesarCashIn->save();

                    if (!empty($transactionData['kategori_buku_besar'])) {
                        $shouldSave = false;

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

                            case 'penjualan_tunai':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction'];
                                $shouldSave = true;
                                break;

                            case 'jasa_sp':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction'];
                                $shouldSave = true;
                                break;

                            case 'provisi':
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction'];
                                $bukuBesarCashIn->provisi = $transactionData['debet_transaction'];
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

                        if ($shouldSave) {
                            $bukuBesarCashIn->save();
                        }
                    }
                }
            } elseif ($status === 'KK') {
                $totalKreditTransactionNow = main_cash_trans::where('main_cash_id', $mainCash->id)
                    ->sum('kredit_transaction');
                $totalDebetTransactionNow = main_cash_trans::where('main_cash_id', $mainCash->id)
                    ->sum('debet_transaction');
                $mainCash->update([
                    'saldo' => $mainCash->saldo_before_trans - $totalKreditTransactionNow + $totalDebetTransactionNow,
                ]);

                $currentSaldo = $mainCash->saldo;
                foreach ($mainCashAfter as $cashAfter) {
                    // Inisialisasi variabel untuk menyimpan total debet transaksi
                    $totalKreditTransactionAfter = 0;
                    $totalDebetTransactionAfter = 0;

                    // Loop untuk menjumlahkan semua debet transaction pada cashAfter
                    foreach ($cashAfter->transactions as $transAfter) {
                        $totalKreditTransactionAfter += $transAfter->kredit_transaction;
                        $totalDebetTransactionAfter += $transAfter->debet_transaction;
                    }

                    // Update saldo pada cashAfter dengan menambahkan total debet transaksi
                    $cashAfter->update([
                        'saldo' => $currentSaldo - $totalKreditTransactionAfter + $totalDebetTransactionAfter, // Tambahkan semua debet transaksi
                        'saldo_before_trans' => $currentSaldo,
                    ]);

                    // Set currentSaldo menjadi saldo yang baru diperbarui
                    $currentSaldo = $cashAfter->saldo;
                }

                // membuat kondisi cek apakah ID maincashtrans sudah tercantum pada table Cash out trans
                $existsInCashOut = cash_out_trans::where('id_main_cash', $transaction->id)->exists();
                if (!$existsInCashOut) {
                    // Insert ke cash_out_trans jika belum ada
                    cash_out_trans::create([
                        'id_main_cash' => $transaction->id,
                    ]);
                }
                // membuat kondisi cek apakah id maincashtrans sudah tercantum pada table buku_besar_cash_outs
                $existsInBukuCashOuts = buku_besar_cash_outs::where('id_main_cash_trans', $transaction->id)->exists();
                if (!$existsInBukuCashOuts) {
                    if (!empty($transactionData['kategori_buku_besar'])) {
                        $bukuBesarCashOut = new buku_besar_cash_outs();
                        $bukuBesarCashOut->id_main_cash_trans = $transaction->id;
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

                            case 'piutang_uang':
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                                $bukuBesarCashOut->piutang_uang = $transactionData['kredit_transaction'];
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
                    $bukuBesarCashOut = buku_besar_cash_outs::where('id_main_cash_trans', $transaction->id)->first();

                    $bukuBesarCashOut->bank_sp = 0;
                    $bukuBesarCashOut->bank_induk = 0;
                    $bukuBesarCashOut->piutang_uang = 0;
                    $bukuBesarCashOut->inventaris = 0;
                    $bukuBesarCashOut->penyertaan_puskop = 0;
                    $bukuBesarCashOut->hutang_toko = 0;
                    $bukuBesarCashOut->dana_pengurus = 0;
                    $bukuBesarCashOut->dana_karyawan = 0;
                    $bukuBesarCashOut->dana_sosial = 0;
                    $bukuBesarCashOut->dana_dik = 0;
                    $bukuBesarCashOut->dana_pdk = 0;
                    $bukuBesarCashOut->simp_pokok = 0;
                    $bukuBesarCashOut->simp_wajib = 0;
                    $bukuBesarCashOut->simp_khusus = 0;
                    $bukuBesarCashOut->shu_angg = 0;
                    $bukuBesarCashOut->pembelian_toko = 0;
                    $bukuBesarCashOut->biaya_insentif = 0;
                    $bukuBesarCashOut->biaya_atk = 0;
                    $bukuBesarCashOut->biaya_transport = 0;
                    $bukuBesarCashOut->biaya_pembinaan = 0;
                    $bukuBesarCashOut->biaya_pembungkus = 0;
                    $bukuBesarCashOut->biaya_rat = 0;
                    $bukuBesarCashOut->biaya_thr = 0;
                    $bukuBesarCashOut->biaya_pajak = 0;
                    $bukuBesarCashOut->biaya_admin = 0;
                    $bukuBesarCashOut->biaya_training = 0;
                    $bukuBesarCashOut->inv_usipa = 0;
                    $bukuBesarCashOut->lain_lain = 0;

                    // Simpan perubahan kosongkan kategori
                    $bukuBesarCashOut->save();
                    if (!empty($transactionData['kategori_buku_besar'])) {
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

                            case 'piutang_uang':
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction'];
                                $bukuBesarCashOut->piutang_uang = $transactionData['kredit_transaction'];
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
                }
            } else {
                return response()->json(['error' => 'Status tidak valid'], 400);
            }
        }

        return redirect()->route('kasInduk.index')->with('success', 'Kas Induk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mainCash = main_cashs::with('transactions')->findOrFail($id);
        $mainCashAfter = main_cashs::where('id', '>', $id)->with('transactions')->get();

        $currentSaldo = $mainCash->saldo_before_trans;
        foreach ($mainCashAfter as $cashAfter) {
            // Inisialisasi variabel untuk menyimpan total debet transaksi
            $totalKreditTransactionAfter = 0;
            $totalDebetTransactionAfter = 0;

            // Loop untuk menjumlahkan semua debet transaction pada cashAfter
            foreach ($cashAfter->transactions as $transAfter) {
                $totalKreditTransactionAfter += $transAfter->kredit_transaction;
                $totalDebetTransactionAfter += $transAfter->debet_transaction;
            }

            // Update saldo pada cashAfter dengan menambahkan total debet transaksi
            $cashAfter->update([
                'saldo' => $currentSaldo - $totalKreditTransactionAfter + $totalDebetTransactionAfter, // Tambahkan semua debet transaksi
                'saldo_before_trans' => $currentSaldo,
            ]);

            $currentSaldo = $cashAfter->saldo;
        }

        // Periksa apakah mainCash memiliki transaksi
        if ($mainCash->transactions->isNotEmpty()) {
            // Loop melalui semua transaksi dari mainCash
            foreach ($mainCash->transactions as $transaction) {
                $cashInTrans = cash_in_trans::where('id_main_cash', $transaction->id)->first();
                if ($cashInTrans) {
                    $cashInTrans->delete();
                }

                // Hapus record dari tabel cash_out_trans
                $cashOutTrans = cash_out_trans::where('id_main_cash', $transaction->id)->first();
                if ($cashOutTrans) {
                    $cashOutTrans->delete();
                }

                // Hapus record dari tabel buku_besar_cash_ins
                $bukuBesarCashIn = buku_besar_cash_ins::where('id_main_cash_trans', $transaction->id)->first();
                if ($bukuBesarCashIn) {
                    $bukuBesarCashIn->delete();
                }

                // Hapus record dari tabel buku_besar_cash_outs
                $bukuBesarCashOut = buku_besar_cash_outs::where('id_main_cash_trans', $transaction->id)->first();
                if ($bukuBesarCashOut) {
                    $bukuBesarCashOut->delete();
                }

                // Hapus transaksi terkait
                $transaction->delete();
            }
        }

        // Setelah semua relasi dihapus, hapus data kas masuk
        $mainCash->delete();

        return redirect()->route('kasInduk.index')->with('success', 'Kas Induk berhasil dihapus.');
    }
}
