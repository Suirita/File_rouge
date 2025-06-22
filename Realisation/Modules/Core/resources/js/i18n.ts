import { createI18n } from 'vue-i18n';

// Load JSON messages
import enDashboard from '../../../Interview/resources/js/lang/en/dashboard.json';
import enInterview from '../../../Interview/resources/js/lang/en/interview.json';
import enQuestion from '../../../Interview/resources/js/lang/en/question.json';
import enTemplate from '../../../Interview/resources/js/lang/en/template.json';
import frDashboard from '../../../Interview/resources/js/lang/fr/dashboard.json';
import frInterview from '../../../Interview/resources/js/lang/fr/interview.json';
import frQuestion from '../../../Interview/resources/js/lang/fr/question.json';
import frTemplate from '../../../Interview/resources/js/lang/fr/template.json';
import enSideBar from './lang/en/sideBar.json';
import frSideBar from './lang/fr/sideBar.json';

const messages = {
  en: {
    sidebar: enSideBar,
    dashboard: enDashboard,
    interview: enInterview,
    template: enTemplate,
    question: enQuestion,
  },
  fr: {
    sidebar: frSideBar,
    dashboard: frDashboard,
    interview: frInterview,
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
