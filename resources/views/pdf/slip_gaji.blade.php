<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table td,
        .table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background: #f2f2f2;
        }

        .total {
            color: #1a73e8;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">Slip Gaji Pegawai</div>
    <p><strong>Nomor Penggajian:</strong> {{ $penggajian->nomor_penggajian }}<br>
        <strong>Tanggal:</strong> {{ $penggajian->tanggal_penggajian->format('d-m-Y') }}
    </p>
    <p><strong>Pegawai:</strong> {{ $detail->pegawai->nama_pegawai }}</p>
    <table class="table">
        <tr>
            <th>Total Hadir</th>
            <td>{{ $detail->total_hadir }}</td>
        </tr>
        <tr>
            <th>Gaji Pokok</th>
            <td>Rp{{ number_format($detail->gaji_pokok,0,',','.') }}</td>
        </tr>
        <tr>
            <th>Tunjangan</th>
            <td>Rp{{ number_format($detail->tunjangan,0,',','.') }}</td>
        </tr>
        <tr>
            <th>Potongan</th>
            <td>Rp{{ number_format($detail->potongan,0,',','.') }}</td>
        </tr>
        <tr>
            <th class="total">Total Gaji</th>
            <td class="total">Rp{{ number_format($detail->total_gaji,0,',','.') }}</td>
        </tr>
    </table>
    @if($penggajian->keterangan)
    <p><strong>Keterangan:</strong> {{ $penggajian->keterangan }}</p>
    @endif
</body>

</html>