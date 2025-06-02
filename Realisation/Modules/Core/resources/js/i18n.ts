import { createI18n } from 'vue-i18n';

// Load JSON messages
import enDashboard from '../../../Interview/resources/js/lang/en/dashboard.json';
import enTemplate from '../../../Interview/resources/js/lang/en/template.json';
import enQuestion from '../../../Interview/resources/js/lang/en/question.json';
import frDashboard from '../../../Interview/resources/js/lang/fr/dashboard.json';
import frTemplate from '../../../Interview/resources/js/lang/fr/template.json';
import frQuestion from '../../../Interview/resources/js/lang/fr/question.json';
import enSideBar from './lang/en/sideBar.json';
import frSideBar from './lang/fr/sideBar.json';

const messages = {
  en: {
    dashboard: enDashboard,
    sidebar: enSideBar,
    template: enTemplate,
    question: enQuestion,
  },
  fr: {
    dashboard: frDashboard,
    sidebar: frSideBar,
    template: frTemplate,
    question: frQuestion,
  },
};

const i18n = createI18n({
  legacy: false,
  locale: 'fr',
  fallbackLocale: 'en',
  messages,
});

export default i18n;
