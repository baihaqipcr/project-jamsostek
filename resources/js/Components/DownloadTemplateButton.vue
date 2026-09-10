<script setup>
import { FileDown } from '@lucide/vue';
import { ref } from 'vue';

const downloading = ref(false);
const errorMessage = ref('');

const download = async () => {
    if (downloading.value) {
        return;
    }

    downloading.value = true;
    errorMessage.value = '';

    try {
        const response = await fetch(route('potensi.template'), {
            headers: {
                Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error('Template gagal diunduh.');
        }

        const blob = await response.blob();
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'Template_Import_Potensi_KSI.xlsx';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    } catch (error) {
        errorMessage.value = error instanceof Error ? error.message : 'Template gagal diunduh.';
    } finally {
        downloading.value = false;
    }
};
</script>

<template>
    <div>
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-wait disabled:opacity-60"
            :disabled="downloading"
            @click="download"
        >
            <FileDown class="h-4 w-4" />
            {{ downloading ? 'Menyiapkan template...' : 'Download Template' }}
        </button>
        <p v-if="errorMessage" class="mt-2 text-xs text-red-600" role="alert">{{ errorMessage }}</p>
    </div>
</template>
