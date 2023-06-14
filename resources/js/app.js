import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { i18nVue } from 'laravel-vue-i18n';
import { setupCalendar } from 'v-calendar';
import methods from './methods';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue, Ziggy)
      .use(setupCalendar, {})
      .use(i18nVue, {
        resolve: (lang) => resolvePageComponent(`../../lang/${lang}.json`, import.meta.glob('../../lang/*.json')),
      })
      .mixin({ methods: Object.assign({ route }, methods) })
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
