<script setup>
import FormFields from './FormFields.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    potensi: {
        type: Object,
        required: true,
    },
});

const toDate = (value) => (value ? String(value).slice(0, 10) : '');

const form = useForm({
    tanggal_input: toDate(props.potensi.tanggal_input),
    nama_usaha: props.potensi.nama_usaha,
    npwp: props.potensi.npwp ?? '',
    segmen: props.potensi.segmen,
    uraian: props.potensi.uraian,
    alamat: props.potensi.alamat,
    latitude: props.potensi.latitude ?? '',
    longitude: props.potensi.longitude ?? '',
    estimasi_tk: props.potensi.estimasi_tk,
    estimasi_upah: props.potensi.estimasi_upah,
    estimasi_iuran: props.potensi.estimasi_iuran,
    programs: (props.potensi.program_potensi || []).map((item) => item.jenis_program),
    status_tindak_lanjut: props.potensi.status_tindak_lanjut,
    catatan: props.potensi.catatan ?? '',
});

const submit = () => {
    form.put(route('potensi.update', props.potensi.id));
};
</script>

<template>
    <Head title="Ubah Potensi" />

    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-navy-900">Ubah Potensi</h1>
            <p class="mt-1 text-sm text-slate-600">Perbarui data potensi {{ potensi.nama_usaha }}.</p>
        </div>
        <Link :href="route('potensi.show', potensi.id)" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">
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
                Perbarui Potensi
            </button>
        </div>
    </form>
</template>
