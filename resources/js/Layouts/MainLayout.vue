<script setup>
import { computed, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import Topbar from '@/Components/Topbar.vue';
import SplashScreen from '@/Components/SplashScreen.vue';

const showingSidebar = ref(false);
const showingSplash = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user);

onMounted(() => {
    const userId = user.value?.id;
    const splashKey = userId ? `ksi-splash-seen-reference-v2-${userId}` : null;

    if (!splashKey || sessionStorage.getItem(splashKey)) {
        return;
    }

    showingSplash.value = true;
    sessionStorage.setItem(splashKey, '1');
    window.setTimeout(() => {
        showingSplash.value = false;
    }, 2100);
});
</script>

<template>
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="ambient-drift absolute -left-24 top-24 h-64 w-64 rounded-full bg-[#3AB44A]/10 blur-3xl" />
            <div class="ambient-drift absolute -right-24 top-8 h-72 w-72 rounded-full bg-[#05ADDC]/10 blur-3xl" style="animation-delay: -3s" />
            <div class="absolute bottom-0 left-1/3 h-56 w-56 rounded-full bg-[#D6E65D]/10 blur-3xl" />
        </div>

        <Transition name="splash">
            <SplashScreen v-if="showingSplash" :name="user?.name" />
        </Transition>

        <Sidebar v-model="showingSidebar" />

        <div class="lg:pl-72">
            <Topbar v-model:sidebar="showingSidebar" />

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <div
                    v-if="$page.props.flash?.success"
                    class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                >
                    {{ $page.props.flash.success }}
                </div>
                <div
                    v-if="$page.props.flash?.error"
                    class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                >
                    {{ $page.props.flash.error }}
                </div>

                <Transition name="page" mode="out-in">
                    <div :key="$page.url">
                        <slot />
                    </div>
                </Transition>
            </main>
        </div>
    </div>
</template>
