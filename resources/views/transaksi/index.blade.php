@extends('layouts.user_type.auth')
@section('content')
    {{-- <div class="container">
        <h2>Daftar Transaksi</h2>
        <div>
            <a href="{{ route('data_barang.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                +&nbsp; Tambah Barang
            </a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead> --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 mx-4">
                        <div class="card-header pb-0">
                            <div class="d-flex flex-row justify-content-between">
                                <div>
                                    <h3 class="mb-0">Transaksi Penjualan</h3>
                                </div>
                                <div>
                                    <a href="{{ route('transaksi.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                        +&nbsp; Tambah Transaksi
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">Kode Barang</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Harga</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                        </tr>
                                    </thead>
            <tbody>
                @foreach($transaksis as $transaksi)
                    <tr>
                        <td class="text-xs font-weight-bold mb-0 ps-4">{{ $transaksi->dataBarang->kode_brg }}</td>
                        <td class="text-xs font-weight-bold mb-0 ps-2">{{ $transaksi->dataBarang->nama_brg }}</td>
                        <td class="text-xs font-weight-bold mb-0 ps-4">{{ $transaksi->jumlah }}</td>
                        <td class="text-xs font-weight-bold mb-0 ps-8" >Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="text-xs font-weight-bold mb-0 text-center">{{ $transaksi->tanggal_transaksi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $transaksis->links() }}
    </div>
@endsection
