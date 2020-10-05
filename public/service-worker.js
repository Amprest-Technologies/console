const apiOrigin = 'https://console.amprest.co.ke';
const methodName = `${apiOrigin}/api/pay`;
const checkoutURL = `${apiOrigin}/api/pay/express`;

function PromiseResolver() {
  /** @private {function(T=): void} */
  this.resolve_;

  /** @private {function(*=): void} */
  this.reject_;

  /** @private {!Promise<T>} */
  this.promise_ = new Promise(function (resolve, reject) {
    this.resolve_ = resolve;
    this.reject_ = reject;
  }.bind(this));
}

PromiseResolver.prototype = {
  /** @return {!Promise<T>} */
  get promise() {
    return this.promise_;
  },

  /** @return {function(T=): void} */
  get resolve() {
    return this.resolve_;
  },

  /** @return {function(*=): void} */
  get reject() {
    return this.reject_;
  },
};

// You'll need a polyfill for `PromiseResolver`
// As it's not implemented in Chrome yet.
let resolver = new PromiseResolver();
let payment_request_event;

self.addEventListener('canmakepayment', function (e) {
  e.respondWith(true);
});

self.addEventListener('paymentrequest', (e) => {
  // Preserve the event for future use
  payment_request_event = e;

  // Pass a promise that resolves when payment is done.
  e.respondWith(resolver.promise);

  // Open the checkout page.
  e.openWindow(checkoutURL)
    .then(client => {
      if (client === null) {
        // Make sure to reject the promise on failure
        resolver.reject('Failed to open window');
      }
    })
    .catch(err => {
      // Make sure to reject the promise on failure
      resolver.reject(err);
    });
});

self.addEventListener('message', e => {
  // Determine a message that tells the service worker that
  // the window is ready. In this case `payment_app_window_ready`.
  if (e.data === "payment_app_window_ready") {
    sendPaymentRequest();
    return;
  }

  // Resolve with the information passed from frontend.
  // This information will be passed back to the Payment Request
  // a merchant has invoked as a resolution of .show() method
  // for the Payment Requests.
  resolver.resolve(e.data);

  // // This app sets `methodName` to be a sign to proceed.
  // if (e.data.methodName === methodName) {
  //   // Resolve with the information passed from frontend.
  //   // This information will be passed back to the Payment Request
  //   // a merchant has invoked as a resolution of .show() method.
  //   resolver.resolve(e.data);
  // } else {
  //   resolver.reject(e.data);
  // }
});

const sendPaymentRequest = () => {
  // If `payment_request_event` is not set, this isn't a message
  // preceded by `paymentrequest` event.
  if (!payment_request_event) return;

  // Query all open windows
  clients.matchAll({
    includeUncontrolled: false,
    type: 'window'
  }).then(clientList => {
    // Send a message that contains information about the payment.
    for (let client of clientList) {
      client.postMessage({
        total: payment_request_event.total,
        paymentRequestOrigin: payment_request_event.paymentRequestOrigin,
        methodData: payment_request_event.methodData
      });
    }
  });
}
