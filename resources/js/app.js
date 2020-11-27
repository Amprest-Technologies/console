/* eslint-disable global-require */
/* eslint-disable import/no-dynamic-require */
/* eslint-disable no-undef */

import Vue from 'vue';
import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import Vuelidate from 'vuelidate';

require('./bootstrap');

// Ziggy.
Vue.prototype.$route = (...args) => route(...args).url();

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(Vuelidate);

const app = document.getElementById('app');

new Vue({
  render: (h) => h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: (name) => require(`./Pages/${name}`).default,
    },
  }),
}).$mount(app);
