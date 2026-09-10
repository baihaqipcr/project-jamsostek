<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="auth-reveal auth-delay-1">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#087a43]">Akses petugas</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#3AB44A]">Daftar petugas</h1>
            <p class="mt-2 mb-6 text-sm leading-6 text-slate-600">Buat akun bidang KSI untuk mencatat potensi.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="auth-reveal auth-delay-2">
                <InputLabel for="name" value="Nama Lengkap" class="auth-label" />

                <TextInput
                    id="name"
                    type="text"
                    class="auth-input mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="auth-reveal auth-delay-3 mt-4">
                <InputLabel for="email" value="Email (KSI)" class="auth-label" />

                <TextInput
                    id="email"
                    type="email"
                    class="auth-input mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="auth-reveal auth-delay-4 mt-4">
                <InputLabel for="password" value="Kata Sandi" class="auth-label" />

                <TextInput
                    id="password"
                    type="password"
                    class="auth-input mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="auth-reveal auth-delay-5 mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Konfirmasi Kata Sandi"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="auth-input mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="auth-reveal auth-delay-6 mt-6 flex flex-col gap-4">
                <div class="flex flex-col-reverse items-center justify-between gap-3 sm:flex-row">
                <Link
                    :href="route('login')"
                    class="text-sm font-semibold text-[#05ADDC] transition hover:text-[#087a43] hover:underline"
                >
                    Sudah punya akun? Masuk
                </Link>

                <PrimaryButton
                    class="auth-primary-button"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Daftar petugas
                </PrimaryButton>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
