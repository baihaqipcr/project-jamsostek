<script setup>
import { Link } from '@inertiajs/vue3';
import { Database, FileUp, Plus } from '@lucide/vue';
</script>

<template>
    <div class="empty-potensi-state px-6 py-14 text-center sm:py-16">
        <div class="empty-state-icon mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[#05ADDC]/10 text-[#05ADDC]">
            <Database class="h-10 w-10" stroke-width="1.5" />
        </div>
        <h3 class="mt-5 text-xl font-extrabold text-slate-900">Belum Ada Data Potensi</h3>
        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
            Mulai catat peluang kepesertaan baru atau impor data yang sudah tersedia untuk melihatnya di ruang kerja ini.
        </p>
        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
            <Link
                :href="route('potensi.create')"
                class="interactive-button inline-flex items-center justify-center gap-2 rounded-xl bg-[#087a43] px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-900/15 hover:bg-[#056b39]"
            >
                <Plus class="h-4 w-4" />
                Tambah Potensi
            </Link>
            <Link
                :href="route('potensi.import')"
                class="interactive-button inline-flex items-center justify-center gap-2 rounded-xl border border-[#05ADDC]/25 bg-[#05ADDC]/10 px-4 py-2.5 text-sm font-bold text-[#087a43] hover:bg-[#05ADDC]/20"
            >
                <FileUp class="h-4 w-4" />
                Impor dari Excel
            </Link>
        </div>
    </div>
</template>
