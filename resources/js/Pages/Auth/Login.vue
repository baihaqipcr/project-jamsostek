<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk" />

        <div class="auth-reveal auth-delay-1">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#087a43]">Ruang kerja KSI</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#3AB44A]">Masuk petugas</h1>
            <p class="mt-2 mb-6 text-sm leading-6 text-slate-600">Gunakan akun bidang KSI untuk mencatat potensi.</p>
        </div>

        <div v-if="status" class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50/70 px-3 py-2 text-sm font-medium text-emerald-700">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="auth-reveal auth-delay-2">
                <InputLabel for="email" value="Email" class="auth-label" />
                <TextInput
                    id="email"
                    type="email"
                    class="auth-input mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="auth-reveal auth-delay-3 mt-4">
                <InputLabel for="password" value="Kata sandi" class="auth-label" />
                <TextInput
                    id="password"
                    type="password"
                    class="auth-input mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="auth-reveal auth-delay-4 mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
                </label>
            </div>

            <div class="auth-reveal auth-delay-5 mt-6 flex flex-col gap-4">
                <div class="flex items-center justify-between gap-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-semibold text-[#05ADDC] transition hover:text-[#087a43] hover:underline"
                >
                    Lupa kata sandi?
                </Link>
                <PrimaryButton class="auth-primary-button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Masuk
                </PrimaryButton>
                </div>
                <p class="text-center text-sm text-slate-600">
                    Belum punya akun?
                    <Link :href="route('register')" class="font-bold text-[#05ADDC] transition hover:text-[#087a43]">Daftar</Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
