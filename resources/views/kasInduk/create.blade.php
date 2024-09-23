@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Tambah Kas Induk</h1>

    <!-- Form untuk input kas masuk -->
    <form action="{{ route('kasInduk.store') }}" method="POST">
        @csrf

        <!-- Input untuk tanggal mulai -->
        <div class="form-group mb-3">
            <label for="date">Tanggal Transaksi</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>

        <!-- Input untuk transaksi -->
        <div id="transactions-container">
            <h3>Transaksi</h3>
            <div class="transaction-group">
                <div class="form-group mb-3">
                    <label for="status-0">Status</label>
                    <select id="status-0" name="transactions[0][status]" class="form-control" required>
                        <option value="KM">Kas Masuk</option>
                        <option value="KK">Kas Keluar</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jenis_transaksi">Jenis Transaksi</label>
                    <input type="text" name="transactions[0][jenis_transaksi]" class="form-control" required>
                </div>

                <!-- Input untuk kategori buku besar dan debet_transaction, disembunyikan dulu -->
                <div class="kas-masuk-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label for="kategori_buku_besar">Buku Besar Kas Masuk</label>
                        <select name="transactions[0][kategori_buku_besar]" class="form-control">
                            @php
                            $kasMasukOptions = [
                            'bank_sp' => 'Bank SP',
                            'bank_induk' => 'Bank Induk',
                            'piutang_uang' => 'Piutang Uang',
                            'piutang_barang_toko' => 'Piutang Barang Toko',
                            'dana_sosial' => 'Dana Sosial',
                            'dana_dik' => 'Dana Pendidikan (Dik)',
                            'dana_pdk' => 'Dana Pengembangan (PDK)',
                            'resiko_kredit' => 'Resiko Kredit',
                            'simpanan_pokok' => 'Simpanan Pokok',
                            'sipanan_wajib' => 'Simpanan Wajib',
                            'sipanan_khusus' => 'Simpanan Khusus',
                            'sipanan_tunai' => 'Simpanan Tunai',
                            'jasa_sp' => 'Jasa SP',
                            'provinsi' => 'Provinsi',
                            'shu_puskop' => 'SHU Puskop',
                            'inv_usipa' => 'Investasi USIPA',
                            'lain_lain' => 'Lain-Lain',
                            ];
                            @endphp

                            @foreach ($kasMasukOptions as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="debet_transaction">Debet</label>
                        <input type="text" name="transactions[0][debet_transaction]" class="form-control rupiah">
                    </div>
                </div>

                <!-- Input untuk kredit_transaction, juga disembunyikan dulu -->
                <div class="kas-keluar-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label for="kategori_buku_besar">Buku Besar Kas Keluar</label>
                        <select name="transactions[0][kategori_buku_besar]" class="form-control">
                            @php
                            $kasKeluarOptions = [
                            'bank_sp' => 'Bank SP',
                            'bank_induk' => 'Bank Induk',
                            'simpan_pinjam' => 'Piutang Uang',
                            'inventaris' => 'Inventaris',
                            'penyertaan_puskop' => 'Penyertaan Puskop',
                            'hutang_toko' => 'Hutang Toko',
                            'dana_pengurus' => 'Dana Pengurus',
                            'dana_karyawan' => 'Dana Karyawan',
                            'dana_sosial' => 'Dana Sosial',
                            'dana_dik' => 'Dana DIK',
                            'dana_pdk' => 'Dana PDK',
                            'simp_pokok' => 'Simpanan Pokok',
                            'simp_wajib' => 'Simpanan Wajib',
                            'simp_khusus' => 'Simpanan Khusus',
                            'shu_angg' => 'SHU Anggaran',
                            'pembelian_toko' => 'Pembelian Toko',
                            'biaya_insentif' => 'Biaya Insentif',
                            'biaya_atk' => 'Biaya ATK',
                            'biaya_transport' => 'Biaya Transportasi',
                            'biaya_pembinaan' => 'Biaya Pembinaan',
                            'biaya_pembungkus' => 'Biaya Pembungkus',
                            'biaya_rat' => 'Biaya RAT',
                            'biaya_thr' => 'Biaya THR',
                            'biaya_pajak' => 'Biaya Pajak',
                            'biaya_admin' => 'Biaya Admin',
                            'biaya_training' => 'Biaya Training',
                            'inv_usipa' => 'INV Usipa',
                            'lain_lain' => 'Lain-Lain',
                            ];
                            @endphp

                            @foreach ($kasKeluarOptions as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="kredit_transaction">Kredit</label>
                        <input type="text" name="transactions[0][kredit_transaction]" class="form-control rupiah">
                    </div>
                </div>

                <button type="button" class="btn btn-secondary add-transaction">Tambah Transaksi</button>
            </div>
        </div>

        <!-- Tombol submit -->
        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const transactionsContainer = document.getElementById('transactions-container');

        function updateTransactionFields(transactionGroup) {
            const kasMasukFields = transactionGroup.querySelector('.kas-masuk-fields');
            const kasKeluarFields = transactionGroup.querySelector('.kas-keluar-fields');
            const statusSelect = transactionGroup.querySelector(
                'select[name^="transactions"][name$="[status]"]'
            ); // Ambil status di dalam grup transaksi tersebut

            const status = statusSelect.value;

            if (status === 'KM') {
                kasMasukFields.style.display = 'block';
                kasKeluarFields.style.display = 'none';

                // Enable kas masuk fields and disable kas keluar fields
                kasMasukFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
                kasKeluarFields.querySelectorAll('input, select').forEach(input => input.disabled = true);
            } else if (status === 'KK') {
                kasMasukFields.style.display = 'none';
                kasKeluarFields.style.display = 'block';

                // Enable kas keluar fields and disable kas masuk fields
                kasMasukFields.querySelectorAll('input, select').forEach(input => input.disabled = true);
                kasKeluarFields.querySelectorAll('input, select').forEach(input => input.disabled = false);
            }
        }

        // Function to initialize each transaction group with status change handling
        function initializeTransactionGroup(transactionGroup) {
            const statusSelect = transactionGroup.querySelector(
                'select[name^="transactions"][name$="[status]"]');
            statusSelect.addEventListener('change', function() {
                updateTransactionFields(transactionGroup);
            });

            // Initial setup
            updateTransactionFields(transactionGroup);
        }

        transactionsContainer.addEventListener('click', function(event) {
            if (event.target && event.target.matches('.add-transaction')) {
                const transactionGroups = document.querySelectorAll('.transaction-group');
                const lastTransactionGroup = transactionGroups[transactionGroups.length - 1];

                if (!lastTransactionGroup) return;

                const newTransactionGroup = lastTransactionGroup.cloneNode(true);

                // Clear input values for the new transaction group
                const inputs = newTransactionGroup.querySelectorAll('input');
                inputs.forEach(input => input.value = '');

                const index = transactionGroups.length; // Update index dynamically for new transaction

                newTransactionGroup.querySelectorAll('input, select').forEach(field => {
                    const name = field.name;

                    // Find the part of the name that contains the index using regex to locate digits
                    const nameParts = name.match(/^transactions\[(\d+)\](.*)$/);

                    if (nameParts) {
                        // Reconstruct the name with the updated index
                        const newName = `transactions[${index}]${nameParts[2]}`;
                        field.name = newName;
                    }
                });

                transactionsContainer.appendChild(newTransactionGroup);

                // Initialize the new transaction group with status change handling
                initializeTransactionGroup(newTransactionGroup);
            }
        });

        // Initialize existing transaction groups on page load
        const initialTransactionGroups = document.querySelectorAll('.transaction-group');
        initialTransactionGroups.forEach(group => initializeTransactionGroup(group));

    });

    // Function untuk memformat angka ke format rupiah
    function formatRupiah(angka, prefix) {
        var numberString = angka.replace(/[^,\d]/g, '').toString(),
            split = numberString.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Menambahkan titik jika yang diinput sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // Menghapus format rupiah untuk keperluan pengiriman data ke server
    function cleanRupiah(value) {
        return value.replace(/[^,\d]/g, '');
    }

    // Event listener untuk input class 'rupiah'
    const rupiahInputs = document.querySelectorAll('.rupiah');

    rupiahInputs.forEach(input => {
        input.addEventListener('keyup', function(e) {
            input.value = formatRupiah(this.value, 'Rp');
        });

        input.addEventListener('blur', function() {
            // Pastikan nilai tanpa format yang dikirim ke server
            input.value = cleanRupiah(this.value);
        });
    });

    // Form submit handler untuk memastikan format yang dikirim adalah angka murni
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        rupiahInputs.forEach(input => {
            input.value = cleanRupiah(input.value);
        });
    });
</script>
@endsection