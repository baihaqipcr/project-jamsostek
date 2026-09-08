<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

const props = defineProps({
    latitude: {
        type: [Number, String],
        default: null,
    },
    longitude: {
        type: [Number, String],
        default: null,
    },
    interactive: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:latitude', 'update:longitude']);

const mapEl = ref(null);
let map;
let marker;

const defaultLat = -6.2088;
const defaultLng = 106.8456;

const parsed = (value, fallback) => {
    const number = Number(value);
    return Number.isFinite(number) ? number : fallback;
};

onMounted(() => {
    const lat = parsed(props.latitude, defaultLat);
    const lng = parsed(props.longitude, defaultLng);
    const zoom = props.latitude && props.longitude ? 15 : 10;

    map = L.map(mapEl.value).setView([lat, lng], zoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    marker = L.marker([lat, lng], { draggable: props.interactive }).addTo(map);

    if (props.interactive) {
        map.on('click', (event) => {
            marker.setLatLng(event.latlng);
            emit('update:latitude', event.latlng.lat.toFixed(7));
            emit('update:longitude', event.latlng.lng.toFixed(7));
        });

        marker.on('dragend', () => {
            const position = marker.getLatLng();
            emit('update:latitude', position.lat.toFixed(7));
            emit('update:longitude', position.lng.toFixed(7));
        });
    }

    setTimeout(() => map.invalidateSize(), 200);
});

watch(
    () => [props.latitude, props.longitude],
    ([lat, lng]) => {
        if (!map || !marker || !lat || !lng) {
            return;
        }

        const next = [parsed(lat, defaultLat), parsed(lng, defaultLng)];
        marker.setLatLng(next);
        map.setView(next, 15);
    },
);

onBeforeUnmount(() => {
    map?.remove();
});
</script>

<template>
    <div>
        <div ref="mapEl" class="h-80 w-full overflow-hidden rounded-xl border border-slate-200" />
        <p v-if="interactive" class="mt-2 text-xs text-slate-500">
            Klik peta atau geser penanda untuk mengisi koordinat otomatis.
        </p>
    </div>
</template>
