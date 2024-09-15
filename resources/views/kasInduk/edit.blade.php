@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Kas Keluar</h1>

        <form action="{{ route('kasKeluar.update', $kasKeluar->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="date_start">Tanggal Mulai</label>
                <input type="date" id="date_start" name="date_start" class="form-control" value="{{ $kasKeluar->date_start }}"
                    required>
            </div>

            <!-- Input untuk tanggal akhir -->
            <div class="form-group mb-3">
                <label for="date_end">Tanggal Akhir</label>
                <input type="date" id="date_end" name="date_end" class="form-control" value="{{ $kasKeluar->date_end }}"
                    required>
            </div>

            <!-- Input untuk status -->
            <div class="form-group mb-3">
                <label for="status">Status</label>
                <input type="text" id="status" name="status" class="form-control" value="{{ $kasKeluar->status }}"
                    required>
            </div>

            @foreach ($kasKeluar->transaction as $transaction)
                <fieldset>
                    <legend>Transaksi</legend>

                    <input type="hidden" name="transactions[{{ $loop->index }}][id]" value="{{ $transaction->id }}">

                    <div class="form-group mb-3">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="transactions[{{ $loop->index }}][jenis_transaksi]" class="form-control"
                            value="{{ $transaction->jenis_transaksi }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="kredit_transaction">Kredit</label>
                        <input type="text" name="transactions[{{ $loop->index }}][kredit_transaction]"
                            class="form-control" value="{{ $transaction->kredit_transaction }}">
                    </div>

                    @foreach ($transaction->transTypeCashOut as $typeTrans)
                        <fieldset>
                            <legend>User Transaksi</legend>

                            <input type="hidden"
                                name="transactions[{{ $loop->parent->index }}][transTypeCashOut][{{ $loop->index }}][id]"
                                value="{{ $typeTrans->id }}">

                            <div class="form-group mb-3">
                                <label for="detail_kas_keluar">Detail Kas Keluar</label>
                                <input type="text"
                                    name="transactions[{{ $loop->parent->index }}][transTypeCashOut][{{ $loop->index }}][detail_kas_keluar]"
                                    class="form-control" value="{{ $typeTrans->detail_kas_keluar }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="kredit_trans_type">Kredit Transaksi</label>
                                <input type="text"
                                    name="transactions[{{ $loop->parent->index }}][transTypeCashOut][{{ $loop->index }}][kredit_trans_type]"
                                    class="form-control" value="{{ $typeTrans->kredit_trans_type }}">
                            </div>
                        </fieldset>
                    @endforeach
                </fieldset>
            @endforeach

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
@endsection
