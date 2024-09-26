@extends('layouts.user_type.auth')

@section('content')
<div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Buku Besar Kas Masuk</h5>
                        </div>
                        <form action="{{ route('buku-masuk.export') }}" method="GET" class="d-flex align-items-center">
                            <input type="text" name="year" placeholder="Enter year" required class="form-control me-2">
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Download Excel</button>
                        </form>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        @include('bukuMasuk.table_buku_masuk', [$kasInduk, $bukuMasuk, $totals])
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <form action="{{ route('buku-masuk') }}" method="GET" class="d-flex align-items-center">
                            @php
                            $currentYear = \Carbon\Carbon::now()->year;
                            $currentMonth = \Carbon\Carbon::now()->month;
                            @endphp
                            <select name="month" class="form-select me-2" style="width: 150px;">
                                @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}" {{ request('month', $currentMonth) == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                                </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-select me-2" style="width: 150px;">
                                @foreach(range($currentYear - 5, $currentYear) as $year)
                                <option value="{{ $year }}" {{ request('year', $currentYear) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection