@extends('layouts.user_type.auth')

@section('content')
    {{-- <h1>Tambah Barang</h1>

    <form action="{{ route('data_barang.store') }}" method="POST">
        @csrf
        <label for="kode_brg">Kode Barang:</label>
        <input type="text" name="kode_brg" required>

        <label for="nama_brg">Nama Barang:</label>
        <input type="text" name="nama_brg" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" required>

        <label for="harga_satuan">Harga Satuan:</label>
        <input type="text" name="harga_satuan" required>

        <button type="submit">Simpan</button>
    </form> --}}


    <div class="container">
        <h1>Tambah Barang</h1>
        
        <form action="{{ route('data_barang.store') }}" method="POST">
            @csrf
            <div id="transactions-container">
                <div class="form-group mb-3">
                    <label for="kode_brg">Kode Barang</label>
                    <input type="text" name="kode_brg" class="form-control" required>
                </div>   
                <div class="form-group mb-3">
                    <label for="nama_brg">Nama Barang</label>
                    <input type="text" name="nama_brg" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="harga_satuan">Harga Satuan</label>
                    <input type="text" name="harga_satuan" class="form-control rupiah" required>
                </div>
                <div class="form-group mb-3">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
            </div>
        
            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
        </form>
    </div>
    
    
<!-- JavaScript untuk memformat input menjadi Rp -->
<script>
    function formatRupiah(angka, prefix) {
        var numberString = angka.replace(/[^,\d]/g, '').toString(),
            split = numberString.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // Menambahkan titik jika angka sudah menjadi ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
    }

    // Menghapus format rupiah untuk dikirim ke server
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
            // Menghapus format saat data dikirim
            input.value = cleanRupiah(this.value);
        });
    });

    // Pastikan format angka murni dikirim saat submit form
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        rupiahInputs.forEach(input => {
            input.value = cleanRupiah(input.value);
        });
    });
</script>
@endsection
