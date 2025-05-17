import { createI18n } from 'vue-i18n';

// Load JSON messages
import enDashboard from '../../../Interview/resources/js/lang/en/dashboard.json';
import frDashboard from '../../../Interview/resources/js/lang/fr/dashboard.json';
import enSideBar from './lang/en/sideBar.json';
import frSideBar from './lang/fr/sideBar.json';

const messages = {
    en: {
        dashboard: enDashboard,
        sidebar: enSideBar,
    },
    fr: {
        dashboard: frDashboard,
        sidebar: frSideBar,
    },
};

const i18n = createI18n({
    legacy: false,
    locale: 'fr',
    fallbackLocale: 'en',
    messages,
});

export default i18n;
