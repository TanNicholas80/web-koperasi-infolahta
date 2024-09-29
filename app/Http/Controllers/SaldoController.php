<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\SaldoUsipa;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function create(Request $request)
    {
        $saldo = new Saldo();

        $saldo->saldo_awal = $request->saldo_awal;
        $saldo->save();

        // $kasInduk = main_cashs::with('transactions')->get();

        return redirect()->route('kasInduk.index')->with('success', 'Saldo awal berhasil ditambahkan');
    }

    public function create_usipa(Request $request)
    {
        $saldo = new SaldoUsipa();

        $saldo->saldo_awal_usipa = $request->saldo_awal_usipa;
        $saldo->save();

        // $kasInduk = main_cashs::with('transactions')->get();

        return redirect()->route('kasUsipa.index')->with('success', 'Saldo awal berhasil ditambahkan');
    }
}
