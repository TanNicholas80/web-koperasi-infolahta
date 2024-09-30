@extends('layouts.user_type.auth')

@section('content')
    <div class="container">
        <h2>Buat Transaksi Baru</h2>
        <form action="{{ route('transaksi.store') }}" method="POST">
            @csrf

            <!-- Input for Nama Anggota -->
            <div class="form-group">
                <label for="nama_anggota">Nama Anggota</label>
                <input type="text" name="nama_anggota" id="nama_anggota" class="form-control" required>
            </div>

            <!-- Input for Jenis Transaksi -->
            <div class="form-group">
                <label for="jenis_transaksi">Jenis Transaksi</label>
                <select name="jenis_transaksi" id="jenis_transaksi" class="form-control" required>
                    <option value="">Pilih Jenis Transaksi</option>
                    <option value="debet">Debet</option>
                    <option value="kredit">Kredit</option>
                </select>
            </div>

            <div id="transaksi-container">
                <div class="transaksi-item mb-3 p-3 border rounded">
                    <div class="form-group">
                        <label for="data_barang_id">Barang</label>
                        <select name="data_barang_id[]" id="data_barang_id" class="form-control" onchange="updateTotal(this)">
                            <option value="">Pilih Barang</option>
                            @foreach($data_barang as $barang)
                                <option value="{{ $barang->id }}" data-harga="{{ $barang->harga_satuan }}">
                                    {{ $barang->nama_brg }} - Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" name="jumlah[]" id="jumlah" class="form-control" required oninput="updateTotal(this)">
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <label>Total Harga Item:</label>
                        <span class="total-harga-item font-weight-bold">Rp 0</span>
                    </div>
                    <button type="button" class="btn btn-danger remove-transaksi">Hapus</button>
                    <hr>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="add-transaksi">Tambah Transaksi</button>

            <!-- Total keseluruhan di sini -->
            <div class="form-group mt-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <label class="font-weight-bold">Total Harga Transaksi Keseluruhan: </label>
                        <span id="total-harga-keseluruhan" class="text-success font-weight-bold">Rp 0</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        // The script for handling dynamic transactions remains the same...
        document.getElementById('add-transaksi').addEventListener('click', function() {
            var transaksiContainer = document.getElementById('transaksi-container');
            var newTransaksiItem = document.createElement('div');
            newTransaksiItem.classList.add('transaksi-item', 'mb-3', 'p-3', 'border', 'rounded');
            newTransaksiItem.innerHTML = `
                <div class="form-group">
                    <label for="data_barang_id">Barang</label>
                    <select name="data_barang_id[]" class="form-control" onchange="updateTotal(this)">
                        <option value="">Pilih Barang</option>
                        @foreach($data_barang as $barang)
                            <option value="{{ $barang->id }}" data-harga="{{ $barang->harga_satuan }}">
                                {{ $barang->nama_brg }} - Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control" required oninput="updateTotal(this)">
                </div>
                <div class="form-group d-flex justify-content-between align-items-center">
                    <label>Total Harga Item:</label>
                    <span class="total-harga-item font-weight-bold">Rp 0</span>
                </div>
                <button type="button" class="btn btn-danger remove-transaksi">Hapus</button>
                <hr>
            `;
            transaksiContainer.appendChild(newTransaksiItem);
            addRemoveFunctionality();
        });

        function addRemoveFunctionality() {
            var removeButtons = document.querySelectorAll('.remove-transaksi');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    this.closest('.transaksi-item').remove();
                    updateTotal();
                });
            });
        }

        addRemoveFunctionality();

        function updateTotal(element) {
            var transaksiItems = document.querySelectorAll('.transaksi-item');
            var totalKeseluruhan = 0;

            transaksiItems.forEach(function(item) {
                var barangSelect = item.querySelector('select[name="data_barang_id[]"]');
                var jumlahInput = item.querySelector('input[name="jumlah[]"]');
                var totalHargaItemSpan = item.querySelector('.total-harga-item');

                // Ambil harga barang yang dipilih
                var selectedOption = barangSelect.options[barangSelect.selectedIndex];
                var hargaBarang = selectedOption.getAttribute('data-harga') ? parseFloat(selectedOption.getAttribute('data-harga')) : 0;

                // Ambil jumlah
                var jumlah = jumlahInput.value ? parseInt(jumlahInput.value) : 0;

                // Hitung total harga item
                var totalHargaItem = hargaBarang * jumlah;

                // Tampilkan total harga per item
                totalHargaItemSpan.textContent = 'Rp ' + totalHargaItem.toLocaleString('id-ID');

                // Tambahkan ke total keseluruhan
                totalKeseluruhan += totalHargaItem;
            });

            // Tampilkan total keseluruhan
            document.getElementById('total-harga-keseluruhan').textContent = 'Rp ' + totalKeseluruhan.toLocaleString('id-ID');
        }
    </script>
@endsection
