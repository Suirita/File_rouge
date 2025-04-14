/// <reference types="vite/client" />

import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../../../vendor/tightenco/ziggy';
import { initializeTheme } from './composables/useAppearance';
import i18n from './i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pages = {
    ...import.meta.glob<DefineComponent>('./pages/**/*.vue'),
    ...import.meta.glob<DefineComponent>('../../../Branch/resources/js/pages/**/*.vue'),
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const key = Object.keys(pages).find((key) => key.toLowerCase().endsWith(`${name.toLowerCase()}.vue`));
        if (!key) {
            throw new Error(`Page not found: ${name}`);
        }
        return pages[key]();
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
