@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h2>Buat Transaksi Baru</h2>
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="data_barang_id">Barang</label>
                <select name="data_barang_id" id="data_barang_id" class="form-control">
                    @foreach($data_barang as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama_brg }} - Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>
@endsection
