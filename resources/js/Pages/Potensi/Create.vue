<script setup>
import FormFields from './FormFields.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    tanggal_input: new Date().toISOString().slice(0, 10),
    nama_usaha: '',
    npwp: '',
    segmen: '',
    uraian: '',
    alamat: '',
    latitude: '',
    longitude: '',
    estimasi_tk: '',
    estimasi_upah: '',
    estimasi_iuran: '',
    programs: [],
    status_tindak_lanjut: 'Belum dihubungi',
    catatan: '',
});

const submit = () => {
    form.post(route('potensi.store'));
};
</script>

<template>
    <Head title="Tambah Potensi" />

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900">Tambah Potensi</h1>
            <p class="mt-1 text-sm text-slate-600">Input data calon peserta BPJS Ketenagakerjaan.</p>
        </div>
        <Link :href="route('potensi.index')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">
            Kembali
        </Link>
    </div>

    <form class="form-shell" @submit.prevent="submit">
        <FormFields :form="form" />
        <div class="mt-6 flex justify-end">
            <button
                type="submit"
                class="magnetic-save-button"
                :disabled="form.processing"
            >
                Simpan Potensi
            </button>
        </div>
    </form>
</template>
