<script setup>
defineProps({
    compact: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="brand-lockup" :class="compact ? 'brand-lockup-compact' : ''">
        <div class="brand-mark" aria-hidden="true">
            <span class="brand-mark-green" />
            <span class="brand-mark-lime" />
            <span class="brand-mark-cyan" />
        </div>
        <div v-if="!compact" class="brand-wordmark">
            <strong>BPJS</strong>
            <span>Ketenagakerjaan</span>
        </div>
    </div>
</template>
