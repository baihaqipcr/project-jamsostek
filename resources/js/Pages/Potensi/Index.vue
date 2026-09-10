<script setup>
import Pagination from '@/Components/Pagination.vue';
import DownloadTemplateButton from '@/Components/DownloadTemplateButton.vue';
import EmptyPotensiState from '@/Components/EmptyPotensiState.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ArrowUpRight, FileDown, Filter, Plus, Sparkles, TrendingUp, Wallet, UsersRound } from '@lucide/vue';

const props = defineProps({
    potensis: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ segmen: '', status: '' }),
    },
    aggregates: {
        type: Object,
        default: () => ({
            total_count_all: 0,
            active_count_all: 0,
            total_iuran_all: 0,
            total_count_filtered: 0,
            active_count_filtered: 0,
            total_iuran_filtered: 0,
        }),
    },
});

const page = usePage();
const calculationMode = ref('page');
const displayName = computed(() => page.props.auth?.user?.name?.split(' ')[0] || 'Pegawai');
const totalTk = computed(() => props.potensis.data.reduce((total, item) => total + Number(item.estimasi_tk || 0), 0));
const pageCount = computed(() => props.potensis.data.filter((item) => item.status_tindak_lanjut === 'Jadi peserta').length);
const pageIuran = computed(() => props.potensis.data.reduce((total, item) => total + Number(item.estimasi_iuran || 0), 0));
const selectedCount = computed(() => {
    if (calculationMode.value === 'all') {
        return props.aggregates.active_count_all;
    }

    if (calculationMode.value === 'filtered') {
        return props.aggregates.active_count_filtered;
    }

    return pageCount.value;
});
const selectedIuran = computed(() => {
    if (calculationMode.value === 'all') {
        return props.aggregates.total_iuran_all;
    }

    if (calculationMode.value === 'filtered') {
        return props.aggregates.total_iuran_filtered;
    }

    return pageIuran.value;
});
const calculationSubtitle = computed(() => ({
    page: 'di halaman saat ini',
    filtered: 'dari data sesuai filter',
    all: 'dari total keseluruhan',
}[calculationMode.value]));
const totalRecords = computed(() => props.potensis.meta?.total ?? props.potensis.total ?? props.potensis.data.length);

const applyFilter = (event) => {
    const form = event.target;
    router.get(
        route('potensi.index'),
        {
            segmen: form.segmen.value,
            status: form.status.value,
        },
        { preserveState: true, replace: true },
    );
};

const destroy = (potensi) => {
    if (!window.confirm(`Hapus potensi ${potensi.nama_usaha}?`)) {
        return;
    }

    router.delete(route('potensi.destroy', potensi.id));
};

const formatNumber = (value) => Number(value || 0).toLocaleString('id-ID');
</script>

