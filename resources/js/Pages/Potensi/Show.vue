<script setup>
import LocationMap from '@/Components/LocationMap.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    potensi: {
        type: Object,
        required: true,
    },
});

const formatNumber = (value) => Number(value || 0).toLocaleString('id-ID');
const formatDate = (value) => (value ? String(value).slice(0, 10).split('-').reverse().join('-') : '-');
</script>

<template>
    <Head :title="`Detail ${potensi.nama_usaha}`" />

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-navy-900">Detail Potensi</h1>
            <p class="mt-1 text-sm text-slate-600">Informasi lengkap usaha dan status tindak lanjut.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <Link :href="route('potensi.edit', potensi.id)" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">Ubah</Link>
            <a :href="route('potensi.sp1', potensi.id)" class="rounded-lg bg-brand-blue px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">Cetak SP1</a>
            <Link :href="route('potensi.index')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">Kembali</Link>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Usaha</p>
                <p class="mt-1 text-lg font-semibold text-navy-900">{{ potensi.nama_usaha }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Segmen</p>
                <p class="mt-1 text-lg font-semibold">{{ potensi.segmen }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal Input</p>
                <p class="mt-1">{{ formatDate(potensi.tanggal_input) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Petugas</p>
                <p class="mt-1">{{ potensi.user?.name ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Uraian</p>
                <p class="mt-1 whitespace-pre-line">{{ potensi.uraian }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Alamat</p>
                <p class="mt-1 whitespace-pre-line">{{ potensi.alamat }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Latitude</p>
                <p class="mt-1">{{ potensi.latitude ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Longitude</p>
                <p class="mt-1">{{ potensi.longitude ?? '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-500">Peta Lokasi</p>
                <LocationMap :latitude="potensi.latitude" :longitude="potensi.longitude" :interactive="false" />
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi Tenaga Kerja</p>
                <p class="mt-1">{{ formatNumber(potensi.estimasi_tk) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi Upah</p>
                <p class="mt-1">Rp {{ formatNumber(potensi.estimasi_upah) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi Iuran</p>
                <p class="mt-1">Rp {{ formatNumber(potensi.estimasi_iuran) }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status Tindak Lanjut</p>
                <p class="mt-1">{{ potensi.status_tindak_lanjut }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Program Potensi</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span
                        v-for="program in potensi.program_potensi"
                        :key="program.id"
                        class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                    >
                        {{ program.jenis_program }}
                    </span>
                    <span v-if="!potensi.program_potensi?.length" class="text-slate-400">-</span>
                </div>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catatan</p>
                <p class="mt-1 whitespace-pre-line">{{ potensi.catatan || '-' }}</p>
            </div>
        </div>
    </div>
</template>
