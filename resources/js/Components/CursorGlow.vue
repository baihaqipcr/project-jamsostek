<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';

const glowStyle = ref({ opacity: 0 });

const moveGlow = (event) => {
    glowStyle.value = {
        opacity: 1,
        transform: `translate3d(${event.clientX - 110}px, ${event.clientY - 110}px, 0)`,
    };
};

const hideGlow = () => {
    glowStyle.value = { opacity: 0 };
};

onMounted(() => {
    window.addEventListener('pointermove', moveGlow, { passive: true });
    window.addEventListener('pointerleave', hideGlow);
});

onBeforeUnmount(() => {
    window.removeEventListener('pointermove', moveGlow);
    window.removeEventListener('pointerleave', hideGlow);
});
</script>

<template>
    <div class="auth-cursor-glow" :style="glowStyle" aria-hidden="true" />
</template>
