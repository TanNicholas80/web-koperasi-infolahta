<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                No
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Tanggal
            </th>
            <th
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Uraian
            </th>
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status
            </th>
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Periode
            </th>
            @php
            $kasMasukOptions = [
            'kas' => 'Kas',
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
            'sipanan_tunai' => 'Penjualan Tunai',
            'jasa_sp' => 'Jasa SP',
            'provinsi' => 'Provinsi',
            'shu_puskop' => 'SHU Puskop',
            'inv_usipa' => 'Investasi USIPA',
            'lain_lain' => 'Lain-Lain',
            ];
            @endphp
            @foreach ($kasMasukOptions as $key => $label)
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                {{ $label }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($kasInduk as $kas)
        <tr>
            <td class="ps-4">
                <p class="text-xs font-weight-bold mb-0">
                    {{ $loop->iteration }}
                </p>
            </td>
            <td class="ps-4">
                <p class="text-xs font-weight-bold mb-0">
                    {{ $kas->trans_date }}
                </p>
            </td>
            <td>
                <p class="text-xs font-weight-bold mb-0">{{ $kas->keterangan }}
                </p>
            </td>
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0">{{ $kas->status }}</p>
            </td>
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0">{{ $kas->periode }}</p>
            </td>
            @foreach ($kasMasukOptions as $key => $label)
            <td class="text-center">
                @php
                // Find the corresponding buku record
                $buku = $bukuMasuk
                ->where('id_main_cash_trans', $kas->id)
                ->first();
                // Get the value for the current key or default to 'N/A'
                $value = $buku->$key ?? 'N/A';
                @endphp
                <p class="text-xs font-weight-bold mb-0 debet-transaction">{{ $value }}</p>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>

<!-- 2 -->
<!-- <style>
    /* CSS untuk mengatur lebar kolom dan mengizinkan teks untuk dibungkus */
    .table td, .table th {
        word-wrap: break-word;
        white-space: normal;
    }

    /* Tentukan ukuran lebar kolom agar lebih proporsional */
    .table th, .table td {
        width: auto;
        max-width: 150px; /* Atur lebar maksimal, bisa diubah sesuai kebutuhan */
    }
</style>

<table class="table table-bordered table-hover align-items-center mb-0">
    <thead class="thead-light">
        <tr>
            <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                Date
            </th>
            <th class="text-uppercase text-secondary text-xs font-weight-bolder ps-2 text-center">
                Keterangan
            </th>
            <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                Status
            </th>
            <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                Periode
            </th>
            @foreach ($kasMasukOptions as $key => $label)
                <th class="text-uppercase text-secondary text-xs font-weight-bolder text-center">
                    {{ $label }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($kasInduk as $kas)
            <tr>
                <td class="text-center align-middle">
                    <span class="text-xs font-weight-bold">{{ $kas->trans_date }}</span>
                </td>
                <td class="text-center align-middle">
                    <span class="text-xs font-weight-bold">{{ $kas->keterangan }}</span>
                </td>
                <td class="text-center align-middle">
                    <span class="text-xs font-weight-bold">{{ $kas->status }}</span>
                </td>
                <td class="text-center align-middle">
                    <span class="text-xs font-weight-bold">{{ $kas->periode }}</span>
                </td>
                @foreach ($kasMasukOptions as $key => $label)
                    <td class="text-center align-middle">
                        @php
                            $buku = $bukuMasuk->where('id_main_cash_trans', $kas->id)->first();
                            $value = $buku->$key ?? 'N/A';
                        @endphp
                        <span class="text-xs font-weight-bold">{{ $value }}</span>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table> -->