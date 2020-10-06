<template>
  <div id="pay-mpesa" class="accordion container bg-white rounded shadow">
    <button
      class="accordion-intro flex items-center w-full p-4 focus:outline-none"
      @click.prevent="onInitiate"
    >
      <img src="/img/payments/mpesa.jpg" class="mr-4 w-20" />
      <h2 class="font-semibold avenir-font text-lg">Pay with M-Pesa</h2>
      <img
        src="/img/loader.svg"
        class="ml-auto mr-4"
        width="25"
        v-show="isLoading"
      />
    </button>

    <div
      class="accordion-body transition-all duration-500 ease-in-out"
      v-if="transaction"
    >
      <div class="bg-wheat mb-4 px-5 py-4">
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
            Enter confirmation code in the input field below and click
            'Confirm'.
          </li>
        </ol>

        <div class="feedback">
          <p class="text-red-600 text-sm italic avenir-font">{{ message }}</p>
        </div>

        <!-- Submission button. -->
        <button
          class="w-full mt-2 bg-teal-600 transition ease-out duration-700 text-white py-2 px-4 border rounded work-sans-font disabled:opacity-0 focus:outline-none"
          @click.prevent="onConfirm"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    baseUri: String,
    baseHeaders: { type: Object, default: () => { } },
    payload: { type: Object, default: () => { } }
  },

  data() {
    return {
      isHidden: false,
      isLoading: false,
      message: null,
      transaction: null
    }
  },

  methods: {
    toggleAccordion: function () {
      // Get the elements.
      const trigger = document.querySelector('#pay-mpesa>.accordion-intro');
      const body = document.querySelector('#pay-mpesa>.accordion-body');

      // Check the expanded status.
      this.isHidden = body.classList.contains('hidden');

      // Toggle the class appropriately.
      this.isHidden
        ? body.classList.remove('hidden')
        : body.classList.add('hidden')

    },

    /**
     * Prepare a C2B Transaction.
     *
     * @param {Object} data
     * @returns {ITransaction | string}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    prepareTransaction: async function (data) {
      return new Promise((resolve, reject) =>
        window.axios
          .post(`${this.baseUri}/mobile-money/safaricom/c2b/prepare`, data, {
            headers: this.baseHeaders
          })
          .then(({ data }) => {
            console.log(data);
            resolve(data);
          })
          .catch(({ message }) => {
            console.error(message);
            reject(message);
          })
      );
    },

    /**
     * Check a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    checkTransaction: async function (data) {
      return new Promise((resolve, reject) =>
        window.axios
          .post(`${this.baseUri}/mobile-money/safaricom/c2b/check`, data, {
            headers: this.baseHeaders
          })
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Mark a transaction as retrieved.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    retrievedTransaction: async function (data) {
      return new Promise((resolve, reject) =>
        window.axios
          .post(`${this.baseUri}/mobile-money/safaricom/c2b/retrieve`, data, {
            headers: this.baseHeaders
          })
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Initiate a payment request and show the details.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onInitiate: function () {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      // Prepare the transaction.
      this.prepareTransaction(this.payload)
        .then(res => {
          // Set the loading state.
          this.isLoading = false
          // Set the transaction.
          this.transaction = { ...res }
        })
        .catch(err => console.error(err))
    },

    /**
     * Check if the transaction was completed.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {void}
     */
    onConfirm: function () {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      // Send the request.
      this.checkTransaction({
        business_short_code: this.transaction.business_short_code,
        bill_ref_number: this.transaction.bill_ref_number,
        transaction_amount: this.transaction.transaction_amount,
        transaction_type: "Pay Bill",
      })
        .then((res) => {
          this.transaction = { ...res };

          if (this.transaction.status === "COMPLETED") {
            // Retrieve the transaction.
            this.retrievedTransaction({
              transaction_id: this.transaction.transaction_id,
            })
              .then((data) => {
                this.message = null;
                this.isLoading = false;
                this.$emit('completed', this.transaction)
              })
              .catch((message) => {
                this.message = message;
                this.isLoading = false;
              });
          } else {
            this.message = "This transaction has not yet been completed.";
            this.isLoading = false;
          }
        })
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        });
    }
  },
}
</script>

<style lang="scss" scoped>
.bg-wheat {
  --bg-opacity: 0.8;
  background-color: wheat;
  background-color: rgba(wheat, var(--bg-opacity));
}
</style>