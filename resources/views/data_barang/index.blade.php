@extends('layouts.user_type.auth')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h3 class="mb-0">Persediaan Barang</h3>
                        </div>
                        <div>
                            <a href="{{ route('data_barang.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                +&nbsp; Tambah Barang
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Form Filter Bulan dan Tahun -->
                    <form method="GET" action="{{ route('data_barang.index') }}" class="mb-3">
                        <div class="d-flex align-items-center">
                            <label for="month" class="me-2">Bulan:</label>
                            <select name="month" id="month" class="form-select me-3">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>

                            <label for="year" class="me-2">Tahun:</label>
                            <select name="year" id="year" class="form-select me-3">
                                @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>

                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">Kode Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Stock</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga Satuan</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Harga Total</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                @foreach ($data_barang as $barang)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0 ps-3">{{ $barang->kode_brg }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $barang->nama_brg }}</p>
                                        </td>
                                        <td class="text-center align-middle">
                                            <p class="text-xs font-weight-bold mb-0">{{ $barang->stock }}</p>
                                        </td>                                        
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($barang->stock * $barang->harga_satuan, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('data_barang.edit', $barang->id) }}" class="mx-3" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit text-secondary"></i>
                                            </a>
                                            <form action="{{ route('data_barang.destroy', $barang->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 mb-0" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fas fa-trash text-secondary"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                            <tfoot>
                                <tr>
                                    <td colspan="9" class="text-end">
                                        <div class="mt-4 p-3 bg-light w-100">
                                            <h5 class="text-end">Total Semua Harga: 
                                                <span class="text-success font-weight-bold">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                                            </h5>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                            
                        </table>

                        <div class="d-flex justify-content-center">
                            {{ $data_barang->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
