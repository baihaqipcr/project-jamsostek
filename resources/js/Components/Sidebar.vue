<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    ChevronDown,
    ClipboardList,
    FileSpreadsheet,
    FileUp,
    PanelLeftClose,
    PanelLeftOpen,
    Plus,
    UserRound,
    X,
} from '@lucide/vue';

const showing = defineModel({ type: Boolean, default: false });
const collapsed = defineModel('collapsed', { type: Boolean, default: false });
const page = usePage();
const isOpen = ref(true);
const currentPath = computed(() => page.url.split('?')[0]);

const isPotensiSectionActive = computed(() => currentPath.value.startsWith('/potensi/') || currentPath.value === '/potensi' || currentPath.value === '/profile');

const links = computed(() => [
    {
        label: 'Tambah Potensi',
        href: route('potensi.create'),
        active: currentPath.value === '/potensi/create',
        icon: Plus,
    },
    {
        label: 'Impor Potensi',
        href: route('potensi.import'),
        active: currentPath.value === '/potensi/import',
        icon: FileUp,
    },
]);

const togglePotensi = () => {
    isOpen.value = !isOpen.value;
};

const closeMobile = () => {
    showing.value = false;
};

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
            class="sidebar-surface fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-white/30 text-white shadow-2xl shadow-cyan-950/20 backdrop-blur-xl transition-[width,transform] duration-300"
            :class="[
                showing ? 'translate-x-0' : '-translate-x-full',
                collapsed ? 'lg:-translate-x-full' : 'lg:translate-x-0',
            ]"
        >
            <header class="shrink-0">
                <div class="relative flex items-center justify-between px-5 py-5">
                    <Link :href="route('potensi.index')" class="flex min-w-0 items-center gap-3">
                        <img
                            src="/img/jamsostek.jpg"
                            alt="Logo BPJS Ketenagakerjaan"
                            class="h-11 w-11 rounded-xl bg-white object-contain p-1.5 shadow-lg ring-1 ring-white/40"
                        />
                        <div class="min-w-0">
                            <p class="truncate text-sm font-bold leading-tight text-white">BPJS Ketenagakerjaan</p>
                            <p class="mt-1 truncate text-[0.68rem] font-medium text-white/65">Pencatatan Potensi KSI</p>
                        </div>
                    </Link>
                    <button
                        type="button"
                        class="sidebar-collapse-button absolute right-3 top-3 hidden rounded-lg p-2 text-white/80 hover:bg-white/15 hover:text-white lg:block"
                        title="Tutup sidebar"
                        aria-label="Tutup sidebar"
                        @click="collapsed = true"
                    >
                        <PanelLeftClose class="h-4 w-4" />
                    </button>
                    <button
                        type="button"
                        class="rounded-lg p-2 text-white/70 hover:bg-white/10 lg:hidden"
                        title="Tutup menu"
                        aria-label="Tutup menu"
                        @click="showing = false"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>
            </header>

            <nav class="sidebar-navigation min-h-0 flex-1 overflow-y-auto px-3 py-3" aria-label="Navigasi utama">
                <p class="mb-2 px-3 text-[0.65rem] font-bold uppercase tracking-[0.2em] text-white/45">Menu utama</p>
                <div
                    class="flex w-full items-center rounded-xl border-l-4 py-1 text-sm font-semibold transition"
                    :class="isPotensiSectionActive ? 'border-[#D6E65D] bg-[#087A43]/80 text-white shadow-lg shadow-cyan-950/20' : 'border-transparent text-white/85 hover:bg-white/15 hover:text-white'"
                >
                    <Link
                        :href="route('potensi.index')"
                        class="interactive-button flex min-w-0 flex-1 items-center gap-3 rounded-l-lg px-3 py-2"
                        @click="closeMobile"
                    >
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/10">
                        <ClipboardList class="h-4 w-4" />
                        </span>
                        <span class="flex-1 text-left">Daftar Potensi</span>
                    </Link>
                    <button
                        type="button"
                        class="interactive-button rounded-r-lg px-3 py-2"
                        :aria-expanded="isOpen"
                        aria-controls="potensi-submenu"
                        title="Buka atau tutup submenu"
                        @click="togglePotensi"
                    >
                        <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''" />
                    </button>
                </div>

                <Transition name="submenu">
                    <div v-if="isOpen" id="potensi-submenu" class="ml-4 mt-2 space-y-1 border-l border-white/20 pl-3">
                        <Link
                            v-for="item in links"
                            :key="item.label"
                            :href="item.href"
                            class="interactive-button flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-medium transition"
                            :class="item.active ? 'border-l-2 border-[#D6E65D] bg-white/10 pl-2 text-white' : 'border-l-2 border-transparent text-white/75 hover:bg-white/15 hover:text-white'"
                            @click="closeMobile"
                        >
                            <component :is="item.icon" class="h-3.5 w-3.5" />
                            {{ item.label }}
                        </Link>

                        <a
                            :href="route('potensi.export')"
                            class="interactive-button flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-medium text-white/75 transition hover:bg-white/15 hover:text-white"
                        >
                            <FileSpreadsheet class="h-3.5 w-3.5" />
                            Export Excel
                        </a>
                    </div>
                </Transition>
            </nav>

            <footer class="shrink-0 border-t border-white/15 p-4">
                <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/10 px-3 py-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#D6E65D] text-xs font-extrabold text-[#087A43]">
                        {{ user?.name?.slice(0, 1)?.toUpperCase() || 'P' }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-white">{{ user?.name || 'Pegawai' }}</p>
                        <p class="truncate text-xs text-white/60">{{ user?.cabang || 'Bidang KSI' }}</p>
                    </div>
                </div>
            </footer>
        </aside>

        <button
            v-if="collapsed"
            type="button"
            class="fixed left-4 top-20 z-[60] hidden rounded-xl border border-white/60 bg-[#087A43] p-3 text-white shadow-xl shadow-cyan-950/20 transition hover:-translate-y-0.5 hover:bg-[#05ADDC] lg:block"
            title="Buka sidebar"
            aria-label="Buka sidebar"
            @click="collapsed = false"
        >
            <PanelLeftOpen class="h-5 w-5" />
        </button>
    </div>
</template>
