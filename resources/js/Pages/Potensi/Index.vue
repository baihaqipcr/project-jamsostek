<script setup>
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { FileDown, Plus, Search } from '@lucide/vue';

const props = defineProps({
    potensis: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ segmen: '', status: '' }),
    },
});

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

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-brand-teal">Bidang KSI</p>
            <h1 class="text-2xl font-bold text-navy-900">Daftar Potensi</h1>
            <p class="mt-1 text-sm text-slate-600">Catatan calon peserta BPJS Ketenagakerjaan.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a
                :href="route('potensi.export')"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                <FileDown class="h-4 w-4" />
                Export Excel
            </a>
            <Link
                :href="route('potensi.create')"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-teal px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700"
            >
                <Plus class="h-4 w-4" />
                Tambah Potensi
            </Link>
        </div>
    </div>

    <form class="mb-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-card md:grid-cols-4" @submit.prevent="applyFilter">
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
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-navy-900 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-800 md:col-span-2">
            <Search class="h-4 w-4" />
            Terapkan filter
        </button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
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
                        class="transition hover:bg-teal-50/40"
                    >
                        <td class="px-4 py-3 font-medium text-navy-900">{{ potensi.nama_usaha }}</td>
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
                        <td colspan="7" class="px-4 py-10 text-center text-slate-500">Belum ada data potensi.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 px-4 py-3">
            <Pagination :links="potensis.meta?.links || potensis.links || []" />
        </div>
    </div>
</template>
