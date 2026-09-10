<script setup>
import axios from 'axios';
import * as XLSX from 'xlsx';
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircle, CheckCircle2, FileUp, LoaderCircle } from '@lucide/vue';
import DownloadTemplateButton from '@/Components/DownloadTemplateButton.vue';

const fileInput = ref(null);
const selectedFile = ref(null);
const rows = ref([]);
const errors = ref([]);
const result = ref(null);
const parsing = ref(false);
const submitting = ref(false);
const parseError = ref('');

const canSubmit = computed(() => rows.value.length > 0 && !parsing.value && !submitting.value);

const toDateString = (value) => {
    if (value instanceof Date && !Number.isNaN(value.getTime())) {
        return value.toISOString().slice(0, 10);
    }

    if (typeof value === 'number') {
        const date = XLSX.SSF.parse_date_code(value);
        if (date) {
            return `${date.y}-${String(date.m).padStart(2, '0')}-${String(date.d).padStart(2, '0')}`;
        }
    }

    return String(value ?? '').trim();
};

const toPrograms = (value) => String(value ?? '')
    .split(',')
    .map((program) => program.trim().toUpperCase())
    .filter(Boolean);

const mapRow = (row) => ({
    tanggal_input: toDateString(row[0]),
    nama_usaha: String(row[1] ?? '').trim(),
    npwp: String(row[2] ?? '').trim(),
    segmen: String(row[3] ?? '').trim(),
    uraian: String(row[4] ?? '').trim(),
    alamat: String(row[5] ?? '').trim(),
    latitude: row[6] ?? '',
    longitude: row[7] ?? '',
    estimasi_tk: row[8] ?? '',
    estimasi_upah: row[9] ?? '',
    estimasi_iuran: row[10] ?? '',
    programs: toPrograms(row[11]),
    status_tindak_lanjut: String(row[12] ?? '').trim(),
    catatan: String(row[13] ?? '').trim(),
});

const chooseFile = () => fileInput.value?.click();

const parseFile = async (event) => {
    const file = event.target.files?.[0];
    if (!file) {
        return;
    }

    selectedFile.value = file;
    rows.value = [];
    errors.value = [];
    result.value = null;
    parseError.value = '';
    parsing.value = true;

    try {
        const workbook = XLSX.read(await file.arrayBuffer(), { type: 'array', cellDates: true });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        const values = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
        rows.value = values.slice(1).filter((row) => row.some((value) => String(value ?? '').trim() !== '')).map(mapRow);

        if (!rows.value.length) {
            parseError.value = 'File belum berisi data setelah baris header.';
        }
    } catch {
        parseError.value = 'File tidak dapat dibaca. Gunakan file .xlsx dari template.';
    } finally {
        parsing.value = false;
    }
};

const submit = async () => {
    if (!canSubmit.value) {
        return;
    }

    submitting.value = true;
    errors.value = [];
    result.value = null;

    try {
        const response = await axios.post(route('potensi.import.store'), { rows: rows.value });
        result.value = response.data;
        errors.value = response.data.errors || [];
        rows.value = [];
        selectedFile.value = null;
        if (fileInput.value) {
            fileInput.value.value = '';
        }
    } catch (error) {
        parseError.value = error.response?.data?.message || 'Data gagal diimpor.';
    } finally {
        submitting.value = false;
    }
};
</script>

<template>
    <Head title="Impor Potensi" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-brand-teal">Bidang KSI</p>
            <h1 class="text-2xl font-bold text-navy-900">Impor Potensi</h1>
            <p class="mt-1 text-sm text-slate-600">Unggah data potensi secara massal melalui Excel.</p>
        </div>
        <Link :href="route('potensi.index')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">
            Kembali
        </Link>
    </div>

    <div class="space-y-4">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-navy-900">1. Siapkan file</h2>
                    <p class="mt-1 text-sm text-slate-600">Gunakan template resmi agar kolom terbaca dengan benar.</p>
                </div>
                <DownloadTemplateButton />
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
            <h2 class="font-semibold text-navy-900">2. Unggah dan periksa data</h2>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                <input ref="fileInput" type="file" accept=".xlsx,.xls" class="hidden" @change="parseFile" />
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-teal px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700 disabled:opacity-60"
                    :disabled="parsing"
                    @click="chooseFile"
                >
                    <LoaderCircle v-if="parsing" class="h-4 w-4 animate-spin" />
                    <FileUp v-else class="h-4 w-4" />
                    {{ parsing ? 'Membaca file...' : 'Pilih File Excel' }}
                </button>
                <span class="text-sm text-slate-600">{{ selectedFile?.name || 'Belum ada file dipilih' }}</span>
            </div>
            <p v-if="parseError" class="mt-3 flex items-center gap-2 text-sm text-red-600" role="alert">
                <AlertCircle class="h-4 w-4 shrink-0" />
                {{ parseError }}
            </p>
            <p v-if="rows.length" class="mt-3 text-sm text-slate-600">{{ rows.length }} baris siap diimpor.</p>
        </section>

        <section v-if="rows.length" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-navy-900">3. Simpan ke sistem</h2>
                    <p class="mt-1 text-sm text-slate-600">Validasi akhir dilakukan oleh server.</p>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-navy-900 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-800 disabled:opacity-60"
                    :disabled="!canSubmit"
                    @click="submit"
                >
                    <LoaderCircle v-if="submitting" class="h-4 w-4 animate-spin" />
                    <CheckCircle2 v-else class="h-4 w-4" />
                    {{ submitting ? 'Mengimpor...' : 'Impor Data' }}
                </button>
            </div>
        </section>

        <section v-if="result" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            Berhasil: {{ result.created }} data baru dan {{ result.updated }} data diperbarui.
        </section>

        <section v-if="errors.length" class="rounded-2xl border border-red-200 bg-red-50 p-4">
            <h2 class="font-semibold text-red-800">Baris yang perlu diperbaiki</h2>
            <ul class="mt-2 space-y-1 text-sm text-red-700">
                <li v-for="error in errors" :key="`${error.row}-${error.message}`">Baris {{ error.row }}: {{ error.message }}</li>
            </ul>
        </section>
    </div>
</template>
