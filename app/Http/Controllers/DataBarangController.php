<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use Illuminate\Http\Request;

class DataBarangController extends Controller
{
    public function index()
    {
       // Mengambil semua data barang dari tabel
       $data_barang = DataBarang::paginate(10); // Menampilkan 10 item per halaman


       // Mengirimkan data barang ke view
       return view('data_barang.index', compact('data_barang'));
    }

    public function create()
    {
        return view('data_barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_brg' => 'required|unique:data_barangs,kode_brg',
            'nama_brg' => 'required',
            'stock' => 'required|integer',
            'harga_satuan' => 'required|numeric',
        ]);

        DataBarang::create($request->all());

        return redirect()->route('data_barang.index')
                         ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = DataBarang::findOrFail($id);
        return view('data_barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_brg' => 'required|unique:data_barangs,kode_brg,' . $id,
            'nama_brg' => 'required',
            'stock' => 'required|integer',
            'harga_satuan' => 'required|numeric',
        ]);

        $barang = DataBarang::findOrFail($id);
        $barang->update($request->all());

        return redirect()->route('data_barang.index')
                         ->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy($id)
    {
        $barang = DataBarang::findOrFail($id);
        $barang->delete();

        return redirect()->route('data_barang.index')
                         ->with('success', 'Barang berhasil dihapus.');
    }
}

