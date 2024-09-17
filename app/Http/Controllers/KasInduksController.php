<?php

namespace App\Http\Controllers;

use App\Models\buku_besar_cash_ins;
use App\Models\buku_besar_cash_outs;
use App\Models\cash_in_trans;
use App\Models\cash_out_trans;
use App\Models\LogSaldo;
use App\Models\main_cash_trans;
use App\Models\main_cashs;
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

        $mainCash = new main_cashs();
        $mainCash->date = $date->format('Y-m-d');
        // Cek apakah saldo_awal diinput oleh user
        if ($request->has('saldo_awal') && $request->saldo_awal !== null) {
            // Jika saldo_awal diinput oleh user, gunakan saldo_awal dari input user
            $mainCash->saldo_awal = $request->saldo_awal;
            $mainCash->saldo = $request->saldo_awal;  // Juga gunakan untuk saldo
        } else {
            // Jika saldo_awal tidak diinput, gunakan saldo terakhir dari database
            $mainCash->saldo_awal = $lastSaldo;
            $mainCash->saldo = $lastSaldo;
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
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
