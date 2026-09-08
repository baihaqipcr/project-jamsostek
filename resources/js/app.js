import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import ErrorBoundary from './Components/ErrorBoundary.vue';
import MainLayout from './Layouts/MainLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Pencatatan Potensi KSI';
const pages = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: async (name) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, pages);

        if (!name.startsWith('Auth/') && name !== 'Welcome' && name !== 'Error') {
            page.default.layout = page.default.layout || MainLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        return createApp({
            render: () =>
                h(ErrorBoundary, null, {
                    default: () => h(App, props),
                }),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#0e7c66',
        showSpinner: false,
    },
});
