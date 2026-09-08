<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: {
        type: [Array, Object],
        default: () => [],
    },
});

const items = computed(() => (Array.isArray(props.links) ? props.links : []));
</script>

<template>
    <nav v-if="items.length > 3" class="flex flex-wrap items-center justify-end gap-1">
        <template v-for="(link, index) in items" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                class="min-w-9 rounded-lg px-3 py-2 text-sm font-medium transition"
                :class="
                    link.active
                        ? 'bg-navy-900 text-white'
                        : 'text-slate-600 hover:bg-slate-50'
                "
                v-html="link.label"
            />
            <span
                v-else
                class="min-w-9 rounded-lg px-3 py-2 text-sm text-slate-400"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
