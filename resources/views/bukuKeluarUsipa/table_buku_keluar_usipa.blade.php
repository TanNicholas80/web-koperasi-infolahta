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
                @foreach ($kasKeluarOptions as $key => $label)
                    <td class="text-center">
                        @php
                            // Find the corresponding buku record
                            $buku = $bukuKeluar->where('id_kas_usipa_trans', $kas->id)->first();
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
                    'bank_sp' => 'total_bank_sp',
                    'bank_induk' => 'total_bank_induk',
                    'simpanan_pinjaman' => 'total_simpanan_pinjaman',
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
                    'modal_disetor' => 'total_modal_disetor',
                    'lain_lain' => 'total_lain_lain',
                    'kas' => 'total_kas',
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
