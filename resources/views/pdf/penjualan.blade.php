<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; } /* 👈 Tambahkan ini */
    </style>
</head>
<body>
    <h2>Daftar Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>No Faktur</th>
                <th>Nama Pembeli</th>
                <th>Status</th>
                <th>Tagihan</th>
                <th>Tgl</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $p)
            <tr>
                <td>{{ $p->no_faktur }}</td>
                <td>{{ optional($p->pembeli)->nama_pembeli }}</td>
                <td>{{ $p->status }}</td>
                <td class="text-right">{{ rupiah($p->tagihan) }}</td>
                <td>{{ $p->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
