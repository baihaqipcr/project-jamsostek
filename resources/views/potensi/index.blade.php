<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Potensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Daftar Potensi</h1>
                <p class="text-sm text-slate-600">Catatan potensi peserta BPJS Ketenagakerjaan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('potensi.create') }}" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg font-semibold">+ Tambah Potensi</a>
                <a href="{{ route('potensi.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold">Export Excel</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg font-semibold">Keluar</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Nama Usaha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Segmen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Program</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Estimasi TK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Estimasi Iuran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($potensis as $potensi)
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $potensi->nama_usaha }}</td>
                            <td class="px-4 py-3"><span class="inline-flex rounded-full bg-blue-100 text-blue-700 px-2 py-1 text-xs font-semibold">{{ $potensi->segmen }}</span></td>
                            <td class="px-4 py-3">
                                @forelse($potensi->programPotensi as $program)
                                    <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-2 py-1 text-xs font-semibold mr-1 mb-1">{{ $program->jenis_program }}</span>
                                @empty
                                    <span class="text-slate-400 text-xs">-</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3">{{ number_format($potensi->estimasi_tk, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($potensi->estimasi_iuran, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $potensi->status_tindak_lanjut }}</td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="{{ route('potensi.show', $potensi) }}" class="text-blue-700 hover:underline">Detail</a>
                                <a href="{{ route('potensi.sp1', $potensi) }}" class="text-emerald-700 hover:underline">Cetak SP1</a>
                                <a href="{{ route('potensi.edit', $potensi) }}" class="text-amber-600 hover:underline">Edit</a>
                                <form action="{{ route('potensi.destroy', $potensi) }}" method="POST" onsubmit="return confirm('Hapus potensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-500">Belum ada data potensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
