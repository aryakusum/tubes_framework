<x-mail::message>
    # Konfirmasi Presensi {{ ucfirst($type) }}

    Halo, {{ $pegawai->nama_pegawai }} ({{ $pegawai->user->email }})

    Berikut detail presensi {{ $type }} Anda:

    - **Tanggal:** {{ $presensi->tanggal }}
    - **Jam Masuk:** {{ $presensi->jam_masuk ?? '-' }}
    - **Jam Keluar:** {{ $presensi->jam_keluar ?? '-' }}
    - **Status:** {{ $presensi->status ?? '-' }}
    - **Keterangan:** {{ $presensi->keterangan ?? '-' }}

    Terima kasih telah melakukan presensi.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>