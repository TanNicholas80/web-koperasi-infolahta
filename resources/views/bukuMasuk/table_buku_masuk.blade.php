<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                No
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Tanggal
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Uraian
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status
            </th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
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
            'penjualan_tunai' => 'Penjualan Tunai',
            'jasa_sp' => 'Jasa SP',
            'provisi' => 'Provisi',
            'shu_puskop' => 'SHU Puskop',
            'inv_usipa' => 'Investasi USIPA',
            'lain_lain' => 'Lain-Lain',
            ];
            @endphp
            @foreach ($kasMasukOptions as $key => $label)
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
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
                $buku = $bukuMasuk->where('id_main_cash_trans', $kas->id)->first();
                // Get the value for the current key or default to 'N/A'
                $value = $buku->$key ?? 'N/A';
                @endphp
                <p class="text-xs font-weight-bold mb-0 debet-transaction">{{ $value }}</p>
            </td>
            @endforeach
        </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-center text-sm font-weight-bold">Total</td>

            @php
            $columnMapping = [
            'kas' => 'total_kas',
            'bank_sp' => 'total_bank_sp',
            'bank_induk' => 'total_bank_induk',
            'piutang_uang' => 'total_piutang_uang',
            'piutang_barang_toko' => 'total_piutang_barang_toko',
            'dana_sosial' => 'total_dana_sosial',
            'dana_dik' => 'total_dana_dik',
            'dana_pdk' => 'total_dana_pdk',
            'resiko_kredit' => 'total_resiko_kredit',
            'simpanan_pokok' => 'total_simpanan_pokok',
            'sipanan_wajib' => 'total_sipanan_wajib',
            'sipanan_khusus' => 'total_sipanan_khusus',
            'penjualan_tunai' => 'total_penjualan_tunai',
            'jasa_sp' => 'total_jasa_sp',
            'provisi' => 'total_provisi',
            'shu_puskop' => 'total_shu_puskop',
            'inv_usipa' => 'total_inv_usipa',
            'lain_lain' => 'total_lain_lain',
            ];
            @endphp

            <!-- Total untuk masing-masing kolom kas keluar -->
            @foreach ($kasMasukOptions as $key => $label)
            <td class="text-center text-xs font-weight-bold debet-transaction">
                @php
                $totalColumn = $columnMapping[$key];
                @endphp
                {{ $totals->$totalColumn ?? '0' }}
            </td>
            @endforeach
        </tr>
    </tbody>
</table>