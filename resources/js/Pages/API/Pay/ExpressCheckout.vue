<template>
  <div class="container">
    <h1 class="font-bold playfair-font text-3xl mb-3">
      Select a payment method
    </h1>
    <div id="payment-options">
      <component
        class="mb-3"
        v-for="paymentOption in paymentOptions"
        :key="`${paymentOption}-Option`"
        :is="`pay-${paymentOption}`"
        :base-uri="baseURI"
        :base-headers="baseHeaders"
        :payload="payload"
        @completed="onComplete"
      ></component>
    </div>
  </div>
</template>

<script>
import PayMpesa from "./PayMpesa"
import PayTkash from "./PayTkash"
import PayAirtelMoney from "./PayAirtelMoney"

export default {
  components: { PayMpesa, PayTkash, PayAirtelMoney },

  props: {
    paymentOptions: {
      type: Array,
      default: () => ['mpesa', 'tkash', 'airtel-money']
    },

    payload: {
      type: Object,
      default: () => {
        return {
          bill_ref_number: null,
          business_short_code: null,
          transaction_amount: null,
          transaction_desc: null
        }
      }
    }
  },

  data() {
    return {
      baseURI: `https://pay.amprest.co.ke`,
      baseHeaders: {
        'Api-Token': process.env.MIX_AMPREST_PAYMENT_API_TOKEN
      }
    }
  },

  methods: {
    onComplete: function (data) {
      this.$emit('completed', data)
    }
  }
}
</script>

<style>
</style>