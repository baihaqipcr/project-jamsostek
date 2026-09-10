<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    ChevronDown,
    ClipboardList,
    FileSpreadsheet,
    FileUp,
    Plus,
    UserRound,
    X,
} from '@lucide/vue';

const showing = defineModel({ type: Boolean, default: false });
const page = usePage();
const isOpen = ref(true);

const isPotensiSectionActive = computed(() => route().current('potensi.*') || route().current('profile.edit'));

const links = computed(() => [
    {
        label: 'Tambah Potensi',
        href: route('potensi.create'),
        active: route().current('potensi.create'),
        icon: Plus,
    },
    {
        label: 'Impor Potensi',
        href: route('potensi.import'),
        active: route().current('potensi.import'),
        icon: FileUp,
    },
    {
        label: 'Profil Petugas',
        href: route('profile.edit'),
        active: route().current('profile.edit'),
        icon: UserRound,
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
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-white/10 bg-[#08251B]/90 text-white shadow-2xl shadow-emerald-950/20 backdrop-blur-xl transition-transform duration-200 lg:translate-x-0"
            :class="showing ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        >
            <div class="flex items-center justify-between px-5 py-5">
                <Link :href="route('potensi.index')" class="flex items-center gap-3">
                    <img
                        src="/img/jamsostek.jpg"
                        alt="Logo BPJS Ketenagakerjaan"
                        class="h-10 w-10 rounded-lg bg-white object-contain p-1 shadow-lg"
                    />
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
                <button
                    type="button"
                    class="interactive-button flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                    :class="isPotensiSectionActive ? 'bg-[#087a43] text-white shadow-lg shadow-emerald-950/20' : 'text-white/75 hover:bg-white/10 hover:text-white'"
                    :aria-expanded="isOpen"
                    aria-controls="potensi-submenu"
                    @click="togglePotensi"
                >
                    <ClipboardList class="h-4 w-4" />
                    <span class="flex-1 text-left">Daftar Potensi</span>
                    <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''" />
                </button>

                <Transition name="submenu">
                    <div v-if="isOpen" id="potensi-submenu" class="ml-3 space-y-1 border-l border-white/15 pl-3">
                        <Link
                            v-for="item in links"
                            :key="item.label"
                            :href="item.href"
                            class="interactive-button flex items-center gap-3 rounded-lg px-3 py-2 text-xs font-medium transition"
                            :class="item.active ? 'bg-white/15 text-[#D6E65D]' : 'text-white/60 hover:bg-white/10 hover:text-white'"
                            @click="closeMobile"
                        >
                            <component :is="item.icon" class="h-3.5 w-3.5" />
                            {{ item.label }}
                        </Link>

                        <a
                            :href="route('potensi.export')"
                            class="interactive-button flex items-center gap-3 rounded-lg px-3 py-2 text-xs font-medium text-white/60 transition hover:bg-white/10 hover:text-white"
                        >
                            <FileSpreadsheet class="h-3.5 w-3.5" />
                            Export Excel
                        </a>
                    </div>
                </Transition>
            </nav>

            <div class="border-t border-white/10 px-5 py-4">
                <p class="text-sm font-medium">{{ user?.name }}</p>
                <p class="text-xs text-white/50">{{ user?.cabang || 'Bidang KSI' }}</p>
            </div>
        </aside>
    </div>
</template>
