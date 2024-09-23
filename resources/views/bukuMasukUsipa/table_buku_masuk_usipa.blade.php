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
            @foreach ($kasMasukOptions as $key => $label)
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                {{ $label }}
            </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($kasUsipa as $kas)
        <tr>
            <td class="ps-4">
                <p class="text-xs font-weight-bold mb-0">
                    {{ $loop->iteration }}
                </p>
            </td>
            <td class="ps-4">
                <p class="text-xs font-weight-bold mb-0">
                    {{ $kas->trans_date_usipa }}
                </p>
            </td>
            <td>
                <p class="text-xs font-weight-bold mb-0">{{ $kas->keterangan_usipa }}
                </p>
            </td>
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0">{{ $kas->status_usipa }}</p>
            </td>
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0">{{ $kas->periode_usipa }}</p>
            </td>
            @foreach ($kasMasukOptions as $key => $label)
            <td class="text-center">
                @php
                // Find the corresponding buku record
                $buku = $bukuMasuk->where('id_kas_usipa_trans', $kas->id)->first();
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
            'piutang_brg_toko' => 'total_piutang_brg_toko',
            'dana_sosial' => 'total_dana_sosial',
            'dana_dik' => 'total_dana_dik',
            'dana_pdk' => 'total_dana_pdk',
            'resiko_kredit' => 'total_resiko_kredit',
            'simp_pokok' => 'total_simp_pokok',
            'simp_wajib' => 'total_simp_wajib',
            'simp_khusus' => 'total_simp_khusus',
            'penjualan_tunai' => 'total_penjualan_tunai',
            'jasa_sp' => 'total_jasa_sp',
            'provinsi' => 'total_provinsi',
            'shu_puskop' => 'total_shu_puskop',
            'modal_disetor' => 'total_modal_disetor',
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