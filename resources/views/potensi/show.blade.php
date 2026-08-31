<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Potensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { height: 260px; width: 100%; border-radius: 0.75rem; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Detail Potensi</h1>
                <p class="text-sm text-slate-600">Informasi lengkap usaha dan status tindak lanjut</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('potensi.edit', $potensi) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-semibold">Edit</a>
                <a href="{{ route('potensi.sp1', $potensi) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold">Cetak SP1</a>
                <a href="{{ route('potensi.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-semibold">Kembali</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs uppercase text-slate-500">Nama Usaha</p>
                    <p class="text-lg font-semibold">{{ $potensi->nama_usaha }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Segmen</p>
                    <p class="text-lg font-semibold">{{ $potensi->segmen }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Tanggal Input</p>
                    <p>{{ $potensi->tanggal_input?->format('d-m-Y') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Petugas</p>
                    <p>{{ $potensi->user?->name ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase text-slate-500">Uraian</p>
                    <p class="whitespace-pre-line">{{ $potensi->uraian }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase text-slate-500">Alamat</p>
                    <p class="whitespace-pre-line">{{ $potensi->alamat }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Latitude</p>
                    <p>{{ $potensi->latitude ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Longitude</p>
                    <p>{{ $potensi->longitude ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase text-slate-500 mb-2">Peta Lokasi</p>
                    <div id="map" class="border border-slate-300"></div>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Estimasi Tenaga Kerja</p>
                    <p>{{ number_format($potensi->estimasi_tk, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Estimasi Upah</p>
                    <p>Rp {{ number_format($potensi->estimasi_upah, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Estimasi Iuran</p>
                    <p>Rp {{ number_format($potensi->estimasi_iuran, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-500">Status Tindak Lanjut</p>
                    <p>{{ $potensi->status_tindak_lanjut }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase text-slate-500">Program Potensi</p>
                    @forelse($potensi->programPotensi as $program)
                        <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-2 py-1 text-xs font-semibold mr-1">{{ $program->jenis_program }}</span>
                    @empty
                        <span class="text-slate-400">-</span>
                    @endforelse
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs uppercase text-slate-500">Catatan</p>
                    <p class="whitespace-pre-line">{{ $potensi->catatan ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;
        const rawLat = Number("{{ $potensi->latitude ?? defaultLat }}");
        const rawLng = Number("{{ $potensi->longitude ?? defaultLng }}");
        const lat = Number.isFinite(rawLat) ? rawLat : defaultLat;
        const lng = Number.isFinite(rawLng) ? rawLng : defaultLng;

        const map = L.map('map').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map);
    </script>
</body>
</html>
