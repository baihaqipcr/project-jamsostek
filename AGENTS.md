# AGENTS.md — Panduan Agent untuk Aplikasi Pencatatan Potensi BPJS Ketenagakerjaan

Dokumen ini adalah instruksi kerja untuk AI coding agent (Copilot Agent Mode, Cline, Claude Code, atau agent sejenis di VS Code). Ikuti aturan dan tahapan di bawah ini secara berurutan. **Jangan lompat ke tahap berikutnya sebelum tahap sebelumnya selesai dan sudah dikonfirmasi bekerja.**

## Konteks proyek

Aplikasi web untuk mencatat potensi toko/perusahaan yang berpeluang menjadi peserta BPJS Ketenagakerjaan, dipakai oleh petugas bidang KSI (Kepesertaan, Korporasi, dan Institusi).

**Tech stack:**
- Laravel 13 (PHP 8.3+)
- Laravel Breeze (stack Blade) untuk autentikasi
- MySQL (lewat Laragon)
- Tailwind CSS (bawaan Breeze)
- Leaflet.js atau Google Maps JS API untuk tagging lokasi
- Maatwebsite/Excel untuk export file excel
- DomPDF atau barryvdh/laravel-dompdf untuk cetak surat SP1

**Segmen peserta:** PU (Penerima Upah), BPU (Bukan Penerima Upah), Jakon (Jasa Konstruksi)

**Tema warna:** biru (`#185FA5` / kepercayaan, elemen utama) + hijau (`#3B6D11` / potensi & pertumbuhan, aksen)

## Struktur data (jadikan acuan migration & model)

**users** — bawaan Breeze, dipakai sebagai tabel petugas. Tambahkan kolom `nip` dan `cabang` lewat migration tambahan (jangan bikin tabel petugas terpisah, cukup extend tabel users).

**potensi**
- id
- user_id (FK ke users)
- tanggal_input
- nama_usaha
- segmen (enum: PU, BPU, Jakon)
- uraian
- alamat
- latitude, longitude (decimal)
- estimasi_tk (integer)
- estimasi_upah (decimal)
- estimasi_iuran (decimal)
- status_sp1 (boolean, default false)
- tanggal_cetak_sp1 (nullable date)
- status_tindak_lanjut (string, default "Belum dihubungi")
- catatan (nullable text)
- timestamps

**program_potensi**
- id
- potensi_id (FK ke potensi)
- jenis_program (string: JKK/JKM/JHT/JP)

## Aturan kerja untuk agent

1. **Kerjakan satu tahap penuh sebelum lanjut ke tahap berikutnya.** Setelah selesai satu tahap, jelaskan singkat apa yang sudah dibuat dan tunggu konfirmasi sebelum lanjut.
2. **Jangan install package baru di luar yang disebutkan** di tahap terkait tanpa menyebutkan alasannya terlebih dahulu.
3. **Ikuti konvensi Laravel standar** (Eloquent, Form Request untuk validasi, Resource Controller, Blade Component untuk elemen UI berulang).
4. **Semua teks di UI pakai Bahasa Indonesia**, konsisten dengan istilah yang sudah dipakai di atas (jangan campur "storage" jadi "penyimpanan" lalu "database" di tempat lain — sinkronkan istilah).
5. **Validasi input di server-side wajib**, terutama untuk field numerik (estimasi TK, iuran) dan koordinat.
6. **Jangan hardcode kredensial atau API key** — selalu taruh di file `.env` dan akses lewat `config()` atau `env()`.
7. Setelah menyelesaikan tahap yang melibatkan migration, jalankan `php artisan migrate` dan laporkan hasilnya.

## Tahapan pembangunan

### Tahap 1 — Fondasi database
- Buat migration untuk menambah kolom `nip` dan `cabang` ke tabel `users`.
- Buat migration + model `Potensi` dan `ProgramPotensi` sesuai struktur di atas, termasuk relasi `hasMany`/`belongsTo`.
- Jalankan migration, pastikan tabel terbentuk di database.

### Tahap 2 — CRUD dasar potensi (tanpa maps dulu)
- Buat `PotensiController` (resource controller: index, create, store, show, edit, update, destroy).
- Buat view Blade: form input potensi (tanpa peta dulu, alamat masih input teks manual) dan halaman daftar potensi (tabel sederhana).
- Terapkan Form Request `StorePotensiRequest` untuk validasi.
- Pastikan CRUD berjalan penuh sebelum lanjut.

### Tahap 3 — Integrasi peta untuk tagging lokasi
- Tambahkan Leaflet.js (lebih ringan dan gratis dibanding Google Maps API untuk kebutuhan ini) ke form input potensi.
- Saat user klik titik di peta, isi otomatis field `latitude` dan `longitude` yang hidden di form.
- Tampilkan peta kecil (read-only) di halaman detail potensi menunjukkan titik lokasi yang sudah disimpan.

### Tahap 4 — Program potensi (relasi many)
- Tambahkan multi-select checkbox (JKK/JKM/JHT/JP) di form input potensi.
- Simpan ke tabel `program_potensi` saat store/update (hapus dulu yang lama, insert ulang, atau pakai sync-style manual karena bukan many-to-many bawaan).
- Tampilkan daftar program di halaman detail dan daftar potensi.

### Tahap 5 — Cetak surat SP1 (PDF)
- Install `barryvdh/laravel-dompdf`.
- Buat template Blade khusus untuk surat SP1 (kop surat, data potensi, tanda tangan).
- Buat route + controller method untuk generate dan download PDF dari satu record potensi.
- Setelah PDF berhasil dicetak, update `status_sp1` jadi true dan isi `tanggal_cetak_sp1`.

### Tahap 6 — Export excel
- Install `maatwebsite/excel`.
- Buat class Export yang mengambil semua data potensi (join dengan program_potensi, gabungkan jadi satu kolom string).
- Buat tombol "Export Excel" di halaman daftar potensi yang men-trigger download.

### Tahap 7 — Status & tindak lanjut (fitur monitoring)
- Tambahkan dropdown untuk update `status_tindak_lanjut` langsung dari halaman daftar/detail (belum dihubungi / sudah dihubungi / jadi peserta / ditolak).
- Tambahkan filter di halaman daftar potensi berdasarkan segmen dan status tindak lanjut.

### Tahap 8 — Polish & tema visual
- Terapkan tema warna biru + hijau ke layout utama (`resources/views/layouts/app.blade.php`) dan komponen Breeze.
- Rapikan responsive layout untuk penggunaan di lapangan (mobile-friendly, karena petugas kemungkinan akses lewat HP saat survei).
- Review ulang semua validasi dan pesan error dalam Bahasa Indonesia.

### Tahap 9 — Testing menyeluruh
- Uji seluruh alur: login → input potensi → tagging lokasi → simpan → cetak SP1 → export excel.
- Cek edge case: input kosong, koordinat tidak dipilih, export saat data kosong.
