@component('mail::message')
# Slip Gaji Pegawai

**Nomor Penggajian:** {{ $penggajian->nomor_penggajian }}
**Tanggal:** {{ $penggajian->tanggal_penggajian->format('d-m-Y') }}

---

@component('mail::panel')
<table width="100%" style="font-size: 15px;">
    <tr>
        <td><strong>Pegawai</strong></td>
        <td align="right">{{ $detail->pegawai->nama_pegawai }}</td>
    </tr>
    <tr>
        <td><strong>Total Hadir</strong></td>
        <td align="right">{{ $detail->total_hadir }}</td>
    </tr>
    <tr>
        <td><strong>Gaji Pokok</strong></td>
        <td align="right">Rp{{ number_format($detail->gaji_pokok,0,',','.') }}</td>
    </tr>
    <tr>
        <td><strong>Tunjangan</strong></td>
        <td align="right">Rp{{ number_format($detail->tunjangan,0,',','.') }}</td>
    </tr>
    <tr>
        <td><strong>Potongan</strong></td>
        <td align="right">Rp{{ number_format($detail->potongan,0,',','.') }}</td>
    </tr>
    <tr>
        <td><strong style="color:#1a73e8;">Total Gaji</strong></td>
        <td align="right"><strong style="color:#1a73e8;">Rp{{ number_format($detail->total_gaji,0,',','.') }}</strong></td>
    </tr>
</table>
@endcomponent

@if($penggajian->keterangan)
> <strong>Keterangan:</strong> {{ $penggajian->keterangan }}
@endif


Terima kasih,<br>