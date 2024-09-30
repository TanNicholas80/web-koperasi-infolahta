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

    // public function store(Request $request)
    // {
    //     // Validate the transaction request
    //     $request->validate([
    //         'data_barang_id' => 'required|exists:data_barangs,id',
    //         'jumlah' => 'required|integer|min:1',
    //     ]);

    //     // Fetch the selected item
    //     $barang = DataBarang::findOrFail($request->data_barang_id);

    //     // Calculate the total price
    //     $total_harga = $barang->harga_satuan * $request->jumlah;

    //     // Create the transaction record
    //     Transaksi::create([
    //         'data_barang_id' => $request->data_barang_id,
    //         'jumlah' => $request->jumlah,
    //         'total_harga' => $total_harga,
    //     ]);

    //     // Update stock in `DataBarang`
    //     $barang->update([
    //         'stock' => $barang->stock - $request->jumlah
    //     ]);

    //     return redirect()->route('transaksi.index')
    //                      ->with('success', 'Transaksi berhasil dilakukan.');
    // }

    public function store(Request $request)
    {
        // Validate input array and new fields
        $request->validate([
            'data_barang_id.*' => 'required|exists:data_barangs,id',
            'jumlah.*' => 'required|integer|min:1',
            'nama_anggota' => 'required|string|max:255',
            'jenis_transaksi' => 'required|in:debet,kredit',
        ]);
    
        $dataBarangIds = $request->data_barang_id;
        $jumlahs = $request->jumlah;
        $nama_anggota = $request->nama_anggota;
        $jenis_transaksi = $request->jenis_transaksi;
    
        foreach ($dataBarangIds as $index => $dataBarangId) {
            $barang = DataBarang::findOrFail($dataBarangId);
            $jumlah = $jumlahs[$index];
    
            // Calculate total price
            $total_harga = $barang->harga_satuan * $jumlah;
    
            // Create new transaction
            Transaksi::create([
                'data_barang_id' => $dataBarangId,
                'jumlah' => $jumlah,
                'total_harga' => $total_harga,
                'nama_anggota' => $nama_anggota,  // Add this
                'jenis_transaksi' => $jenis_transaksi,  // Add this
            ]);
    
            // Update stock
            $barang->update([
                'stock' => $barang->stock - $jumlah
            ]);
        }
    
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }


    // public function index()
    // {
    //     // Get all transactions
    //     $transaksis = Transaksi::with('dataBarang')->paginate(100);
    //     $totalPemasukan = Transaksi::sum('total_harga');

    //     // Mengirimkan data transaksi dan total pemasukan ke view
    //     return view('transaksi.index', compact('transaksis', 'totalPemasukan'));

    // }

    public function index(Request $request)
{
    // Ambil bulan dan tahun dari request, default ke bulan ini
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));

    // Ambil transaksi berdasarkan bulan dan tahun yang dipilih
    $transaksis = Transaksi::with('dataBarang')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->paginate(100);

    // Hitung total pemasukan per bulan
    $totalPemasukan = Transaksi::whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->sum('total_harga');

    // Mengirimkan data transaksi dan total pemasukan ke view
    return view('transaksi.index', compact('transaksis', 'totalPemasukan', 'bulan', 'tahun'));
}

    
}

