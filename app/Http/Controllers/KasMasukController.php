<?php

namespace App\Http\Controllers;

use App\Models\cash_in_trans;
use App\Models\cash_ins;
use App\Models\main_cashs;
use App\Models\user_trans_cash_ins;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class KasMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data kas masuk beserta relasi transaction dan userCashIn
        $kasMasuk = cash_ins::with('transaction')->get();

        // Kirim data ke view
        return view('kasMasuk.index', compact('kasMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kasMasuk.create');
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
        $lastCashIn = cash_ins::orderBy('date_start', 'desc')->first();

        // Default nilai periode
        $periode = 1;
        $lastMonth = null;
        $lastYear = null;

        // Ambil bulan dan tahun dari entry terakhir
        if ($lastCashIn) {
            $lastMonth = Carbon::parse($lastCashIn->date_start)->format('m');
            $lastYear = Carbon::parse($lastCashIn->date_start)->format('Y');
        }


        $cashIn = new cash_ins();
        $cashIn->date_start = $date_start->format('Y-m-d');
        $cashIn->date_end = $date_end->format('Y-m-d');
        $cashIn->status = $request->status;
        $cashIn->save();

        // Simpan transaksi kas masuk
        foreach ($request->transactions as $transactionData) {
            $keterangan = $transactionData['jenis_transaksi'] .
                ' tgl ' . $request->date_start .
                ' - ' . $request->date_end;

            $lastTransaction = cash_in_trans::orderBy('created_at', 'desc')->first();
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
            $transaction = $cashIn->transaction()->create([
                'jenis_transaksi' => $transactionData['jenis_transaksi'],
                'keterangan' => $keterangan,
                'debet_transaction' => $transactionData['debet_transaction'],
                'periode' => $periode,
            ]);

            // Ambil ID dari transaksi yang baru disimpan
            $cashInId = $transaction->id;

            $mainCashs = new main_cashs();
            $mainCashs->id_cash_ins = $cashInId;
            if (!empty($transactionData['debet_transaction'])) {
                $mainCashs->saldo = $transactionData['debet_transaction'];
            } else {
                $mainCashs->saldo = 0;
            }
            $mainCashs->save();

            // Simpan user transaksi kas masuk
            $totalDebetUser = 0;
            $hasUserTrans = false;
            foreach ($transactionData['userCashIn'] as $userTransData) {
                if (!empty($userTransData['nama']) && !empty($userTransData['debet_user'])) {
                    $userTrans = $transaction->userCashIn()->create([
                        'nama' => $userTransData['nama'],
                        'debet_user' => $userTransData['debet_user'],
                    ]);

                    // Accumulate the total debet_user
                    $totalDebetUser += $userTransData['debet_user'];
                    $hasUserTrans = true;
                }
            }

            // Update debet_transaction hanya jika ada user transaksi
            if ($hasUserTrans) {
                $transaction->update([
                    'debet_transaction' => $totalDebetUser,
                ]);

                // Update the amount in main_cash when debet_transaction is updated
                $mainCashs->update([
                    'saldo' => $totalDebetUser,
                ]);
            }
        }

        return redirect()->route('kasMasuk.index')->with('success', 'Kas masuk berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data kas masuk beserta transaksi dan userCashIn yang terkait
        $kasMasuk = cash_ins::with('transaction.userCashIn')->findOrFail($id);

        // Kirim data ke view
        return view('kasMasuk.edit', compact('kasMasuk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cashIn = cash_ins::findOrFail($id);

        // Update kas masuk
        $cashIn->date_start = Carbon::parse($request->date_start);
        $cashIn->date_end = Carbon::parse($request->date_end);
        $cashIn->status = $request->status;
        $cashIn->save();

        foreach ($request->transactions as $index => $transactionData) {
            $transaction = cash_in_trans::find($transactionData['id']);
            $keterangan = $transactionData['jenis_transaksi'] .
                ' tgl ' . $request->date_start .
                ' - ' . $request->date_end;

            // Update transaksi
            $transaction->jenis_transaksi = $transactionData['jenis_transaksi'];
            $transaction->keterangan = $keterangan;
            $transaction->debet_transaction = $transactionData['debet_transaction'];
            $transaction->save();

            log::info('debet transaksi', ['transaksi' => $transactionData['debet_transaction']]);

            $cashInId = $transaction->id;

            // Cek apakah entri dengan id_cash_ins sudah ada
            $mainCashs = main_cashs::where('id_cash_ins', $cashInId)->first();

            if ($mainCashs) {
                // Jika entri ditemukan, lakukan update
                $mainCashs->saldo = !empty($transactionData['debet_transaction']) ? $transactionData['debet_transaction'] : 0;
                $mainCashs->save();
            }


            $totalUserDebetTrans = 0;
            $hasUserTrans = false;
            // Update user transaksi kas masuk
            if (isset($transactionData['userCashIn'])) {
                foreach ($transactionData['userCashIn'] as $userIndex => $userTransData) {
                    // Cek apakah ID user transaksi ada
                    if (isset($userTransData['id'])) {
                        $userTrans = user_trans_cash_ins::find($userTransData['id']);
                        if ($userTrans) {
                            // Update user transaksi
                            $userTrans->nama = $userTransData['nama'];
                            $userTrans->debet_user = $userTransData['debet_user'];
                            $userTrans->save();
                        }
                    }
                    // Akumulasi nilai kredit_trans_type
                    $totalUserDebetTrans += $userTransData['debet_user'];
                    $hasUserTrans = true;
                }
            }
            // Update total kredit_transaction di transaksi utama
            if ($hasUserTrans) {
                $transaction->update([
                    'debet_transaction' => $totalUserDebetTrans,
                ]);

                $mainCashs->update([
                    'saldo' => $totalUserDebetTrans,
                ]);
            }
        }

        return redirect()->route('kasMasuk.index')->with('success', 'Kas masuk berhasil di-update.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Ambil data kas masuk beserta transaksi dan userCashIn yang terkait
        $cashIn = cash_ins::with('transaction.userCashIn')->findOrFail($id);

        // Hapus semua user transaksi yang terkait dengan setiap transaksi
        foreach ($cashIn->transaction as $transaction) {
            // Hapus semua user transaksi terkait dengan transaksi ini
            if ($transaction->userCashIn()->exists()) {
                foreach ($transaction->userCashIn as $userTrans) {
                    $userTrans->delete();
                }
            }

            // Hapus transaksi terkait
            $transaction->delete();
        }

        // Setelah semua relasi dihapus, hapus data kas masuk
        $cashIn->delete();

        return redirect()->route('kasMasuk.index')->with('success', 'Kas masuk berhasil dihapus.');
    }
}
