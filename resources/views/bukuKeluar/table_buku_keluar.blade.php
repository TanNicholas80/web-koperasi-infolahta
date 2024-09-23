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
            'kas' => 'Kas',
            ];
            @endphp
            @foreach ($kasKeluarOptions as $key => $label)
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
            @foreach ($kasKeluarOptions as $key => $label)
            <td class="text-center">
                @php
                // Find the corresponding buku record
                $buku = $bukuKeluar->where('id_main_cash_trans', $kas->id)->first();
                // Get the value for the current key or default to 'N/A'
                $value = $buku->$key ?? 'N/A';
                @endphp
                <p class="text-xs font-weight-bold mb-0 kredit-transaction">{{ $value }}</p>
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
            'inventaris' => 'total_inventaris',
            'penyertaan_puskop' => 'total_penyertaan_puskop',
            'hutang_toko' => 'total_hutang_toko',
            'dana_pengurus' => 'total_dana_pengurus',
            'dana_karyawan' => 'total_dana_karyawan',
            'dana_sosial' => 'total_dana_sosial',
            'dana_dik' => 'total_dana_dik',
            'dana_pdk' => 'total_dana_pdk',
            'simp_pokok' => 'total_simp_pokok',
            'simp_wajib' => 'total_simp_wajib',
            'simp_khusus' => 'total_simp_khusus',
            'shu_angg' => 'total_shu_angg',
            'pembelian_toko' => 'total_pembelian_toko',
            'biaya_insentif' => 'total_biaya_insentif',
            'biaya_atk' => 'total_biaya_atk',
            'biaya_transport' => 'total_biaya_transport',
            'biaya_pembinaan' => 'total_biaya_pembinaan',
            'biaya_pembungkus' => 'total_biaya_pembungkus',
            'biaya_rat' => 'total_biaya_rat',
            'biaya_thr' => 'total_biaya_thr',
            'biaya_pajak' => 'total_biaya_pajak',
            'biaya_admin' => 'total_biaya_admin',
            'biaya_training' => 'total_biaya_training',
            'inv_usipa' => 'total_inv_usipa',
            'lain_lain' => 'total_lain_lain',
            ];
            @endphp

            <!-- Total untuk masing-masing kolom kas keluar -->
            @foreach ($kasKeluarOptions as $key => $label)
            <td class="text-center text-xs font-weight-bold kredit-transaction">
                @php
                $totalColumn = $columnMapping[$key];
                @endphp
                {{ $totals->$totalColumn ?? '0' }}
            </td>
            @endforeach
        </tr>
    </tbody>
</table>