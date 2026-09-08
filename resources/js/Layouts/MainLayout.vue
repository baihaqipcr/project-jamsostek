<script setup>
import { ref } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import Topbar from '@/Components/Topbar.vue';

const showingSidebar = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-100">
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
