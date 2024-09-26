@extends('layouts.user_type.auth')

@section('content')
    <h1>Edit Barang</h1>

    <form action="{{ route('data_barang.update', $barang->id) }}" method="POST">
    @csrf
    @method('PUT')
    <!-- Input untuk transaksi -->
    <div id="transactions-container">
            <div class="form-group mb-3">
                <label for="jenis_transaksi">Kode Barang</label>
                <input type="text" name="kode_brg" value="{{ $barang->kode_brg }}" class="form-control" required>
            </div>   
            <div class="form-group mb-3">
                <label for="jenis_transaksi">Nama Barang</label>
                <input type="text" name="nama_brg" value="{{ $barang->nama_brg }}" class="form-control "  required>
            </div>
            <div class="form-group mb-3">
                <label for="jenis_transaksi">Stock</label>
                <input type="text" name="stock" value="{{ $barang->stock }}" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="harga_satuan">Harga Satuan</label>
                <input type="text" name="harga_satuan" value="{{ $barang->harga_satuan }}" class="form-control rupiah" required>
            </div>
        </div>
    </div>

    <!-- Tombol submit -->
    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
</form>
</div>
@endsection
