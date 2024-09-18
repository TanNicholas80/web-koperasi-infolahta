<table class="table align-items-center mb-0">
    <thead>
        <tr>
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
            <td class="text-center">
                <p class="text-xs font-weight-bold mb-0 kredit-transaction">
                    {{ $kas->kredit_transaction }}
                </p>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>