<template>
    <Head title="Daftar Potensi" />

    <section class="dashboard-hero mb-6 overflow-hidden rounded-[2rem] p-6 text-white shadow-2xl shadow-emerald-950/10 sm:p-8">
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#D6E65D] backdrop-blur">
                    <Sparkles class="h-3.5 w-3.5" /> Bidang KSI
                </div>
                <h1 class="max-w-2xl text-3xl font-extrabold tracking-tight sm:text-5xl">Selamat datang, {{ displayName }}.</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/70 sm:text-base">Pantau potensi calon peserta dan gerakkan tindak lanjut hari ini dari satu ruang kerja.</p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:flex">
                <div class="hero-mini-stat"><span>Total potensi</span><strong>{{ totalRecords }}</strong></div>
                <div class="hero-mini-stat"><span>Tenaga kerja</span><strong>{{ formatNumber(totalTk) }}</strong></div>
            </div>
        </div>
        <div class="dashboard-hero-orb dashboard-hero-orb-one" />
        <div class="dashboard-hero-orb dashboard-hero-orb-two" />
    </section>

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-[#087a43]">Ruang kerja</p>
            <h2 class="text-2xl font-bold text-navy-900">Daftar Potensi</h2>
            <p class="mt-1 text-sm text-slate-600">Catatan calon peserta BPJS Ketenagakerjaan.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <DownloadTemplateButton />
            <a
                :href="route('potensi.export')"
                class="interactive-button inline-flex items-center gap-2 rounded-xl border border-white/80 bg-white/65 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white"
            >
                <FileDown class="h-4 w-4" />
                Export Excel
            </a>
            <Link
                :href="route('potensi.create')"
                class="interactive-button inline-flex items-center gap-2 rounded-xl bg-[#087a43] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/15 hover:bg-[#056b39]"
            >
                <Plus class="h-4 w-4" />
                Tambah Potensi
            </Link>
        </div>
    </div>

    <div class="mb-5 grid gap-4 sm:grid-cols-3">
        <div class="dashboard-card glass-panel rounded-2xl p-4">
            <div class="flex items-start justify-between gap-3 text-sm text-slate-500">
                <span>Potensi aktif</span>
                <span class="card-icon-badge"><TrendingUp class="h-4 w-4" /></span>
                <select v-model="calculationMode" class="card-mode-select" aria-label="Mode perhitungan potensi aktif">
                    <option value="page">Halaman Ini</option>
                    <option value="all">Keseluruhan</option>
                </select>
            </div>
            <strong class="mt-2 block text-2xl text-navy-900">{{ formatNumber(selectedCount) }}</strong>
            <span class="text-xs text-slate-500">{{ calculationSubtitle }}</span>
        </div>
        <div class="dashboard-card glass-panel rounded-2xl p-4">
            <div class="flex items-start justify-between gap-3 text-sm text-slate-500">
                <span>Estimasi iuran</span>
            <span class="card-icon-badge card-icon-green"><Wallet class="h-4 w-4" /></span>
                <select v-model="calculationMode" class="card-mode-select" aria-label="Mode perhitungan estimasi iuran">
                    <option value="page">Halaman Ini</option>
                    <option value="filtered">Sesuai Filter</option>
                    <option value="all">Keseluruhan</option>
                </select>
            </div>
            <strong class="mt-2 block text-2xl text-navy-900">Rp {{ formatNumber(selectedIuran) }}</strong>
            <span class="text-xs text-slate-500">{{ calculationSubtitle }}</span>
        </div>
        <div class="dashboard-card glass-panel rounded-2xl p-4">
            <div class="flex items-center justify-between text-sm text-slate-500"><span>Status pantauan</span><span class="card-icon-badge card-icon-lime"><Sparkles class="h-4 w-4" /></span></div>
            <strong class="mt-2 block truncate text-2xl text-navy-900">{{ filters.status || 'Semua' }}</strong>
            <span class="text-xs text-slate-500">filter yang sedang aktif</span>
        </div>
    </div>

    <form class="glass-panel mb-5 grid grid-cols-1 gap-3 rounded-2xl p-4 md:grid-cols-[1fr_1fr_auto]" @submit.prevent="applyFilter">
        <select name="segmen" class="app-input" :value="filters.segmen">
            <option value="">Semua segmen</option>
            <option value="PU">PU</option>
            <option value="BPU">BPU</option>
            <option value="Jakon">Jakon</option>
        </select>
        <select name="status" class="app-input" :value="filters.status">
            <option value="">Semua status</option>
            <option value="Belum dihubungi">Belum dihubungi</option>
            <option value="Sudah dihubungi">Sudah dihubungi</option>
            <option value="Jadi peserta">Jadi peserta</option>
            <option value="Ditolak">Ditolak</option>
        </select>
        <button type="submit" class="interactive-button inline-flex items-center justify-center gap-2 rounded-xl bg-[#12342A] px-5 py-2 text-sm font-semibold text-white hover:bg-[#087a43]">
            <Filter class="h-4 w-4" />
            Terapkan filter
        </button>
    </form>

    <div class="glass-panel overflow-hidden rounded-[1.5rem]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/70 text-sm">
                <thead class="bg-white/35">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Usaha</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Segmen</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Program</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi TK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estimasi Iuran</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr
                        v-for="potensi in potensis.data"
                        :key="potensi.id"
                        class="table-row-reveal transition hover:bg-white/45"
                        :style="{ animationDelay: `${Math.min(potensis.data.indexOf(potensi), 8) * 45}ms` }"
                    >
                        <td class="px-4 py-4 font-semibold text-navy-900">{{ potensi.nama_usaha }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-brand-blue">
                                {{ potensi.segmen }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span
                                v-for="program in potensi.program_potensi"
                                :key="program.id"
                                class="mb-1 mr-1 inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700"
                            >
                                {{ program.jenis_program }}
                            </span>
                            <span v-if="!potensi.program_potensi?.length" class="text-slate-400">-</span>
                        </td>
                        <td class="px-4 py-3">{{ formatNumber(potensi.estimasi_tk) }}</td>
                        <td class="px-4 py-3">Rp {{ formatNumber(potensi.estimasi_iuran) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ potensi.status_tindak_lanjut }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-3">
                                <Link :href="route('potensi.show', potensi.id)" class="font-medium text-brand-blue hover:underline">Detail</Link>
                                <a :href="route('potensi.sp1', potensi.id)" class="font-medium text-brand-green hover:underline">Cetak SP1</a>
                                <Link :href="route('potensi.edit', potensi.id)" class="font-medium text-amber-600 hover:underline">Ubah</Link>
                                <button type="button" class="font-medium text-red-600 hover:underline" @click="destroy(potensi)">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!potensis.data.length">
                        <td colspan="7" class="p-0">
                            <EmptyPotensiState />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-4 py-3">
            <Pagination :links="potensis.meta?.links || potensis.links || []" />
        </div>
    </div>
</template>
