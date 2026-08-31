<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Potensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map { height: 320px; width: 100%; border-radius: 0.75rem; overflow: hidden; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-blue-800">Edit Potensi</h1>
                <p class="text-sm text-slate-600">Perbarui data potensi</p>
            </div>
            <a href="{{ route('potensi.show', $potensi) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-semibold">Kembali</a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('potensi.update', $potensi) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="tanggal_input" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Input</label>
                    <input type="date" id="tanggal_input" name="tanggal_input" value="{{ old('tanggal_input', $potensi->tanggal_input?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="nama_usaha" class="block text-sm font-medium text-slate-700 mb-1">Nama Usaha</label>
                    <input type="text" id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $potensi->nama_usaha) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="segmen" class="block text-sm font-medium text-slate-700 mb-1">Segmen</label>
                    <select id="segmen" name="segmen" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="PU" {{ old('segmen', $potensi->segmen) == 'PU' ? 'selected' : '' }}>PU</option>
                        <option value="BPU" {{ old('segmen', $potensi->segmen) == 'BPU' ? 'selected' : '' }}>BPU</option>
                        <option value="Jakon" {{ old('segmen', $potensi->segmen) == 'Jakon' ? 'selected' : '' }}>Jakon</option>
                    </select>
                </div>
                <div>
                    <label for="estimasi_tk" class="block text-sm font-medium text-slate-700 mb-1">Estimasi Tenaga Kerja</label>
                    <input type="number" id="estimasi_tk" name="estimasi_tk" value="{{ old('estimasi_tk', $potensi->estimasi_tk) }}" min="1" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="estimasi_upah" class="block text-sm font-medium text-slate-700 mb-1">Estimasi Upah</label>
                    <input type="number" id="estimasi_upah" name="estimasi_upah" value="{{ old('estimasi_upah', $potensi->estimasi_upah) }}" min="0" step="0.01" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="estimasi_iuran" class="block text-sm font-medium text-slate-700 mb-1">Estimasi Iuran</label>
                    <input type="number" id="estimasi_iuran" name="estimasi_iuran" value="{{ old('estimasi_iuran', $potensi->estimasi_iuran) }}" min="0" step="0.01" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label for="uraian" class="block text-sm font-medium text-slate-700 mb-1">Uraian</label>
                    <textarea id="uraian" name="uraian" rows="4" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>{{ old('uraian', $potensi->uraian) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>{{ old('alamat', $potensi->alamat) }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Lokasi Potensi</label>
                    <div id="map" class="border border-slate-300"></div>
                    <p class="mt-2 text-xs text-slate-500">Klik di peta untuk memperbarui koordinat.</p>
                </div>
                <div>
                    <label for="latitude" class="block text-sm font-medium text-slate-700 mb-1">Latitude</label>
                    <input type="number" step="0.0000001" id="latitude" name="latitude" value="{{ old('latitude', $potensi->latitude) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" readonly>
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-slate-700 mb-1">Longitude</label>
                    <input type="number" step="0.0000001" id="longitude" name="longitude" value="{{ old('longitude', $potensi->longitude) }}" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500" readonly>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Program Potensi</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 rounded-lg border border-slate-300 p-3">
                        @foreach(['JKK', 'JKM', 'JHT', 'JP'] as $program)
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" name="programs[]" value="{{ $program }}" {{ in_array($program, old('programs', $potensi->programPotensi->pluck('jenis_program')->toArray()), true) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                {{ $program }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="status_tindak_lanjut" class="block text-sm font-medium text-slate-700 mb-1">Status Tindak Lanjut</label>
                    <select id="status_tindak_lanjut" name="status_tindak_lanjut" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="Belum dihubungi" {{ old('status_tindak_lanjut', $potensi->status_tindak_lanjut) == 'Belum dihubungi' ? 'selected' : '' }}>Belum dihubungi</option>
                        <option value="Sudah dihubungi" {{ old('status_tindak_lanjut', $potensi->status_tindak_lanjut) == 'Sudah dihubungi' ? 'selected' : '' }}>Sudah dihubungi</option>
                        <option value="Jadi peserta" {{ old('status_tindak_lanjut', $potensi->status_tindak_lanjut) == 'Jadi peserta' ? 'selected' : '' }}>Jadi peserta</option>
                        <option value="Ditolak" {{ old('status_tindak_lanjut', $potensi->status_tindak_lanjut) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="catatan" class="block text-sm font-medium text-slate-700 mb-1">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="3" class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">{{ old('catatan', $potensi->catatan) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg font-semibold">Perbarui Potensi</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        const defaultLat = -6.2088;
        const defaultLng = 106.8456;
        const map = L.map('map').setView([defaultLat, defaultLng], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const marker = L.marker([defaultLat, defaultLng]).addTo(map);

        const savedLat = latInput.value;
        const savedLng = lngInput.value;

        if (savedLat && savedLng) {
            const parsedLat = parseFloat(savedLat);
            const parsedLng = parseFloat(savedLng);
            if (!Number.isNaN(parsedLat) && !Number.isNaN(parsedLng)) {
                map.setView([parsedLat, parsedLng], 15);
                marker.setLatLng([parsedLat, parsedLng]);
            }
        }

        map.on('click', function (event) {
            const { lat, lng } = event.latlng;
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
            marker.setLatLng([lat, lng]);
        });
    </script>
</body>
</html>
