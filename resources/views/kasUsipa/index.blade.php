@extends('layouts.user_type.auth')

@section('content')
<div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Kas Usipa</h5>
                        </div>
                        <div>
                            <div class="d-flex">
                                <form action="{{ route('saldo.create') }}" method="POST" class="d-flex align-items-center">
                                    @csrf <!-- Pastikan token CSRF ada untuk keamanan -->
                                    <input type="text" name="saldo_awal" placeholder="Masukan Saldo Awal" required class="form-control me-2">
                                    <button type="submit" class="btn bg-gradient-primary btn-sm mb-0">Saldo</button>
                                </form>
                                <a href="{{ route('kasUsipa.create') }}" class="btn bg-gradient-primary btn-sm mb-0 mx-3"
                                    type="button">+&nbsp; Kas Usipa</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Tanggal
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Keterangan
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Periode
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Buku Besar
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Debet
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kredit
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Saldo
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kasUsipa as $kas)
                                    @foreach ($kas->transactions as $transaksi)
                                    <tr>
                                        <td class="ps-4">
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $kas->date_usipa }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $transaksi->keterangan_usipa }}
                                            </p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $transaksi->status_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $transaksi->periode_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $transaksi->kategori_buku_besar_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 debet-transaction">{{ $transaksi->debet_transaction_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 kredit-transaction">{{ $transaksi->kredit_transaction_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0 saldo">{{ $kas->saldo_usipa }}</p>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('kasUsipa.edit', $kas->id) }}" class="mx-3"
                                                data-bs-toggle="tooltip" data-bs-original-title="Edit Kas Usipa">
                                                <i class="fas fa-user-edit text-secondary"></i>
                                            </a>
                                            <span>
                                                <form action="{{ route('kasUsipa.destroy', $kas->id) }}"
                                                    method="POST" style="display:inline;" class="mx-3">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link p-0 mb-0"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Delete Kas Usipa">
                                                        <i class="cursor-pointer fas fa-trash text-secondary"></i>
                                                    </button>
                                                </form>
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection