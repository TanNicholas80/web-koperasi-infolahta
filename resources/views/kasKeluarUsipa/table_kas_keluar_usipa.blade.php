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
            <th
                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                Kredit
            </th>
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
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0 kredit-transaction">
                    {{ $kas->kredit_transaction_usipa }}
                </p>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-center text-sm font-weight-bold">Total</td>
            <td class="text-center text-xs font-weight-bold kredit-transaction">
                {{ $totalKredit }}
            </td>
        </tr>
    </tbody>
</table>