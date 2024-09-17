<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th bgcolor="#FFEB00" style="border:1px solid black;" class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Date
            </th>
            <th bgcolor="#FFEB00" style="border:1px solid black;"
                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                Keterangan
            </th>
            <th bgcolor="#FFEB00" style="border:1px solid black;"
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Status
            </th>
            <th bgcolor="#FFEB00" style="border:1px solid black;"
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Periode
            </th>
            @php
            $kasKeluarOptions = [
            'kas' => 'Kas',
            'bank_sp' => 'Bank SP',
            'bank_induk' => 'Bank Induk',
            'simpan_pinjam' => 'Simpan Pinjam',
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
            @foreach ($kasKeluarOptions as $key => $label)
            <th bgcolor="#FFEB00" style="border:1px solid black;"
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
                $buku = $bukuKeluar
                ->where('id_main_cash_trans', $kas->id)
                ->first();
                // Get the value for the current key or default to 'N/A'
                $value = $buku->$key ?? 'N/A';
                @endphp
                <p class="text-xs font-weight-bold mb-0 kredit-transaction">{{ $value }}</p>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>