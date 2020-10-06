<template>
  <div id="checkout" class="bg-wheat px-3 py-3">
    <!-- Cancel request. -->
    <button
      class="focus:outline-none"
      @click.prevent="onCancel('The payment request was cancelled by user')"
    >
      <img src="/img/icons/close-outline.svg" alt="Cancel icon" width="50" />
    </button>

    <div id="intro" class="px-3 my-5">
      <h1 class="avenir-font font-bold text-3xl">Payment Methods</h1>
      <h3 class="work-sans-font text-sm">
        Powered by
        <a
          class="text-blue-500"
          href="https://console.amprest.co.ke"
          target="_blank"
          >Amprest Technologies</a
        >
      </h3>
    </div>

    <!-- Payment methods. -->
    <div v-if="hasPayload" id="payment-methods" class="mt-10 px-3">
      <h1 class="font-bold avenir-font text-xl mb-3">
        Select a payment method
      </h1>

      <ExpressCheckout
        :payment-options="['mpesa']"
        :payload="payload"
        @completed="onPay"
        @failed="onCancel"
      />
    </div>

    <div v-else class="my-10 px-3">
      <h1 class="work-sans-font text-semibold">
        Could not process payment. Kindly retry.
      </h1>
    </div>
  </div>
</template>

<script>
import ExpressCheckout from "../../../Components/Checkout/ExpressCheckout"

export default {
  components: { ExpressCheckout },

  data() {
    return {
      client: null,
      payload: {},
    }
  },

  computed: {
    hasPayload: function () {
      return Object.entries(this.payload).length > 0
        && Object.values(this.payload).every(el => el)
    }
  },

  methods: {
    /**
     * When a user confirms payment.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {void}
     */
    onPay: function (paymentResponse) {
      // When `client` is not found, there's no associated service worker
      if (!this.client) return;

      // Respond to the service worker with arbitrary message.
      const response = {
        methodName: `https://console.amprest.co.ke/api/pay`,
        details: { ...this.payload, ...paymentResponse },
      };

      this.client.postMessage(response);
      // Chrome will close all windows in the scope of the service worker
      // after the service worker responds to the 'paymentrequest' event.
    },

    /**
     * When a user cancels payment.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {void}
     */
    onCancel(message) {
      if (!this.client) return;
      // Chrome will close all windows in the scope of the service worker
      // after the service worker responds to the 'paymentrequest' event.
      this.client.postMessage(message);
    },
  },

  /**
   * Mounted (lifecycle hook)
   *
   * @author Brian K. Kiragu <brian@amprest.co.ke>
   * @returns {void}
   */
  mounted() {
    // Listen to messages from service worker
    navigator.serviceWorker.addEventListener("message", (e) => {
      // Preserve service worker object for later use
      this.client = e.source;

      // Set the axios instance headers.
      window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
      window.axios.defaults.headers.common["x-api-key"] = e.data.methodData[0].data.apiKey;

      // Set the data properties.
      this.payload = {
        ...{
          bill_ref_number: e.data.methodData[0].data.transactionType,
          business_short_code: e.data.methodData[0].data.businessShortCode,
          transaction_amount: parseInt(e.data.total.value),
          transaction_desc: `Express Payment from ${e.data.paymentRequestOrigin}`,
        },
      };
    });

    // Send `payment_app_window_ready` as a sign that the window is ready.
    navigator.serviceWorker.controller.postMessage("payment_app_window_ready");
  }
}
</script>

<style lang="scss" scoped>
.bg-wheat {
  background: rgba(wheat, 1);
}

#checkout {
  min-height: 100vh;
}
</style>