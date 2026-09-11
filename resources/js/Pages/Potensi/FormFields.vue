<script setup>
import { computed, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import LocationMap from '@/Components/LocationMap.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
});

const programs = ['JKK', 'JKM', 'JHT', 'JP', 'JKP'];
const statuses = ['Belum dihubungi', 'Sudah dihubungi', 'Jadi peserta', 'Ditolak'];
const mapOpen = ref(false);
const touched = ref({});
const displayUpah = ref(String(props.form.estimasi_upah ?? ''));
const displayIuran = ref(String(props.form.estimasi_iuran ?? ''));

const requiredFields = ['nama_usaha', 'segmen', 'uraian', 'alamat', 'estimasi_tk', 'estimasi_upah', 'estimasi_iuran'];
const isInvalid = (field) => Boolean(touched.value[field] && !props.form[field]);
const isValid = (field) => Boolean(touched.value[field] && props.form[field] && !props.form.errors[field]);
const inputClass = (field) => ({
    'form-input-invalid': isInvalid(field) || props.form.errors[field],
    'form-input-valid': isValid(field),
});
const markTouched = (field) => {
    touched.value[field] = true;
};
const formatRupiah = (value) => {
    const digits = String(value).replace(/\D/g, '');
    return digits ? new Intl.NumberFormat('id-ID').format(Number(digits)) : '';
};
const updateCurrency = (field, display, event) => {
    const digits = event.target.value.replace(/\D/g, '');
    display.value = formatRupiah(digits);
    props.form[field] = digits;
    markTouched(field);
};
const coordinatesPicked = computed(() => Boolean(props.form.latitude && props.form.longitude));
const closeMap = () => { mapOpen.value = false; };
</script>

