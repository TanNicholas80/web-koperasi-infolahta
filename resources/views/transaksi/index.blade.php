@extends('layouts.user_type.auth')
@section('content')
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

                <!-- Filter by Month and Year -->
                <div class="card-body">
                    <form method="GET" action="{{ route('transaksi.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="bulan" class="form-label">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="tahun" class="form-label">Tahun</label>
                                <select name="tahun" id="tahun" class="form-select">
                                    @for($i = 2020; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <button type="submit" class="btn btn-primary mt-2">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Harga</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Transaksi</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Anggota</th> <!-- Kolom Nama Anggota -->
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Transaksi</th> <!-- Kolom Jenis Transaksi -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksis as $transaksi)
                                    <tr>
                                        <td class="text-xs font-weight-bold mb-0 ps-4">{{ $transaksi->dataBarang->kode_brg }}</td>
                                        <td class="text-xs font-weight-bold mb-0 ps-2">{{ $transaksi->dataBarang->nama_brg }}</td>
                                        <td class="text-xs font-weight-bold mb-0 ps-4">{{ $transaksi->jumlah }}</td>
                                        <td class="text-xs font-weight-bold mb-0 text-center">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                        <td class="text-xs font-weight-bold mb-0 text-center">{{ $transaksi->tanggal_transaksi }}</td>
                                        <td class="text-xs font-weight-bold mb-0 text-center">{{ $transaksi->nama_anggota }}</td> <!-- Menampilkan Nama Anggota -->
                                        <td class="text-xs font-weight-bold mb-0 text-center">{{ $transaksi->jenis_transaksi }}</td> <!-- Menampilkan Jenis Transaksi -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination links -->
                        {{ $transaksis->links() }}

                        <!-- Total Pemasukan Bulanan -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <h5 class="text-end">Total Pemasukan Bulan {{ date('F', mktime(0, 0, 0, $bulan, 1)) }}: 
                                <span class="text-success font-weight-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
