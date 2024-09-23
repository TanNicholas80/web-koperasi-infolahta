@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h1>Edit Kas Induk</h1>

        <form action="{{ route('kasInduk.update', $kasInduk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="date">Tanggal Transaksi</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ $kasInduk->date }}" required>
            </div>

            @foreach ($kasInduk->transactions as $index => $transaction)
                <fieldset>
                    <legend>Transaksi #{{ $loop->iteration }}</legend>

                    <input type="hidden" name="transactions[{{ $index }}][id]" value="{{ $transaction->id }}">

                    <div class="form-group mb-3">
                        <label for="status-0">Status</label>
                        <select id="status-0" name="transactions[{{ $index }}][status]" class="form-control"
                            required>
                            <option value="{{ $transaction->status }}">Current Status</option>
                            <option value="KM">Kas Masuk</option>
                            <option value="KK">Kas Keluar</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" name="transactions[{{ $index }}][jenis_transaksi]" class="form-control"
                            value="{{ $transaction->jenis_transaksi }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="periode">Periode</label>
                        <input type="text" name="transactions[{{ $index }}][periode]" class="form-control"
                            value="{{ $transaction->periode }}" required>
                    </div>

                    <!-- Input untuk kategori buku besar dan debet_transaction, disembunyikan dulu -->
                    <div class="kas-masuk-fields" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="kategori_buku_besar">Buku Besar Kas Masuk</label>
                            <select name="transactions[{{ $index }}][kategori_buku_besar]" class="form-control">
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
                                        'penjualan_tunai' => 'Penjualan Tunai',
                                        'jasa_sp' => 'Jasa SP',
                                        'provinsi' => 'Provinsi',
                                        'shu_puskop' => 'SHU Puskop',
                                        'inv_usipa' => 'Investasi USIPA',
                                        'lain_lain' => 'Lain-Lain',
                                    ];
                                @endphp
                                <option value="{{ $transaction->kategori_buku_besar }}">Current Kategori Buku Besar KM
                                </option>
                                @foreach ($kasMasukOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="debet_transaction">Debet</label>
                            <input type="text" name="transactions[{{ $index }}][debet_transaction]"
                                class="form-control rupiah" value="{{ $transaction->debet_transaction }}">
                        </div>
                    </div>

                    <!-- Input untuk kredit_transaction, juga disembunyikan dulu -->
                    <div class="kas-keluar-fields" style="display: none;">
                        <div class="form-group mb-3">
                            <label for="kategori_buku_besar">Buku Besar Kas Keluar</label>
                            <select name="transactions[{{ $index }}][kategori_buku_besar]" class="form-control">
                                @php
                                    $kasKeluarOptions = [
                                        'bank_sp' => 'Bank SP',
                                        'bank_induk' => 'Bank Induk',
                                        'piutang_uang' => 'Piutang Uang',
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
                                        'shu_angg' => 'SHU Anggota',
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
                                <option value="{{ $transaction->kategori_buku_besar }}">Current Kategori Buku Besar KK
                                </option>
                                @foreach ($kasKeluarOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kredit_transaction">Kredit</label>
                            <input type="text" name="transactions[{{ $index }}][kredit_transaction]"
                                class="form-control rupiah" value="{{ $transaction->kredit_transaction }}">
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
                const debetInput = parentFieldset.querySelector('input[name$="[debet_transaction]"]');
                const kreditInput = parentFieldset.querySelector('input[name$="[kredit_transaction]"]');
                const kasMasukSelect = kasMasukFields.querySelector('select[name$="[kategori_buku_besar]"]');
                const kasKeluarSelect = kasKeluarFields.querySelector('select[name$="[kategori_buku_besar]"]');

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
            const statusFields = document.querySelectorAll('select[name$="[status]"]');
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
