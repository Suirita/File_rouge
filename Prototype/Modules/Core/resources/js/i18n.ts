import { createI18n } from 'vue-i18n';

// Load JSON messages
import en from './lang/en/messages.json';
import fr from './lang/fr/messages.json';

const i18n = createI18n({
    legacy: false,
    locale: 'en',
    fallbackLocale: 'en',
    messages: {
        en,
        fr,
    },
});

export default i18n;
