@extends('layouts.user_type.auth')

@section('content')
    <div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Buku Masuk</h5>
                            </div>
                            <form action="{{ route('buku-masuk.export') }}" method="GET" class="d-flex align-items-center">
                                <input type="text" name="year" placeholder="Enter year" required class="form-control me-2">
                                <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Download Excel</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            @include('bukuMasuk.table_buku_masuk', [$kasInduk, $bukuMasuk])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
