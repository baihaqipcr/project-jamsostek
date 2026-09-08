<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    status: {
        type: Number,
        required: true,
    },
});

const titles = {
    403: 'Akses ditolak',
    404: 'Halaman tidak ditemukan',
    500: 'Terjadi kesalahan server',
    503: 'Layanan sedang tidak tersedia',
};

const descriptions = {
    403: 'Anda tidak memiliki izin untuk membuka halaman ini.',
    404: 'Tautan yang Anda tuju tidak ada atau sudah dipindahkan.',
    500: 'Sistem mengalami gangguan. Silakan coba beberapa saat lagi.',
    503: 'Aplikasi sedang dalam pemeliharaan.',
};
</script>

<template>
    <Head :title="titles[status] || 'Terjadi kesalahan'" />
    <div class="flex min-h-screen items-center justify-center bg-slate-100 px-4">
        <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-teal">{{ status }}</p>
            <h1 class="mt-3 text-3xl font-bold text-navy-900">{{ titles[status] || 'Terjadi kesalahan' }}</h1>
            <p class="mt-3 text-slate-600">
                {{ descriptions[status] || 'Halaman tidak dapat ditampilkan.' }}
            </p>
            <a
                href="/potensi"
                class="mt-8 inline-flex rounded-lg bg-brand-teal px-5 py-2.5 text-sm font-semibold text-white hover:bg-teal-700"
            >
                Kembali ke aplikasi
            </a>
        </div>
    </div>
</template>
