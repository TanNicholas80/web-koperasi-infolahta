@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Kas Usipa</h1>

        <form action="{{ route('kasUsipa.update', $kasUsipa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="date_usipa">Tanggal Transaksi</label>
                <input type="date" id="date" name="date_usipa" class="form-control" value="{{ $kasUsipa->date_usipa }}"
                    required>
            </div>

            @foreach ($kasUsipa->transactions as $index => $transaction)
                <fieldset>
                    <legend>Transaksi #{{ $loop->iteration }}</legend>

                    <input type="hidden" name="transactions[{{ $index }}][id]" value="{{ $transaction->id }}">

                    <div class="form-group mb-3">
                        <label for="status-0">Status</label>
                        <select id="status-0" name="transactions[{{ $index }}][status_usipa]" class="form-control"
                            required>
                            <option value="{{ $transaction->status_usipa }}">Current Status</option>
                            <option value="KM">Kas Masuk</option>
                            <option value="KK">Kas Keluar</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="jenis_transaksi_usipa">Jenis Transaksi</label>
                        <input type="text" name="transactions[{{ $index }}][jenis_transaksi_usipa]"
                            class="form-control" value="{{ $transaction->jenis_transaksi_usipa }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="periode_usipa">Periode</label>
                        <input type="text" name="transactions[{{ $index }}][periode_usipa]" class="form-control"
                            value="{{ $transaction->periode_usipa }}" required>
                    </div>

                    <!-- Input untuk kategori buku besar dan debet_transaction, disembunyikan dulu -->
                    <div class="kas-masuk-fields" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="kategori_buku_besar_usipa">Buku Besar Kas Masuk</label>
                            <select name="transactions[{{ $index }}][kategori_buku_besar_usipa]"
                                class="form-control">
                                @php
                                    $kasMasukOptions = [
                                        'bank_sp' => 'BANK S/P',
                                        'bank_induk' => 'BANK INDUK',
                                        'piutang_uang' => 'PIUTANG UANG',
                                        'piutang_brg_toko' => 'PIUTANG BARANG TOKO',
                                        'dana_sosial' => 'DANA SOSIAL',
                                        'dana_dik' => 'DANA DIK',
                                        'dana_pdk' => 'DANA PDK',
                                        'resiko_kredit' => 'RESIKO KREDIT',
                                        'simp_pokok' => 'SIMPANAN POKOK',
                                        'simp_wajib' => 'SIMPANAN WAJIB',
                                        'simp_khusus' => 'SIMPANAN KHUSUS',
                                        'penjualan_tunai' => 'PENJUALAN TUNAI',
                                        'jasa_sp' => 'JASA S/P',
                                        'provinsi' => 'PROVINSI',
                                        'shu_puskop' => 'SHU PUSKOP',
                                        'modal_disetor' => 'MODAL DISETOR',
                                    ];
                                @endphp
                                <option value="{{ $transaction->kategori_buku_besar_usipa }}">Current Kategori Buku Besar
                                    KM
                                </option>
                                @foreach ($kasMasukOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="debet_transaction_usipa">Debet</label>
                            <input type="text" name="transactions[{{ $index }}][debet_transaction_usipa]"
                                class="form-control rupiah" value="{{ $transaction->debet_transaction_usipa }}">
                        </div>
                    </div>

                    <!-- Input untuk kredit_transaction, juga disembunyikan dulu -->
                    <div class="kas-keluar-fields" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="kategori_buku_besar_usipa">Buku Besar Kas Keluar</label>
                            <select name="transactions[{{ $index }}][kategori_buku_besar_usipa]"
                                class="form-control">
                                @php
                                    $kasKeluarOptions = [
                                        'bank_sp' => 'BANK S/P',
                                        'bank_induk' => 'BANK INDUK',
                                        'simpanan_pinjaman' => 'SIMPAN PINJAM',
                                        'inventaris' => 'INVENTARIS',
                                        'penyertaan_puskop' => 'PENYERTAAN PUSKOP',
                                        'hutang_toko' => 'HUTANG TOKO',
                                        'dana_pengurus' => 'DANA PENGURUS',
                                        'dana_karyawan' => 'DANA KARYAWAN',
                                        'dana_sosial' => 'DANA SOSIAL',
                                        'dana_dik' => 'DANA DIK',
                                        'dana_pdk' => 'DANA PDK',
                                        'simp_pokok' => 'SIMPANAN POKOK',
                                        'simp_wajib' => 'SIMPANAN WAJIB',
                                        'simp_khusus' => 'SIMPANAN KHUSUS',
                                        'shu_angg' => 'SHU ANGG',
                                        'pembelian_toko' => 'PEMBELIAN TOKO',
                                        'biaya_insentif' => 'BIAYA INSENTIF',
                                        'biaya_atk' => 'BIAYA ATK',
                                        'biaya_transport' => 'BIAYA TRANSPORT',
                                        'biaya_pembinaan' => 'BIAYA PEMBINAAN',
                                        'biaya_pembungkus' => 'BIAYA PEMBUNGKUS',
                                        'biaya_rat' => 'BIAYA RAT',
                                        'biaya_thr' => 'BIAYA THR',
                                        'biaya_pajak' => 'BIAYA PAJAK',
                                        'biaya_admin' => 'BIAYA ADMIN',
                                        'biaya_training' => 'BIAYA TRAINING',
                                        'modal_disetor' => 'MODAL DISETOR',
                                        'lain_lain' => 'LAIN-LAIN',
                                    ];
                                @endphp
                                <option value="{{ $transaction->kategori_buku_besar_usipa }}">Current Kategori Buku Besar
                                    KK
                                </option>
                                @foreach ($kasKeluarOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kredit_transaction_usipa">Kredit</label>
                            <input type="text" name="transactions[{{ $index }}][kredit_transaction_usipa]"
                                class="form-control rupiah" value="{{ $transaction->kredit_transaction_usipa }}">
                        </div>
                    </div>
                </fieldset>
            @endforeach

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function untuk menampilkan/menghilangkan field berdasarkan status
            function toggleFieldsByStatus(statusField) {
                const parentFieldset = statusField.closest('fieldset');
                const status = statusField.value;
                const kasMasukFields = parentFieldset.querySelector('.kas-masuk-fields');
                const kasKeluarFields = parentFieldset.querySelector('.kas-keluar-fields');
                const debetInput = parentFieldset.querySelector('input[name$="[debet_transaction_usipa]"]');
                const kreditInput = parentFieldset.querySelector('input[name$="[kredit_transaction_usipa]"]');
                const kasMasukSelect = kasMasukFields.querySelector('select[name$="[kategori_buku_besar_usipa]"]');
                const kasKeluarSelect = kasKeluarFields.querySelector(
                'select[name$="[kategori_buku_besar_usipa]"]');

                if (status === 'KM') {
                    // Menampilkan field kas masuk dan menyembunyikan kas keluar
                    kasMasukFields.style.display = 'block';
                    kasKeluarFields.style.display = 'none';

                    // Mengaktifkan input debet dan menonaktifkan input kredit
                    debetInput.disabled = false;
                    kreditInput.disabled = true;
                    kreditInput.value = ''; // Reset nilai kredit

                    // Mengaktifkan select kategori buku besar untuk kas masuk dan menonaktifkan yang lain
                    kasMasukSelect.disabled = false;
                    kasKeluarSelect.disabled = true;

                    debetInput.required = true; // Set debet input required
                    kreditInput.required = false; // Set kredit input not required
                } else if (status === 'KK') {
                    // Menampilkan field kas keluar dan menyembunyikan kas masuk
                    kasMasukFields.style.display = 'none';
                    kasKeluarFields.style.display = 'block';

                    // Mengaktifkan input kredit dan menonaktifkan input debet
                    debetInput.disabled = true;
                    kreditInput.disabled = false;
                    debetInput.value = ''; // Reset nilai debet

                    // Mengaktifkan select kategori buku besar untuk kas keluar dan menonaktifkan yang lain
                    kasMasukSelect.disabled = true;
                    kasKeluarSelect.disabled = false;

                    debetInput.required = false; // Set debet input not required
                    kreditInput.required = true; // Set kredit input required
                } else {
                    // Menyembunyikan kedua field dan menonaktifkan input
                    kasMasukFields.style.display = 'none';
                    kasKeluarFields.style.display = 'none';
                    debetInput.disabled = true;
                    kreditInput.disabled = true;
                    kasMasukSelect.disabled = true;
                    kasKeluarSelect.disabled = true;
                    debetInput.value = '';
                    kreditInput.value = '';

                    debetInput.required = false; // Set debet input not required
                    kreditInput.required = false; // Set kredit input not required
                }
            }

            // Inisialisasi awal untuk menampilkan field sesuai status yang ada di form
            const statusFields = document.querySelectorAll('select[name$="[status_usipa]"]');
            statusFields.forEach((statusField, index) => {
                // Set data index untuk fieldset
                const parentFieldset = statusField.closest('fieldset');
                parentFieldset.dataset.index = index;

                toggleFieldsByStatus(statusField); // Panggil fungsi untuk setiap status field

                // Tambahkan event listener untuk perubahan status
                statusField.addEventListener('change', function() {
                    toggleFieldsByStatus(this);
                });
            });

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
        });
    </script>
@endsection
