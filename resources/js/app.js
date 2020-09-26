require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';

// Ziggy.
Vue.prototype.$route = (...args) => route(...args).url()

Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

const app = document.getElementById('app');

// MomentJS filter.
Vue.filter('fromTime', function (value) {
  if (!value) return ''
  value = value.toString()
  return moment(value).format('MMMM Do YYYY, h:mm A');
})

new Vue({
  render: (h) =>
    h(InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: (name) => require(`./Pages/${name}`).default
      }
    })
}).$mount(app);
