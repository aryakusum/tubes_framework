<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border border-gray-300 px-4 py-2">No Faktur</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Bayar</th>
            <th class="border border-gray-300 px-4 py-2">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembayarans as $pembayaran)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $pembayaran->no_faktur }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $pembayaran->tgl }}</td>
                <td class="border border-gray-300 px-4 py-2">Rp{{ number_format($pembayaran->tagihan, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
