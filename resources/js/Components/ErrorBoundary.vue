<script setup>
import { onErrorCaptured, ref } from 'vue';

const error = ref(null);

onErrorCaptured((err) => {
    error.value = err;
    return false;
});

const reload = () => {
    window.location.reload();
};
</script>

<template>
    <div
        v-if="error"
        class="flex min-h-screen items-center justify-center bg-slate-100 px-4"
    >
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-card">
            <p class="text-sm font-semibold uppercase tracking-wide text-brand-teal">Kesalahan aplikasi</p>
            <h1 class="mt-2 text-2xl font-bold text-navy-900">Gagal memuat halaman</h1>
            <p class="mt-3 text-sm text-slate-600">
                Terjadi kendala saat menampilkan tautan ini. Muat ulang halaman atau kembali ke daftar potensi.
            </p>
            <div class="mt-6 flex justify-center gap-3">
                <button
                    type="button"
                    class="rounded-lg bg-brand-teal px-4 py-2 text-sm font-semibold text-white hover:bg-teal-700"
                    @click="reload"
                >
                    Muat ulang
                </button>
                <a
                    href="/potensi"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Ke daftar potensi
                </a>
            </div>
        </div>
    </div>
    <slot v-else />
</template>