<template>
    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <section class="form-glass-panel">
            <div class="form-section-heading">
                <span class="form-section-number">01</span>
                <div>
                    <p class="form-section-kicker">Identitas</p>
                    <h2>Informasi perusahaan</h2>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <InputLabel for="nama_usaha" value="Nama Usaha / Perusahaan" />
                    <input id="nama_usaha" v-model="form.nama_usaha" type="text" class="app-input form-interactive mt-1" :class="inputClass('nama_usaha')" required @blur="markTouched('nama_usaha')" />
                    <InputError class="mt-1" :message="form.errors.nama_usaha" />
                </div>
                <div>
                    <InputLabel for="npwp" value="NPWP" />
                    <input id="npwp" v-model="form.npwp" type="text" maxlength="20" placeholder="Contoh: 123456789012345" class="app-input form-interactive mt-1" :class="inputClass('npwp')" />
                    <InputError class="mt-1" :message="form.errors.npwp" />
                </div>
                <div>
                    <InputLabel for="segmen" value="Segmen" />
                    <select id="segmen" v-model="form.segmen" class="app-input form-interactive mt-1" :class="inputClass('segmen')" required @blur="markTouched('segmen')">
                        <option value="">Pilih segmen</option>
                        <option value="PU">PU — Penerima Upah</option>
                        <option value="BPU">BPU — Bukan Penerima Upah</option>
                        <option value="Jakon">Jakon — Jasa Konstruksi</option>
                    </select>
                    <InputError class="mt-1" :message="form.errors.segmen" />
                </div>
                <div>
                    <InputLabel for="uraian" value="Uraian / Bidang Usaha" />
                    <textarea id="uraian" v-model="form.uraian" rows="3" class="app-input form-interactive mt-1" :class="inputClass('uraian')" required @blur="markTouched('uraian')" />
                    <InputError class="mt-1" :message="form.errors.uraian" />
                </div>
                <div>
                    <InputLabel for="tanggal_input" value="Tanggal Input" />
                    <input id="tanggal_input" v-model="form.tanggal_input" type="date" class="app-input form-interactive mt-1" :class="inputClass('tanggal_input')" />
                    <InputError class="mt-1" :message="form.errors.tanggal_input" />
                </div>
            </div>
        </section>

        <section class="form-glass-panel">
            <div class="form-section-heading">
                <span class="form-section-number form-section-number-green">02</span>
                <div>
                    <p class="form-section-kicker">Proyeksi</p>
                    <h2>Estimasi & program</h2>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <InputLabel for="estimasi_tk" value="Estimasi Tenaga Kerja" />
                    <input id="estimasi_tk" v-model="form.estimasi_tk" type="number" min="1" class="app-input form-interactive mt-1" :class="inputClass('estimasi_tk')" required @blur="markTouched('estimasi_tk')" />
                    <InputError class="mt-1" :message="form.errors.estimasi_tk" />
                </div>
                <div>
                    <InputLabel for="estimasi_upah" value="Estimasi Upah" />
                    <div class="relative mt-1">
                        <span class="currency-prefix">Rp</span>
                        <input id="estimasi_upah" :value="displayUpah" type="text" inputmode="numeric" placeholder="0" class="app-input form-interactive pl-10" :class="inputClass('estimasi_upah')" required @input="updateCurrency('estimasi_upah', displayUpah, $event)" @blur="markTouched('estimasi_upah')" />
                    </div>
                    <InputError class="mt-1" :message="form.errors.estimasi_upah" />
                </div>
                <div>
                    <InputLabel for="estimasi_iuran" value="Estimasi Iuran" />
                    <div class="relative mt-1">
                        <span class="currency-prefix">Rp</span>
                        <input id="estimasi_iuran" :value="displayIuran" type="text" inputmode="numeric" placeholder="0" class="app-input form-interactive pl-10" :class="inputClass('estimasi_iuran')" required @input="updateCurrency('estimasi_iuran', displayIuran, $event)" @blur="markTouched('estimasi_iuran')" />
                    </div>
                    <InputError class="mt-1" :message="form.errors.estimasi_iuran" />
                </div>
                <div>
                    <InputLabel value="Program Potensi" />
                    <div class="mt-2 grid grid-cols-2 gap-2">
                        <label v-for="program in programs" :key="program" class="program-choice" :class="{ 'program-choice-active': form.programs.includes(program) }">
                            <input v-model="form.programs" type="checkbox" :value="program" class="sr-only" />
                            <span class="program-check">{{ form.programs.includes(program) ? '✓' : '' }}</span>
                            {{ program }}
                        </label>
                    </div>
                    <InputError class="mt-1" :message="form.errors.programs" />
                </div>
                <div>
                    <InputLabel for="status_tindak_lanjut" value="Status Tindak Lanjut" />
                    <select id="status_tindak_lanjut" v-model="form.status_tindak_lanjut" class="app-input form-interactive mt-1">
                        <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="form-glass-panel xl:col-span-1">
            <div class="form-section-heading">
                <span class="form-section-number form-section-number-cyan">03</span>
                <div>
                    <p class="form-section-kicker">Survei lapangan</p>
                    <h2>Lokasi & catatan</h2>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <InputLabel for="alamat" value="Alamat Lengkap" />
                    <textarea id="alamat" v-model="form.alamat" rows="3" class="app-input form-interactive mt-1" :class="inputClass('alamat')" required @blur="markTouched('alamat')" />
                    <InputError class="mt-1" :message="form.errors.alamat" />
                </div>
                <div class="coordinate-panel">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold text-[#12342a]">Titik lokasi</p>
                            <p class="mt-1 text-xs text-slate-500">Pilih titik usaha dari peta.</p>
                        </div>
                        <span v-if="coordinatesPicked" class="coordinate-success" aria-label="Lokasi tersimpan">✓</span>
                    </div>
                    <button type="button" class="map-picker-button mt-3" @click="mapOpen = true">
                        <span class="text-lg">⌖</span>
                        Pilih Lokasi di Peta
                    </button>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div>
                            <InputLabel for="latitude" value="Latitude" />
                            <input id="latitude" v-model="form.latitude" type="text" class="app-input mt-1 bg-white/50 text-xs" readonly />
                        </div>
                        <div>
                            <InputLabel for="longitude" value="Longitude" />
                            <input id="longitude" v-model="form.longitude" type="text" class="app-input mt-1 bg-white/50 text-xs" readonly />
                        </div>
                    </div>
                    <InputError class="mt-1" :message="form.errors.latitude || form.errors.longitude" />
                </div>
                <div>
                    <InputLabel for="catatan" value="Catatan" />
                    <textarea id="catatan" v-model="form.catatan" rows="4" class="app-input form-interactive mt-1" :class="inputClass('catatan')" />
                    <InputError class="mt-1" :message="form.errors.catatan" />
                </div>
            </div>
        </section>
    </div>

    <Teleport to="body">
        <div v-if="mapOpen" class="map-modal-backdrop" role="dialog" aria-modal="true" aria-label="Pilih lokasi di peta" @click.self="closeMap">
            <div class="map-modal-panel">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="form-section-kicker">Tagging lokasi</p>
                        <h2 class="mt-1 text-xl font-extrabold text-[#12342a]">Pilih titik usaha</h2>
                        <p class="mt-1 text-sm text-slate-500">Klik peta atau geser penanda, lalu tutup jendela.</p>
                    </div>
                    <button type="button" class="modal-close-button" aria-label="Tutup peta" @click="closeMap">×</button>
                </div>
                <div class="mt-4 overflow-hidden rounded-2xl border border-white/80 shadow-inner">
                    <LocationMap :latitude="form.latitude" :longitude="form.longitude" @update:latitude="form.latitude = $event" @update:longitude="form.longitude = $event" />
                </div>
                <button type="button" class="map-confirm-button mt-4" @click="closeMap">Simpan titik lokasi</button>
            </div>
        </div>
    </Teleport>
</template>
