<?php

namespace App\Http\Controllers;

use App\Models\DataBarang;
use Illuminate\Http\Request;

class DataBarangController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Mendapatkan bulan saat ini dan bulan sebelumnya
        $currentMonth = intval($month);
        $previousMonth = $currentMonth === 1 ? 12 : $currentMonth - 1;

        // Mengambil data untuk bulan yang dipilih dan bulan sebelumnya
        $data_barang = DataBarang::where(function ($query) use ($currentMonth, $year, $previousMonth) {
            $query->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $year)
                ->orWhere(function ($query) use ($previousMonth, $year) {
                    $query->whereMonth('tanggal', $previousMonth)
                        ->whereYear('tanggal', $year);
                });
        })->paginate(10);

        // Menghitung total harga untuk data yang diambil
        $total_harga = $data_barang->sum(function ($barang) {
            return $barang->stock * $barang->harga_satuan;
        });

        return view('data_barang.index', compact('data_barang', 'total_harga', 'month', 'year'));
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
            'tanggal' => 'required|date', // Add validation for the tanggal input
        ]);

        // Include the tanggal when creating the record
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
