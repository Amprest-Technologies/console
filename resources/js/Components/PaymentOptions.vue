<template>
  <div class="bg-white shadow rounded mb-4 px-5 py-4">
    <ol class="list-decimal px-5">
      <li class="mb-3 work-sans-font">Go to your M-PESA Menu.</li>
      <li class="mb-3 work-sans-font">Go to Lipa na M-PESA.</li>
      <li class="mb-3 work-sans-font">Select Pay Bill.</li>
      <li class="mb-3 work-sans-font">
        Enter
        <strong class="avenir-font text-md">{{
          transaction.business_short_code
        }}</strong>
        as the Business Number.
      </li>
      <li class="mb-3 work-sans-font">
        Enter
        <strong class="avenir-font text-md">{{
          transaction.bill_ref_number
        }}</strong>
        as the Account Number.
      </li>
      <li class="mb-3 work-sans-font">
        Enter
        <strong class="avenir-font text-md"
          >KES {{ transaction.transaction_amount.toLocaleString() }}</strong
        >
        as the Amount.
      </li>
      <li class="mb-3 work-sans-font">Enter your pin.</li>
      <li class="mb-3 work-sans-font">
        Enter confirmation code in the input field below and click 'Confirm'.
      </li>
    </ol>

    <div class="feedback">
      <p class="text-red-600 text-sm italic avenir-font">{{ message }}</p>
    </div>

    <!-- Submission button. -->
    <button
      class="w-full mt-2 bg-teal-600 transition ease-out duration-700 text-white py-2 px-4 border rounded work-sans-font disabled:opacity-0"
      @click.prevent="onConfirm"
    >
      Confirm
    </button>
  </div>
</template>

<script>
// Set API Token on axios.
window.axios.defaults.headers.common["Api-Token"] = process.env.MIX_AMPREST_PAYMENT_API_TOKEN;

export default {
  props: {
    _transaction: { type: Object, default: () => { } }
  },

  data() {
    return {
      message: null,
      isLoading: false,
      baseURI: `https://pay.amprest.co.ke`,
      transaction: { ...this._transaction },
    }
  }
}
</script>

<style>
</style>