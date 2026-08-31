<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Pemberitahuan 1 (SP1)</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
        .kop { text-align: center; margin-bottom: 16px; }
        .judul { font-weight: bold; font-size: 14px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .table td { padding: 6px; vertical-align: top; }
        .tanda-tangan { margin-top: 36px; display: flex; justify-content: flex-end; }
    </style>
</head>
<body>
    <div class="kop">
        <div class="judul">KEMENTERIAN KESEJAHTERAAN SOSIAL</div>
        <div>Direktorat Jenderal BPJS Ketenagakerjaan</div>
        <hr />
        <h3>Surat Pemberitahuan 1 (SP1)</h3>
    </div>

    <table class="table">
        <tr>
            <td style="width:30%">Nomor</td>
            <td>: SP1/{{ $potensi->id }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $potensi->tanggal_cetak_sp1 ? $potensi->tanggal_cetak_sp1->format('Y-m-d') : now()->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Nama Usaha</td>
            <td>: {{ $potensi->nama_usaha }}</td>
        </tr>
        <tr>
            <td>Segmen</td>
            <td>: {{ $potensi->segmen }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: {{ $potensi->alamat }}</td>
        </tr>
        <tr>
            <td>Estimasi Tenaga Kerja</td>
            <td>: {{ number_format($potensi->estimasi_tk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Estimasi Iuran</td>
            <td>: Rp {{ number_format($potensi->estimasi_iuran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Program Potensi</td>
            <td>:
                @if($potensi->programPotensi->isNotEmpty())
                    @foreach($potensi->programPotensi as $program)
                        {{ $program->jenis_program }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td>: {{ $potensi->catatan ?: '-' }}</td>
        </tr>
    </table>

    <div class="tanda-tangan">
        <div style="text-align:center">
            <div>Petugas,</div>
            <br /><br />
            <div style="font-weight:bold">{{ $potensi->user->name ?? '-' }}</div>
        </div>
    </div>
</body>
</html>
