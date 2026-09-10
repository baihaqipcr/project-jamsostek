<script setup>
import { Bell, MousePointer2, Sparkles } from '@lucide/vue';
import BrandLogo from '@/Components/BrandLogo.vue';

defineProps({
    name: {
        type: String,
        default: 'Pegawai',
    },
});
</script>

<template>
    <div class="reference-stage fixed inset-0 z-[100] overflow-hidden px-4 py-6 text-slate-900 sm:px-8 lg:px-16">
        <div class="reference-orb reference-orb-green" />
        <div class="reference-orb reference-orb-cyan" />
        <div class="reference-orb reference-orb-lime" />
        <div class="reference-orbit reference-orbit-one" />
        <div class="reference-orbit reference-orbit-two" />

        <div class="reference-topbar glass-surface hidden items-center justify-end gap-5 sm:flex">
            <Bell class="h-5 w-5 text-[#087a43]" />
            <Sparkles class="h-5 w-5 text-[#087a43]" />
        </div>

        <div class="reference-stack" aria-label="Selamat datang">
            <div class="reference-panel reference-panel-back glass-surface" />
            <div class="reference-panel reference-panel-main glass-surface">
                <div class="reference-panel-content">
                    <BrandLogo />
                    <p class="mt-6 text-xs font-bold uppercase tracking-[0.32em] text-[#087a43]">Bidang KSI</p>
                    <h1 class="mt-3 max-w-xl text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">
                        Selamat datang, {{ name }}!
                    </h1>
                    <p class="mt-3 max-w-md text-base text-slate-700 sm:text-lg">Merombak masa depan melalui pencatatan potensi yang lebih baik.</p>
                </div>
                <div class="reference-cursor hidden sm:block">
                    <MousePointer2 class="h-8 w-8 rotate-[-18deg] text-[#12342A]" />
                    <span class="reference-cursor-ring" />
                </div>
            </div>

            <div class="reference-panel reference-form-panel glass-surface">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#087a43]">Pencatatan Potensi</p>
                        <h2 class="mt-1 text-xl font-extrabold leading-tight sm:text-3xl">Jamsostek Bidang KSI</h2>
                    </div>
                    <div class="reference-status-dot" />
                </div>
                <div class="mt-6 grid gap-3 sm:grid-cols-[1.2fr_0.8fr]">
                    <div class="space-y-3">
                        <div class="reference-field"><span>Nama Usaha</span><i /></div>
                        <div class="reference-field"><span>Alamat Lengkap</span><i /></div>
                    </div>
                    <div class="reference-summary">
                        <p>Estimasi TK</p>
                        <strong>Rp 500</strong>
                        <p class="mt-4">Estimasi Upah</p>
                        <strong>350.000</strong>
                    </div>
                </div>
                <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-700">
                    <span class="reference-pill">Program JKK, JHT</span>
                    <span class="reference-pill">Status tindak lanjut</span>
                </div>
            </div>
        </div>

        <div class="reference-footer glass-surface">
            <BrandLogo compact />
            <span>Catat potensi. Tumbuhkan perlindungan.</span>
        </div>
    </div>
</template>
