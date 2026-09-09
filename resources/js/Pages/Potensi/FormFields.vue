<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LocationMap from '@/Components/LocationMap.vue';

defineProps({
    form: {
        type: Object,
        required: true,
    },
});

const programs = ['JKK', 'JKM', 'JHT', 'JP'];
const statuses = ['Belum dihubungi', 'Sudah dihubungi', 'Jadi peserta', 'Ditolak'];
</script>

<template>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div>
            <InputLabel for="tanggal_input" value="Tanggal Input" />
            <input
                id="tanggal_input"
                v-model="form.tanggal_input"
                type="date"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.tanggal_input }"
            />
            <InputError class="mt-1" :message="form.errors.tanggal_input" />
        </div>
        <div>
            <InputLabel for="nama_usaha" value="Nama Usaha" />
            <input
                id="nama_usaha"
                v-model="form.nama_usaha"
                type="text"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.nama_usaha }"
                required
            />
            <InputError class="mt-1" :message="form.errors.nama_usaha" />
        </div>
        <div>
            <InputLabel for="npwp" value="NPWP" />
            <input
                id="npwp"
                v-model="form.npwp"
                type="text"
                maxlength="20"
                placeholder="Contoh: 123456789012345"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.npwp }"
            />
            <InputError class="mt-1" :message="form.errors.npwp" />
        </div>
        <div>
            <InputLabel for="segmen" value="Segmen" />
            <select
                id="segmen"
                v-model="form.segmen"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.segmen }"
                required
            >
                <option value="">Pilih segmen</option>
                <option value="PU">PU — Penerima Upah</option>
                <option value="BPU">BPU — Bukan Penerima Upah</option>
                <option value="Jakon">Jakon — Jasa Konstruksi</option>
            </select>
            <InputError class="mt-1" :message="form.errors.segmen" />
        </div>
        <div>
            <InputLabel for="estimasi_tk" value="Estimasi Tenaga Kerja" />
            <input
                id="estimasi_tk"
                v-model="form.estimasi_tk"
                type="number"
                min="1"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.estimasi_tk }"
                required
            />
            <InputError class="mt-1" :message="form.errors.estimasi_tk" />
        </div>
        <div>
            <InputLabel for="estimasi_upah" value="Estimasi Upah" />
            <input
                id="estimasi_upah"
                v-model="form.estimasi_upah"
                type="number"
                min="0"
                step="0.01"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.estimasi_upah }"
                required
            />
            <InputError class="mt-1" :message="form.errors.estimasi_upah" />
        </div>
        <div>
            <InputLabel for="estimasi_iuran" value="Estimasi Iuran" />
            <input
                id="estimasi_iuran"
                v-model="form.estimasi_iuran"
                type="number"
                min="0"
                step="0.01"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.estimasi_iuran }"
                required
            />
            <InputError class="mt-1" :message="form.errors.estimasi_iuran" />
        </div>
        <div class="md:col-span-2">
            <InputLabel for="uraian" value="Uraian" />
            <textarea
                id="uraian"
                v-model="form.uraian"
                rows="4"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.uraian }"
                required
            />
            <InputError class="mt-1" :message="form.errors.uraian" />
        </div>
        <div class="md:col-span-2">
            <InputLabel for="alamat" value="Alamat" />
            <textarea
                id="alamat"
                v-model="form.alamat"
                rows="3"
                class="app-input mt-1"
                :class="{ 'app-input-error': form.errors.alamat }"
                required
            />
            <InputError class="mt-1" :message="form.errors.alamat" />
        </div>
        <div class="md:col-span-2">
            <InputLabel value="Lokasi Potensi" />
            <div class="mt-2">
                <LocationMap
                    :latitude="form.latitude"
                    :longitude="form.longitude"
                    @update:latitude="form.latitude = $event"
                    @update:longitude="form.longitude = $event"
                />
            </div>
            <InputError class="mt-1" :message="form.errors.latitude || form.errors.longitude" />
        </div>
        <div>
            <InputLabel for="latitude" value="Latitude" />
            <input
                id="latitude"
                v-model="form.latitude"
                type="number"
                step="0.0000001"
                class="app-input mt-1 bg-slate-50"
                readonly
            />
        </div>
        <div>
            <InputLabel for="longitude" value="Longitude" />
            <input
                id="longitude"
                v-model="form.longitude"
                type="number"
                step="0.0000001"
                class="app-input mt-1 bg-slate-50"
                readonly
            />
        </div>
        <div class="md:col-span-2">
            <InputLabel value="Program Potensi" />
            <div class="mt-2 grid grid-cols-2 gap-3 rounded-xl border border-slate-200 p-4 md:grid-cols-4">
                <label
                    v-for="program in programs"
                    :key="program"
                    class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-700"
                >
                    <input
                        v-model="form.programs"
                        type="checkbox"
                        :value="program"
                        class="rounded border-slate-300 text-brand-teal focus:ring-teal-600"
                    />
                    {{ program }}
                </label>
            </div>
            <InputError class="mt-1" :message="form.errors.programs" />
        </div>
        <div class="md:col-span-2">
            <InputLabel for="status_tindak_lanjut" value="Status Tindak Lanjut" />
            <select id="status_tindak_lanjut" v-model="form.status_tindak_lanjut" class="app-input mt-1">
                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <InputLabel for="catatan" value="Catatan" />
            <textarea id="catatan" v-model="form.catatan" rows="3" class="app-input mt-1" />
            <InputError class="mt-1" :message="form.errors.catatan" />
        </div>
    </div>
</template>
