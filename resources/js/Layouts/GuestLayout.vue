<script setup>
import CursorGlow from '@/Components/CursorGlow.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="auth-shell relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
        <div class="auth-orb auth-orb-green" />
        <div class="auth-orb auth-orb-cyan" />
        <div class="auth-orb auth-orb-lime" />
        <CursorGlow />

        <div class="relative z-10 w-full max-w-md animate-auth-panel">
            <Link href="/" class="mb-7 flex items-center justify-center gap-3">
                <img src="/img/jamsostek.jpg" alt="Logo BPJS Ketenagakerjaan" class="h-12 w-12 rounded-xl bg-white object-contain p-1 shadow-lg" />
                <div class="text-left">
                    <p class="text-sm font-bold text-[#087a43]">BPJS Ketenagakerjaan</p>
                    <p class="text-xs text-slate-600">Pencatatan Potensi Bidang KSI</p>
                </div>
            </Link>
            <div class="auth-glass-card rounded-[2rem] p-6 sm:p-8">
                <Transition name="auth-page" mode="out-in">
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </div>
        </div>
    </div>
</template>
