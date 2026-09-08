<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative flex min-h-screen items-center justify-center bg-navy-950 px-4 py-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_#0e7c66_0%,_transparent_42%)] opacity-40" />
        <div class="relative w-full max-w-md">
            <Link href="/" class="mb-6 flex items-center justify-center gap-3 text-white">
                <ApplicationLogo class="h-12 w-12 text-brand-teal" />
                <div class="text-left">
                    <p class="text-sm font-semibold">BPJS Ketenagakerjaan</p>
                    <p class="text-xs text-white/70">Pencatatan Potensi Bidang KSI</p>
                </div>
            </Link>
            <div class="rounded-2xl border border-white/10 bg-white p-8 shadow-card">
                <slot />
            </div>
        </div>
    </div>
</template>
