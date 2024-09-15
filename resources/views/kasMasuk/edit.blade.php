@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Kas Masuk</h1>

        <form action="{{ route('kasMasuk.update', $kasMasuk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="date_start">Tanggal Mulai</label>
                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $kasMasuk->date_start }}"
                    required>
            </div>

            <!-- Input untuk tanggal akhir -->
            <div class="form-group mb-3">
                <label for="date_end">Tanggal Akhir</label>
                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $kasMasuk->date_end }}"
                    required>
            </div>

            <!-- Input untuk status -->
            <div class="form-group mb-3">
                <label for="status">Status</label>
                <input type="text" id="status" name="status" class="form-control" value="{{ $kasMasuk->status }}"
                    required>
            </div>

            @foreach ($kasMasuk->transaction as $transaction)
                <fieldset>
                    <legend>Transaksi</legend>

                    <input type="hidden" name="transactions[{{ $loop->index }}][id]" value="{{ $transaction->id }}">

                    <div class="form-group mb-3">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="transactions[{{ $loop->index }}][jenis_transaksi]" class="form-control"
                            value="{{ $transaction->jenis_transaksi }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="debet_transaction">Debet</label>
                        <input type="text" name="transactions[{{ $loop->index }}][debet_transaction]"
                            class="form-control" value="{{ $transaction->debet_transaction }}">
                    </div>

                    @foreach ($transaction->userCashIn as $userTrans)
                        <fieldset>
                            <legend>User Transaksi</legend>

                            <input type="hidden" name="transactions[{{ $loop->parent->index }}][userCashIn][{{ $loop->index }}][id]" value="{{ $userTrans->id }}">

                            <div class="form-group mb-3">
                                <label for="nama">Nama</label>
                                <input type="text" name="transactions[{{ $loop->parent->index }}][userCashIn][{{ $loop->index }}][nama]" class="form-control" value="{{ $userTrans->nama }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="debet_user">Debet User</label>
                                <input type="text" name="transactions[{{ $loop->parent->index }}][userCashIn][{{ $loop->index }}][debet_user]"
                                    class="form-control" value="{{ $userTrans->debet_user }}">
                            </div>
                        </fieldset>
                    @endforeach
                </fieldset>
            @endforeach

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
