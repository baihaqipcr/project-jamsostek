<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    ClipboardList,
    FileSpreadsheet,
    Plus,
    UserRound,
    X,
} from '@lucide/vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const showing = defineModel({ type: Boolean, default: false });
const page = usePage();

const links = computed(() => [
    {
        label: 'Daftar Potensi',
        href: route('potensi.index'),
        active: route().current('potensi.index') || route().current('potensi.show'),
        icon: ClipboardList,
    },
    {
        label: 'Tambah Potensi',
        href: route('potensi.create'),
        active: route().current('potensi.create'),
        icon: Plus,
    },
    {
        label: 'Profil Petugas',
        href: route('profile.edit'),
        active: route().current('profile.edit'),
        icon: UserRound,
    },
]);

const user = computed(() => page.props.auth.user);
</script>

<template>
    <div>
        <div
            v-if="showing"
            class="fixed inset-0 z-40 bg-navy-950/50 lg:hidden"
            @click="showing = false"
        />

        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-navy-900 text-white transition-transform duration-200 lg:translate-x-0"
            :class="showing ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex items-center justify-between px-5 py-5">
                <Link :href="route('potensi.index')" class="flex items-center gap-3">
                    <ApplicationLogo class="h-10 w-10 text-brand-teal" />
                    <div>
                        <p class="text-sm font-semibold leading-tight">BPJS Ketenagakerjaan</p>
                        <p class="text-xs text-white/60">Pencatatan Potensi KSI</p>
                    </div>
                </Link>
                <button class="rounded-md p-1 text-white/70 hover:bg-white/10 lg:hidden" @click="showing = false">
                    <X class="h-5 w-5" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 px-3">
                <Link
                    v-for="item in links"
                    :key="item.label"
                    :href="item.href"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                    :class="
                        item.active
                            ? 'bg-brand-teal text-white shadow-sm'
                            : 'text-white/70 hover:bg-white/10 hover:text-white'
                    "
                    @click="showing = false"
                >
                    <component :is="item.icon" class="h-4 w-4" />
                    {{ item.label }}
                </Link>

                <a
                    :href="route('potensi.export')"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white"
                >
                    <FileSpreadsheet class="h-4 w-4" />
                    Export Excel
                </a>
            </nav>

            <div class="border-t border-white/10 px-5 py-4">
                <p class="text-sm font-medium">{{ user?.name }}</p>
                <p class="text-xs text-white/50">{{ user?.cabang || 'Bidang KSI' }}</p>
            </div>
        </aside>
    </div>
</template>
