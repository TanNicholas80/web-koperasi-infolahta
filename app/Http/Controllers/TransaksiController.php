<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataBarang;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function create()
    {
        // Get available items for the transaction
        $data_barang = DataBarang::all();
        return view('transaksi.create', compact('data_barang'));
    }

    public function store(Request $request)
    {
        // Validate the transaction request
        $request->validate([
            'data_barang_id' => 'required|exists:data_barangs,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Fetch the selected item
        $barang = DataBarang::findOrFail($request->data_barang_id);

        // Calculate the total price
        $total_harga = $barang->harga_satuan * $request->jumlah;

        // Create the transaction record
        Transaksi::create([
            'data_barang_id' => $request->data_barang_id,
            'jumlah' => $request->jumlah,
            'total_harga' => $total_harga,
        ]);

        // Update stock in `DataBarang`
        $barang->update([
            'stock' => $barang->stock - $request->jumlah
        ]);

        return redirect()->route('transaksi.index')
                         ->with('success', 'Transaksi berhasil dilakukan.');
    }

    public function index()
    {
        // Get all transactions
        $transaksis = Transaksi::with('dataBarang')->paginate(10);
        return view('transaksi.index', compact('transaksis'));
    }
}

