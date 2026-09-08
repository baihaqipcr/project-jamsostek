<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil Petugas" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy-900">Profil Petugas</h1>
        <p class="mt-1 text-sm text-slate-600">Kelola identitas akun dan kata sandi.</p>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card sm:p-8">
            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl" />
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card sm:p-8">
            <UpdatePasswordForm class="max-w-xl" />
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card sm:p-8">
            <DeleteUserForm class="max-w-xl" />
        </div>
    </div>
</template>
