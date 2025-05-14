<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $no_faktur }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .invoice-box {
            width: 100%;
            padding: 20px;
            border: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table th {
            background: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
        }
        .info {
            margin-top: 10px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="title">INVOICE PEMBAYARAN</div>

        <div class="info">
            <strong>No Faktur:</strong> {{ $no_faktur }}<br>
            <strong>Nama Pembeli:</strong> {{ $nama_pembeli }}<br>
            <strong>Tanggal:</strong> {{ $tanggal }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Makanan</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->nama_makanan }}</td>
                    <td>{{ $item->total_makanan }}</td>
                    <td class="text-right">{{ rupiah($item->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right">{{ rupiah($item->harga_jual * $item->total_makanan, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ rupiah($total, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <p style="margin-top: 30px;">Terima kasih atas kepercayaan Anda!</p>
    </div>
</body>
</html>
