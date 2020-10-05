/**
 * Function to register service worker.
 *
 * @author Brian K. Kiragu <brian@amprest.co.ke>
 */
const registerServiceWorker = async () => {
  // Check if service worker is available
  if ('serviceWorker' in navigator) {
    // Register a service worker
    const registration = await navigator.serviceWorker.register(
      // A service worker JS file is separate
      '/service-worker.js',
    );

    // Check if Payment Handler is available
    if (!registration.paymentManager) return;

    registration.paymentManager.userHint = 'Pay with Amprest Technologies';
    registration.paymentManager.instruments.set(
      // Payment instrument key can be any string.
      'amprest-pay',
      // Payment instrument detail
      {
        name: 'Amprest Technologies Web Services',
        method: `https://console.amprest.co.ke/api/pay`,
      },
    );
  }
};

window.addEventListener('load', (e) => {
  // Register the service worker.
  registerServiceWorker();
});

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  forceTLS: true
});
