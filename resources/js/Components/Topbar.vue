<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { ChevronRight, LogOut, Menu } from '@lucide/vue';

const showingSidebar = defineModel('sidebar', { type: Boolean, default: false });
const page = usePage();

const crumbs = computed(() => {
    const items = [{ label: 'Beranda', href: route('potensi.index') }];

    if (route().current('potensi.create')) {
        items.push({ label: 'Tambah Potensi' });
    } else if (route().current('potensi.edit')) {
        items.push({ label: 'Daftar Potensi', href: route('potensi.index') });
        items.push({ label: 'Ubah Data' });
    } else if (route().current('potensi.show')) {
        items.push({ label: 'Daftar Potensi', href: route('potensi.index') });
        items.push({ label: 'Detail Potensi' });
    } else if (route().current('profile.edit')) {
        items.push({ label: 'Profil Petugas' });
    } else {
        items.push({ label: 'Daftar Potensi' });
    }

    return items;
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                    @click="showingSidebar = true"
                >
                    <Menu class="h-5 w-5" />
                </button>

                <nav class="flex min-w-0 items-center gap-1 overflow-x-auto text-sm">
                    <template v-for="(crumb, index) in crumbs" :key="crumb.label">
                        <ChevronRight v-if="index > 0" class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                        <Link
                            v-if="crumb.href && index < crumbs.length - 1"
                            :href="crumb.href"
                            class="shrink-0 text-slate-500 hover:text-brand-teal"
                        >
                            {{ crumb.label }}
                        </Link>
                        <span v-else class="truncate font-medium text-navy-900">{{ crumb.label }}</span>
                    </template>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold text-navy-900">{{ page.props.auth.user.name }}</p>
                    <p class="text-xs text-slate-500">{{ page.props.auth.user.email }}</p>
                </div>
                <Link
                    :href="route('profile.edit')"
                    class="hidden rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 sm:inline-flex"
                >
                    Profil
                </Link>
                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg bg-navy-900 px-3 py-2 text-sm font-medium text-white hover:bg-navy-800"
                    @click="logout"
                >
                    <LogOut class="h-4 w-4" />
                    Keluar
                </button>
            </div>
        </div>
    </header>
</template>
