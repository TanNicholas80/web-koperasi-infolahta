@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Tambah Kas Masuk</h1>

        <!-- Form untuk input kas masuk -->
        <form action="{{ route('kasMasuk.store') }}" method="POST">
            @csrf

            <!-- Input untuk tanggal mulai -->
            <div class="form-group mb-3">
                <label for="date_start">Tanggal Mulai</label>
                <input type="date" id="date_start" name="date_start" class="form-control" required>
            </div>

            <!-- Input untuk tanggal akhir -->
            <div class="form-group mb-3">
                <label for="date_end">Tanggal Akhir</label>
                <input type="date" id="date_end" name="date_end" class="form-control" required>
            </div>

            <!-- Input untuk status -->
            <div class="form-group mb-3">
                <label for="status">Status</label>
                <input type="text" id="status" name="status" class="form-control" required>
            </div>

            <!-- Input untuk transaksi -->
            <div id="transactions-container">
                <h3>Transaksi</h3>
                <div class="transaction-group">
                    <div class="form-group mb-3">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="transactions[0][jenis_transaksi]" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="debet_transaction">Debet</label>
                        <input type="text" name="transactions[0][debet_transaction]" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <h3>Transaksi User</h3>
                        <div class="user-trans-container">
                            <div class="user-trans-group">
                                <div class="form-group mb-3">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="transactions[0][userCashIn][0][nama]" class="form-control">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="debet_user">Debet User</label>
                                    <input type="text" name="transactions[0][userCashIn][0][debet_user]"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary add-user-trans" data-transaction-index="0">Tambah
                            User Transaksi</button>
                    </div>
                    <button type="button" class="btn btn-secondary add-transaction">Tambah Transaksi</button>
                </div>
            </div>

            <!-- Tombol submit -->
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>

    <!-- Script untuk menambah transaksi dan user transaksi -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let transactionIndex = 1;

            // Fungsi untuk menambah transaksi baru
            document.querySelector('.add-transaction').addEventListener('click', function() {
                let container = document.getElementById('transactions-container');
                let newTransaction = document.createElement('div');
                newTransaction.className = 'transaction-group';
                newTransaction.innerHTML = `
            <hr>
            <div class="form-group mb-3">
                <label for="jenis_transaksi">Jenis Transaksi</label>
                <input type="text" name="transactions[${transactionIndex}][jenis_transaksi]" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="debet_transaction">Debet</label>
                <input type="text" name="transactions[${transactionIndex}][debet_transaction]" class="form-control">
            </div>

            <div class="form-group mb-3">
                <h3>Transaksi User</h3>
                <div class="user-trans-container">
                    <div class="user-trans-group">
                        <div class="form-group mb-3">
                            <label for="nama">Nama</label>
                            <input type="text" name="transactions[${transactionIndex}][userCashIn][0][nama]" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="debet_user">Debet User</label>
                            <input type="text" name="transactions[${transactionIndex}][userCashIn][0][debet_user]" class="form-control">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary add-user-trans" data-transaction-index="${transactionIndex}">Tambah User Transaksi</button>
            </div>
        `;
                container.appendChild(newTransaction);
                transactionIndex++;
            });

            // Event listener dengan event delegation untuk tombol tambah user transaksi
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('add-user-trans')) {
                    let transactionGroup = e.target.closest('.transaction-group');
                    let userTransContainer = transactionGroup.querySelector('.user-trans-container');
                    let userTransGroups = userTransContainer.querySelectorAll('.user-trans-group');
                    let userIndex = userTransGroups.length; // Hitung berapa user transaksi yang sudah ada

                    // Tambah user transaksi baru
                    let newUserTransaction = document.createElement('div');
                    newUserTransaction.className = 'user-trans-group';
                    newUserTransaction.innerHTML = `
            <div class="form-group mb-3">
                <label for="nama">Nama</label>
                <input type="text" name="transactions[${e.target.getAttribute('data-transaction-index')}][userCashIn][${userIndex}][nama]" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="debet_user">Debet User</label>
                <input type="text" name="transactions[${e.target.getAttribute('data-transaction-index')}][userCashIn][${userIndex}][debet_user]" class="form-control">
            </div>
        `;
                    userTransContainer.appendChild(newUserTransaction);
                }
            });
        });
    </script>
@endsection
