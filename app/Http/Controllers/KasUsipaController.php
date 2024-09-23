<?php

namespace App\Http\Controllers;

use App\Models\BukuBesarUsipaCashIn;
use App\Models\BukuBesarUsipaCashOut;
use App\Models\CashInUsipa;
use App\Models\CashOutUsipa;
use App\Models\KasUsipa;
use App\Models\KasUsipaTrans;
use App\Models\Saldo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasUsipaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kasUsipa = KasUsipa::with('transactions')->get();

        return view('kasUsipa.index', compact('kasUsipa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kasUsipa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date = Carbon::parse($request->date_usipa);

        // $month_start = $date->format('m');
        // $year_start = $date->format('Y');

        // Ambil saldo terakhir dari database
        $lastCash = KasUsipa::latest('created_at')->first();
        $lastSaldo = $lastCash ? $lastCash->saldo_usipa : 0;
        $saldo_awal = Saldo::latest('created_at')->first();

        $kasUsipa = new KasUsipa();
        $kasUsipa->date_usipa = $date->format('Y-m-d');

        if ($lastCash) {
            // Jika ada saldo sebelumnya, gunakan saldo terakhir dan tambahkan nilai baru
            $kasUsipa->saldo_usipa = $lastSaldo;  // Misalnya saldo baru ditambahkan
            $kasUsipa->saldo_before_usipa_trans = $lastSaldo;
        } else {
            // Jika tidak ada saldo sebelumnya, gunakan saldo_awal yang diinput user
            $kasUsipa->saldo_usipa = $saldo_awal->saldo_awal;
            $kasUsipa->saldo_before_usipa_trans = $saldo_awal->saldo_awal;
        }
        $kasUsipa->save();

        foreach ($request->transactions as $transactionData) {
            $keterangan = $transactionData['jenis_transaksi_usipa'];

            $status = $transactionData['status_usipa'];

            // $lastTransaction = KasUsipaTrans::where('status_usipa', $status)->orderBy('trans_date_usipa', 'desc')->first();

            // if ($lastTransaction) {
            //     $lastTransDate = Carbon::parse($lastTransaction->trans_date_usipa);
            //     $lastMonth = $lastTransDate->format('m');
            //     $lastYear = $lastTransDate->format('Y');
            //     $lastStatus = $lastTransaction->status_usipa;

            //     // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
            //     if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
            //         // Jika bulan dan tahun sama, increment periode dari entri terakhir
            //         $periode = $lastTransaction->periode_usipa + 1;
            //     } else {
            //         // Jika bulan atau tahun berbeda, reset periode ke 1
            //         $periode = 1;
            //     }
            // } else {
            //     // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
            //     $periode = 1;
            // }

            $transaction = $kasUsipa->transactions()->create([
                'trans_date_usipa' => $request->date_usipa,
                'status_usipa' => $status,
                'jenis_transaksi_usipa' => $transactionData['jenis_transaksi_usipa'],
                'keterangan_usipa' => $keterangan,
                'periode_usipa' => $transactionData['periode_usipa'],
                'kategori_buku_besar_usipa' => $transactionData['kategori_buku_besar_usipa'],
                'debet_transaction_usipa' => $status == 'KM' ? $transactionData['debet_transaction_usipa'] : null, // Isi debet jika KM
                'kredit_transaction_usipa' => $status == 'KK' ? $transactionData['kredit_transaction_usipa'] : null, // Isi kredit jika KK
            ]);

            if ($status === 'KM') {
                $kasUsipa->update([
                    'saldo_usipa' => $kasUsipa->saldo_usipa + $transactionData['debet_transaction_usipa'],
                ]);

                $kasUsipaInTransId = $transaction->id;

                $cashInUsipaTrans = new CashInUsipa();
                $cashInUsipaTrans->id_kas_usipa_trans = $kasUsipaInTransId;
                $cashInUsipaTrans->save();

                if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                    $bukuBesarCashIn = new BukuBesarUsipaCashIn();
                    $bukuBesarCashIn->id_kas_usipa_trans = $kasUsipaInTransId;
                    $shouldSave = false; // Flag untuk mengecek apakah data harus disimpan

                    switch ($transactionData['kategori_buku_besar_usipa']) {
                        case 'bank_sp':
                            // Logika khusus untuk kategori BANK S/P
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->bank_sp = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            // Logika khusus untuk kategori BANK INDUK
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->bank_induk = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'piutang_uang':
                            // Logika khusus untuk kategori PIUTANG UANG
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->piutang_uang = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'piutang_brg_toko':
                            // Logika khusus untuk kategori PIUTANG BRG TOKO
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->piutang_brg_toko = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            // Logika khusus untuk kategori DANA SOSIAL
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->dana_sosial = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            // Logika khusus untuk kategori DANA DIK
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->dana_dik = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            // Logika khusus untuk kategori DANA PDK
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->dana_pdk = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'resiko_kredit':
                            // Logika khusus untuk kategori RESIKO KREDIT
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->resiko_kredit = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_pokok':
                            // Logika khusus untuk kategori SIMP POKOK
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->simp_pokok = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_wajib':
                            // Logika khusus untuk kategori SIMP WAJIB
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->simp_wajib = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_khusus':
                            // Logika khusus untuk kategori SIMP KHUSUS
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->simp_khusus = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'penjualan_tunai':
                            // Logika khusus untuk kategori PENJUALAN TUNAI
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'jasa_sp':
                            // Logika khusus untuk kategori JASA S/P
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'provinsi':
                            // Logika khusus untuk kategori PROVISI
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->provinsi = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'shu_puskop':
                            // Logika khusus untuk kategori SHU PUSKOP
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->shu_puskop = $transactionData['debet_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'modal_disetor':
                            // Logika khusus untuk kategori MODAL DISETOR
                            $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                            $bukuBesarCashIn->modal_disetor = $transactionData['debet_transaction_usipa'];
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
                $kasUsipa->update([
                    'saldo_usipa' => $kasUsipa->saldo_usipa - $transactionData['kredit_transaction_usipa'],
                ]);

                $kasUsipaOutTransId = $transaction->id;

                $cashOutUsipaTrans = new CashOutUsipa();
                $cashOutUsipaTrans->id_kas_usipa_trans = $kasUsipaOutTransId;
                $cashOutUsipaTrans->save();

                if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                    $bukuBesarCashOut = new BukuBesarUsipaCashOut();
                    $bukuBesarCashOut->id_kas_usipa_trans = $kasUsipaOutTransId;
                    $shouldSave = false;

                    switch ($transactionData['kategori_buku_besar_usipa']) {
                        case 'bank_sp':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->bank_sp = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'bank_induk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->bank_induk = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simpanan_pinjaman':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->simpanan_pinjaman = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'inventaris':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->inventaris = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'penyertaan_puskop':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->penyertaan_puskop = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'hutang_toko':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->hutang_toko = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_pengurus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->dana_pengurus = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_karyawan':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->dana_karyawan = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_sosial':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->dana_sosial = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_dik':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->dana_dik = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'dana_pdk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->dana_pdk = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_pokok':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->simp_pokok = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_wajib':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->simp_wajib = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'simp_khusus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->simp_khusus = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'shu_angg':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->shu_angg = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'pembelian_toko':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->pembelian_toko = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_insentif':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_insentif = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_atk':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_atk = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_transport':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_transport = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pembinaan':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_pembinaan = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pembungkus':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_pembungkus = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_rat':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_rat = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_thr':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_thr = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_pajak':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_pajak = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_admin':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_admin = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'biaya_training':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->biaya_training = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'modal_disetor':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->modal_disetor = $transactionData['kredit_transaction_usipa'];
                            $shouldSave = true;
                            break;

                        case 'lain_lain':
                            $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                            $bukuBesarCashOut->lain_lain = $transactionData['kredit_transaction_usipa'];
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
                return response()->json(['error' => 'Status tidak valid'], 400);
            }
        }

        return redirect()->route('kasUsipa.index')->with('success', 'Kas Usipa berhasil disimpan.');
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
        $kasUsipa = KasUsipa::with('transactions')->findOrFail($id);

        return view('kasUsipa.edit', compact('kasUsipa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kasUsipa = KasUsipa::find($id);
        $kasUsipaAfter = KasUsipa::where('id', '>', $id)->with('transactions')->get();

        $date = Carbon::parse($request->date_usipa);

        // $month_start = $date->format('m');
        // $year_start = $date->format('Y');

        $kasUsipa->date_usipa = $date->format('Y-m-d');
        $kasUsipa->save();

        foreach ($request->transactions as $transactionData) {
            $transaction = KasUsipaTrans::findOrFail($transactionData['id']);

            $keterangan = $transactionData['jenis_transaksi_usipa'];

            $status = $transactionData['status_usipa'];

            // $lastTransaction = KasUsipaTrans::where('status_usipa', $status)->orderBy('trans_date_usipa', 'desc')->first();

            // if ($lastTransaction) {
            //     $lastTransDate = Carbon::parse($lastTransaction->trans_date_usipa);
            //     $lastMonth = $lastTransDate->format('m');
            //     $lastYear = $lastTransDate->format('Y');
            //     $lastStatus = $lastTransaction->status_usipa;

            //     // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
            //     if ($lastMonth == $month_start && $lastYear == $year_start && $lastStatus == $status) {
            //         // Jika bulan dan tahun sama, increment periode dari entri terakhir
            //         $periode = $transaction->periode_usipa;
            //     } else {
            //         // Jika bulan atau tahun berbeda, reset periode ke 1
            //         $periode = 1;
            //     }
            // } else {
            //     // Jika tidak ada transaksi sebelumnya, mulai dari periode 1
            //     $periode = 1;
            // }

            if ($transaction->status_usipa !== $status) {
                // Hapus record dari tabel cash_in_trans
                $cashInUsipaTrans = CashInUsipa::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($cashInUsipaTrans) {
                    $cashInUsipaTrans->delete();
                }

                // Hapus record dari tabel cash_out_trans
                $cashOutUsipaTrans = CashOutUsipa::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($cashOutUsipaTrans) {
                    $cashOutUsipaTrans->delete();
                }

                // Hapus record dari tabel buku_besar_cash_ins
                $bukuBesarCashIn = BukuBesarUsipaCashIn::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($bukuBesarCashIn) {
                    $bukuBesarCashIn->delete();
                }

                // Hapus record dari tabel buku_besar_cash_outs
                $bukuBesarCashOut = BukuBesarUsipaCashOut::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($bukuBesarCashOut) {
                    $bukuBesarCashOut->delete();
                }
            }

            $transaction->trans_date_usipa = $request->date_usipa;
            $transaction->status_usipa = $status;
            $transaction->jenis_transaksi_usipa = $transactionData['jenis_transaksi_usipa'];
            $transaction->keterangan_usipa = $keterangan;
            $transaction->periode_usipa = $transactionData['periode_usipa'];
            $transaction->kategori_buku_besar_usipa = $transactionData['kategori_buku_besar_usipa'];
            $transaction->debet_transaction_usipa = $status == 'KM' ? $transactionData['debet_transaction_usipa'] : null;
            $transaction->kredit_transaction_usipa = $status == 'KK' ? $transactionData['kredit_transaction_usipa'] : null;
            $transaction->save();

            if ($status === 'KM') {
                $totalDebetTransactionNow = KasUsipaTrans::where('kas_usipa_id', $kasUsipa->id)
                    ->sum('debet_transaction_usipa');
                $totalKreditTransactionNow = KasUsipaTrans::where('kas_usipa_id', $kasUsipa->id)
                    ->sum('kredit_transaction_usipa');
                $kasUsipa->update([
                    'saldo_usipa' => $kasUsipa->saldo_before_usipa_trans + $totalDebetTransactionNow - $totalKreditTransactionNow,
                ]);

                $currentSaldo = $kasUsipa->saldo_usipa;
                foreach ($kasUsipaAfter as $cashAfter) {
                    // Inisialisasi variabel untuk menyimpan total debet transaksi
                    $totalDebetTransactionAfter = 0;
                    $totalKreditTransactionAfter = 0;

                    // Loop untuk menjumlahkan semua debet transaction pada cashAfter
                    foreach ($cashAfter->transactions as $transAfter) {
                        $totalDebetTransactionAfter += $transAfter->debet_transaction_usipa;
                        $totalKreditTransactionAfter += $transAfter->kredit_transaction_usipa;
                    }

                    // Update saldo pada cashAfter dengan menambahkan total debet transaksi
                    $cashAfter->update([
                        'saldo_usipa' => $currentSaldo + $totalDebetTransactionAfter - $totalKreditTransactionAfter, // Tambahkan semua debet transaksi
                        'saldo_before_usipa_trans' => $currentSaldo,
                    ]);

                    // Set currentSaldo menjadi saldo yang baru diperbarui
                    $currentSaldo = $cashAfter->saldo_usipa;
                }

                $existsInCashInUsipa = CashInUsipa::where('id_kas_usipa_trans', $transaction->id)->exists();
                if (!$existsInCashInUsipa) {
                    // Insert ke cash_in_trans jika belum ada
                    CashInUsipa::create([
                        'id_kas_usipa_trans' => $kasUsipa->id,
                    ]);
                }

                $existsInBukuUsipaCashIns = BukuBesarUsipaCashIn::where('id_kas_usipa_trans', $transaction->id)->exists();
                if (!$existsInBukuUsipaCashIns) {
                    if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                        $bukuBesarCashIn = new BukuBesarUsipaCashIn();
                        $bukuBesarCashIn->id_kas_usipa_trans = $transaction->id;
                        $shouldSave = false;

                        switch ($transactionData['kategori_buku_besar_usipa']) {
                            case 'bank_sp':
                                $bukuBesarCashIn->bank_sp = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'bank_induk':
                                $bukuBesarCashIn->bank_induk = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'piutang_uang':
                                $bukuBesarCashIn->piutang_uang = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'piutang_brg_toko':
                                $bukuBesarCashIn->piutang_brg_toko = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_sosial':
                                $bukuBesarCashIn->dana_sosial = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_dik':
                                $bukuBesarCashIn->dana_dik = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pdk':
                                $bukuBesarCashIn->dana_pdk = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'resiko_kredit':
                                $bukuBesarCashIn->resiko_kredit = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_pokok':
                                $bukuBesarCashIn->simp_pokok = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_wajib':
                                $bukuBesarCashIn->simp_wajib = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_khusus':
                                $bukuBesarCashIn->simp_khusus = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'penjualan_tunai':
                                $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'jasa_sp':
                                $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'provinsi':
                                $bukuBesarCashIn->provinsi = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'shu_puskop':
                                $bukuBesarCashIn->shu_puskop = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'modal_disetor':
                                $bukuBesarCashIn->modal_disetor = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
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
                    $bukuBesarCashIn = BukuBesarUsipaCashIn::where('id_kas_usipa_trans', $transaction->id)->first();

                    $bukuBesarCashIn->bank_sp = 0;
                    $bukuBesarCashIn->bank_induk = 0;
                    $bukuBesarCashIn->piutang_uang = 0;
                    $bukuBesarCashIn->piutang_brg_toko = 0;
                    $bukuBesarCashIn->dana_sosial = 0;
                    $bukuBesarCashIn->dana_dik = 0;
                    $bukuBesarCashIn->dana_pdk = 0;
                    $bukuBesarCashIn->resiko_kredit = 0;
                    $bukuBesarCashIn->simp_pokok = 0;
                    $bukuBesarCashIn->simp_wajib = 0;
                    $bukuBesarCashIn->simp_khusus = 0;
                    $bukuBesarCashIn->penjualan_tunai = 0;
                    $bukuBesarCashIn->jasa_sp = 0;
                    $bukuBesarCashIn->provinsi = 0;
                    $bukuBesarCashIn->shu_puskop = 0;
                    $bukuBesarCashIn->modal_disetor = 0;

                    // Menyimpan perubahan
                    $bukuBesarCashIn->save();

                    if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                        $shouldSave = false;

                        switch ($transactionData['kategori_buku_besar_usipa']) {
                            case 'bank_sp':
                                $bukuBesarCashIn->bank_sp = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'bank_induk':
                                $bukuBesarCashIn->bank_induk = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'piutang_uang':
                                $bukuBesarCashIn->piutang_uang = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'piutang_brg_toko':
                                $bukuBesarCashIn->piutang_brg_toko = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_sosial':
                                $bukuBesarCashIn->dana_sosial = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_dik':
                                $bukuBesarCashIn->dana_dik = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pdk':
                                $bukuBesarCashIn->dana_pdk = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'resiko_kredit':
                                $bukuBesarCashIn->resiko_kredit = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_pokok':
                                $bukuBesarCashIn->simp_pokok = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_wajib':
                                $bukuBesarCashIn->simp_wajib = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_khusus':
                                $bukuBesarCashIn->simp_khusus = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'penjualan_tunai':
                                $bukuBesarCashIn->penjualan_tunai = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'jasa_sp':
                                $bukuBesarCashIn->jasa_sp = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'provinsi':
                                $bukuBesarCashIn->provinsi = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'shu_puskop':
                                $bukuBesarCashIn->shu_puskop = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'modal_disetor':
                                $bukuBesarCashIn->modal_disetor = $transactionData['debet_transaction_usipa'];
                                $bukuBesarCashIn->kas = $transactionData['debet_transaction_usipa'];
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
                $totalKreditTransactionNow = KasUsipaTrans::where('kas_usipa_id', $kasUsipa->id)
                    ->sum('kredit_transaction_usipa');
                $totalDebetTransactionNow = KasUsipaTrans::where('kas_usipa_id', $kasUsipa->id)
                    ->sum('debet_transaction_usipa');
                $kasUsipa->update([
                    'saldo_usipa' => $kasUsipa->saldo_before_usipa_trans - $totalKreditTransactionNow + $totalDebetTransactionNow,
                ]);

                $currentSaldo = $kasUsipa->saldo_usipa;
                foreach ($kasUsipaAfter as $cashAfter) {
                    // Inisialisasi variabel untuk menyimpan total debet transaksi
                    $totalKreditTransactionAfter = 0;
                    $totalDebetTransactionAfter = 0;

                    // Loop untuk menjumlahkan semua debet transaction pada cashAfter
                    foreach ($cashAfter->transactions as $transAfter) {
                        $totalKreditTransactionAfter += $transAfter->kredit_transaction_usipa;
                        $totalDebetTransactionAfter += $transAfter->debet_transaction_usipa;
                    }

                    // Update saldo pada cashAfter dengan menambahkan total debet transaksi
                    $cashAfter->update([
                        'saldo_usipa' => $currentSaldo - $totalKreditTransactionAfter + $totalDebetTransactionAfter, // Tambahkan semua debet transaksi
                        'saldo_before_usipa_trans' => $currentSaldo,
                    ]);

                    // Set currentSaldo menjadi saldo yang baru diperbarui
                    $currentSaldo = $cashAfter->saldo_usipa;
                }

                $existsInCashOut = CashOutUsipa::where('id_kas_usipa_trans', $transaction->id)->exists();
                if (!$existsInCashOut) {
                    // Insert ke cash_out_trans jika belum ada
                    CashOutUsipa::create([
                        'id_kas_usipa_trans' => $transaction->id,
                    ]);
                }

                $existsInBukuUsipaCashOuts = BukuBesarUsipaCashOut::where('id_kas_usipa_trans', $transaction->id)->exists();
                if (!$existsInBukuUsipaCashOuts) {
                    if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                        $bukuBesarCashOut = new BukuBesarUsipaCashOut();
                        $bukuBesarCashOut->id_kas_usipa_trans = $transaction->id;
                        $shouldSave = false;

                        switch ($transactionData['kategori_buku_besar_usipa']) {
                            case 'bank_sp':
                                $bukuBesarCashOut->bank_sp = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'bank_induk':
                                $bukuBesarCashOut->bank_induk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simpanan_pinjaman':
                                $bukuBesarCashOut->simpanan_pinjaman = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'inventaris':
                                $bukuBesarCashOut->inventaris = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'penyertaan_puskop':
                                $bukuBesarCashOut->penyertaan_puskop = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'hutang_toko':
                                $bukuBesarCashOut->hutang_toko = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pengurus':
                                $bukuBesarCashOut->dana_pengurus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_karyawan':
                                $bukuBesarCashOut->dana_karyawan = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_sosial':
                                $bukuBesarCashOut->dana_sosial = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_dik':
                                $bukuBesarCashOut->dana_dik = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pdk':
                                $bukuBesarCashOut->dana_pdk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_pokok':
                                $bukuBesarCashOut->simp_pokok = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_wajib':
                                $bukuBesarCashOut->simp_wajib = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_khusus':
                                $bukuBesarCashOut->simp_khusus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'shu_angg':
                                $bukuBesarCashOut->shu_angg = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'pembelian_toko':
                                $bukuBesarCashOut->pembelian_toko = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_insentif':
                                $bukuBesarCashOut->biaya_insentif = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_atk':
                                $bukuBesarCashOut->biaya_atk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_transport':
                                $bukuBesarCashOut->biaya_transport = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pembinaan':
                                $bukuBesarCashOut->biaya_pembinaan = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pembungkus':
                                $bukuBesarCashOut->biaya_pembungkus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_rat':
                                $bukuBesarCashOut->biaya_rat = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_thr':
                                $bukuBesarCashOut->biaya_thr = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pajak':
                                $bukuBesarCashOut->biaya_pajak = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_admin':
                                $bukuBesarCashOut->biaya_admin = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_training':
                                $bukuBesarCashOut->biaya_training = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'modal_disetor':
                                $bukuBesarCashOut->modal_disetor = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'lain_lain':
                                $bukuBesarCashOut->lain_lain = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
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
                    $bukuBesarCashOut = BukuBesarUsipaCashOut::where('id_kas_usipa_trans', $transaction->id)->first();

                    $bukuBesarCashOut->bank_sp = 0;
                    $bukuBesarCashOut->bank_induk = 0;
                    $bukuBesarCashOut->simpanan_pinjaman = 0;
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
                    $bukuBesarCashOut->modal_disetor = 0;
                    $bukuBesarCashOut->lain_lain = 0;

                    $bukuBesarCashOut->save();

                    if (!empty($transactionData['kategori_buku_besar_usipa'])) {
                        $shouldSave = false;

                        switch ($transactionData['kategori_buku_besar_usipa']) {
                            case 'bank_sp':
                                $bukuBesarCashOut->bank_sp = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'bank_induk':
                                $bukuBesarCashOut->bank_induk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simpanan_pinjaman':
                                $bukuBesarCashOut->simpanan_pinjaman = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'inventaris':
                                $bukuBesarCashOut->inventaris = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'penyertaan_puskop':
                                $bukuBesarCashOut->penyertaan_puskop = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'hutang_toko':
                                $bukuBesarCashOut->hutang_toko = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pengurus':
                                $bukuBesarCashOut->dana_pengurus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_karyawan':
                                $bukuBesarCashOut->dana_karyawan = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_sosial':
                                $bukuBesarCashOut->dana_sosial = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_dik':
                                $bukuBesarCashOut->dana_dik = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'dana_pdk':
                                $bukuBesarCashOut->dana_pdk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_pokok':
                                $bukuBesarCashOut->simp_pokok = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_wajib':
                                $bukuBesarCashOut->simp_wajib = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'simp_khusus':
                                $bukuBesarCashOut->simp_khusus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'shu_angg':
                                $bukuBesarCashOut->shu_angg = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'pembelian_toko':
                                $bukuBesarCashOut->pembelian_toko = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_insentif':
                                $bukuBesarCashOut->biaya_insentif = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_atk':
                                $bukuBesarCashOut->biaya_atk = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_transport':
                                $bukuBesarCashOut->biaya_transport = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pembinaan':
                                $bukuBesarCashOut->biaya_pembinaan = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pembungkus':
                                $bukuBesarCashOut->biaya_pembungkus = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_rat':
                                $bukuBesarCashOut->biaya_rat = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_thr':
                                $bukuBesarCashOut->biaya_thr = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_pajak':
                                $bukuBesarCashOut->biaya_pajak = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_admin':
                                $bukuBesarCashOut->biaya_admin = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'biaya_training':
                                $bukuBesarCashOut->biaya_training = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'modal_disetor':
                                $bukuBesarCashOut->modal_disetor = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
                                $shouldSave = true;
                                break;

                            case 'lain_lain':
                                $bukuBesarCashOut->lain_lain = $transactionData['kredit_transaction_usipa'];
                                $bukuBesarCashOut->kas = $transactionData['kredit_transaction_usipa'];
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

        return redirect()->route('kasUsipa.index')->with('success', 'Kas Usipa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kasUsipa = KasUsipa::with('transactions')->findOrFail($id);
        $kasUsipaAfter = KasUsipa::where('id', '>', $id)->with('transactions')->get();

        $currentSaldo = $kasUsipa->saldo_before_usipa_trans;
        foreach ($kasUsipaAfter as $cashAfter) {
            // Inisialisasi variabel untuk menyimpan total debet transaksi
            $totalKreditTransactionAfter = 0;
            $totalDebetTransactionAfter = 0;

            // Loop untuk menjumlahkan semua debet transaction pada cashAfter
            foreach ($cashAfter->transactions as $transAfter) {
                $totalKreditTransactionAfter += $transAfter->kredit_transaction_usipa;
                $totalDebetTransactionAfter += $transAfter->debet_transaction_usipa;
            }

            // Update saldo pada cashAfter dengan menambahkan total debet transaksi
            $cashAfter->update([
                'saldo_usipa' => $currentSaldo - $totalKreditTransactionAfter + $totalDebetTransactionAfter, // Tambahkan semua debet transaksi
                'saldo_before_usipa_trans' => $currentSaldo,
            ]);

            $currentSaldo = $cashAfter->saldo_usipa;
        }

        // Periksa apakah mainCash memiliki transaksi
        if ($kasUsipa->transactions->isNotEmpty()) {
            // Loop melalui semua transaksi dari mainCash
            foreach ($kasUsipa->transactions as $transaction) {
                $cashInUsipaTrans = CashInUsipa::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($cashInUsipaTrans) {
                    $cashInUsipaTrans->delete();
                }

                // Hapus record dari tabel cash_out_trans
                $cashOutUsipaTrans = CashOutUsipa::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($cashOutUsipaTrans) {
                    $cashOutUsipaTrans->delete();
                }

                // Hapus record dari tabel buku_besar_cash_ins
                $bukuBesarCashIn = BukuBesarUsipaCashIn::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($bukuBesarCashIn) {
                    $bukuBesarCashIn->delete();
                }

                // Hapus record dari tabel buku_besar_cash_outs
                $bukuBesarCashOut = BukuBesarUsipaCashOut::where('id_kas_usipa_trans', $transaction->id)->first();
                if ($bukuBesarCashOut) {
                    $bukuBesarCashOut->delete();
                }

                // Hapus transaksi terkait
                $transaction->delete();
            }
        }

        // Setelah semua relasi dihapus, hapus data kas masuk
        $kasUsipa->delete();

        return redirect()->route('kasUsipa.index')->with('success', 'Kas Usipa berhasil dihapus.');
    }
}
