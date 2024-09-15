<?php

namespace App\Http\Controllers;

use App\Models\cash_out_trans;
use App\Models\cash_outs;
use App\Models\main_cashs;
use App\Models\trans_type_cash_outs;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data kas masuk beserta relasi transaction dan userCashIn
        $kasKeluar = cash_outs::with('transaction')->get();

        // Kirim data ke view
        return view('kasKeluar.index', compact('kasKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kasKeluar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date_start = Carbon::parse($request->date_start);
        $date_end = Carbon::parse($request->date_end);

        // Ambil bulan dan tahun dari `date_start`
        $month_start = $date_start->format('m');
        $year_start = $date_start->format('Y');

        // Ambil entry terakhir dari database berdasarkan bulan dan tahun
        $lastCashOut = cash_outs::orderBy('date_start', 'desc')->first();

        // Default nilai periode
        $periode = 1;
        $lastMonth = null;
        $lastYear = null;

        if ($lastCashOut) {
            $lastMonth = Carbon::parse($lastCashOut->date_start)->format('m');
            $lastYear = Carbon::parse($lastCashOut->date_start)->format('Y');
        }

        $cashOut = new cash_outs();
        $cashOut->date_start = $date_start->format('Y-m-d');
        $cashOut->date_end = $date_end->format('Y-m-d');
        $cashOut->status = $request->status;
        $cashOut->save();

        // Simpan transaksi kas masuk
        foreach ($request->transactions as $transactionData) {
            $keterangan = $transactionData['jenis_transaksi'] .
                ' tgl ' . $request->date_start .
                ' - ' . $request->date_end;

            $lastTransaction = cash_out_trans::orderBy('created_at', 'desc')->first();
            if ($lastTransaction) {
                // Ambil transaksi terakhir di cash_in_trans berdasarkan cash_in_id dan periode
                if ($lastMonth == $month_start && $lastYear == $year_start) {
                    // Jika bulan dan tahun sama, increment periode dari entri terakhir
                    $periode = $lastTransaction->periode + 1;
                } else {
                    // Jika bulan atau tahun berbeda, reset periode ke 1
                    $periode = 1;
                }
            }

            // Buat transaksi kas masuk
            $transaction = $cashOut->transaction()->create([
                'jenis_transaksi' => $transactionData['jenis_transaksi'],
                'keterangan' => $keterangan,
                'kredit_transaction' => $transactionData['kredit_transaction'],
                'periode' => $periode,
            ]);

            // Ambil ID dari transaksi yang baru disimpan
            $cashOutId = $transaction->id;

            $mainCashs = new main_cashs();
            $mainCashs->id_cash_outs = $cashOutId;
            if (!empty($transactionData['kredit_transaction'])) {
                $mainCashs->saldo = $transactionData['kredit_transaction'];
            } else {
                $mainCashs->saldo = 0;
            }
            $mainCashs->save();

            // Simpan user transaksi kas masuk
            $totalKreditTypeTrans = 0;
            $hasTypeTrans = false;
            foreach ($transactionData['transTypeCashOut'] as $transTypeData) {
                if (!empty($transTypeData['detail_kas_keluar']) && !empty($transTypeData['kredit_trans_type'])) {
                    $typeTrans = $transaction->transTypeCashOut()->create([
                        'detail_kas_keluar' => $transTypeData['detail_kas_keluar'],
                        'kredit_trans_type' => $transTypeData['kredit_trans_type'],
                    ]);

                    // Accumulate the total debet_user
                    $totalKreditTypeTrans += $transTypeData['kredit_trans_type'];
                    $hasTypeTrans = true;
                }
            }

            // Update debet_transaction hanya jika ada user transaksi
            if ($hasTypeTrans) {
                $transaction->update([
                    'kredit_transaction' => $totalKreditTypeTrans,
                ]);

                // Update the amount in main_cash when debet_transaction is updated
                $mainCashs->update([
                    'saldo' => $totalKreditTypeTrans,
                ]);
            }
        }

        return redirect()->route('kasKeluar.index')->with('success', 'Kas Keluar berhasil disimpan.');
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
        // Ambil data kas masuk beserta transaksi dan userCashIn yang terkait
        $kasKeluar = cash_outs::with('transaction.transTypeCashOut')->findOrFail($id);

        // Kirim data ke view
        return view('kasKeluar.edit', compact('kasKeluar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cashOut = cash_outs::findOrFail($id);

        // Update kas masuk
        $cashOut->date_start = Carbon::parse($request->date_start);
        $cashOut->date_end = Carbon::parse($request->date_end);
        $cashOut->status = $request->status;
        $cashOut->save();

        foreach ($request->transactions as $index => $transactionData) {
            $transaction = cash_out_trans::find($transactionData['id']);
            $keterangan = $transactionData['jenis_transaksi'] .
                ' tgl ' . $request->date_start .
                ' - ' . $request->date_end;

            // Update transaksi
            $transaction->jenis_transaksi = $transactionData['jenis_transaksi'];
            $transaction->keterangan = $keterangan;
            $transaction->kredit_transaction = $transactionData['kredit_transaction'];
            $transaction->save();

            $cashOutId = $transaction->id;

            // Cek apakah entri dengan id_cash_ins sudah ada
            $mainCashs = main_cashs::where('id_cash_outs', $cashOutId)->first();

            if ($mainCashs) {
                // Jika entri ditemukan, lakukan update
                $mainCashs->saldo = !empty($transactionData['kredit_transaction']) ? $transactionData['kredit_transaction'] : 0;
                $mainCashs->save();
            }

            $totalKreditTypeTrans = 0;
            $hasTypeTrans = false;
            // Update user transaksi kas masuk
            if (isset($transactionData['transTypeCashOut'])) {
                foreach ($transactionData['transTypeCashOut'] as $typeIndex => $transTypeData) {
                    // Cek apakah ID user transaksi ada
                    if (isset($transTypeData['id'])) {
                        $typeTrans = trans_type_cash_outs::find($transTypeData['id']);
                        if ($typeTrans) {
                            // Update user transaksi
                            $typeTrans->detail_kas_keluar = $transTypeData['detail_kas_keluar'];
                            $typeTrans->kredit_trans_type = $transTypeData['kredit_trans_type'];
                            $typeTrans->save();
                        }
                    }

                    // Akumulasi nilai kredit_trans_type
                    $totalKreditTypeTrans += $transTypeData['kredit_trans_type'];
                    $hasTypeTrans = true;
                }
            }
            if ($hasTypeTrans) {
                // Update total kredit_transaction di transaksi utama
                $transaction->update([
                    'kredit_transaction' => $totalKreditTypeTrans,
                ]);
                // Update the amount in main_cash when debet_transaction is updated
                $mainCashs->update([
                    'saldo' => $totalKreditTypeTrans,
                ]);
            }
        }

        return redirect()->route('kasKeluar.index')->with('success', 'Kas keluar berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil data kas masuk beserta transaksi dan userCashIn yang terkait
        $cashOut = cash_outs::with('transaction.transTypeCashOut')->findOrFail($id);

        // Hapus semua user transaksi yang terkait dengan setiap transaksi
        foreach ($cashOut->transaction as $transaction) {
            // Hapus semua user transaksi terkait dengan transaksi ini
            if ($transaction->transTypeCashOut()->exists()) {
                foreach ($transaction->transTypeCashOut as $typeTrans) {
                    $typeTrans->delete();
                }
            }

            // Hapus transaksi terkait
            $transaction->delete();
        }

        // Setelah semua relasi dihapus, hapus data kas masuk
        $cashOut->delete();

        return redirect()->route('kasKeluar.index')->with('success', 'Kas keluar berhasil dihapus.');
    }
}
