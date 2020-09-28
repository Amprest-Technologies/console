<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Subscribe {{ project.name }} to {{ service.name }}
      </h2>
    </template>

    <div class="container mb-4 mx-auto px-4 py-6">
      <div class="flex flex-wrap lg:-mx-2">
        <div class="w-full lg:w-2/3 mb-4 px-2">
          <div class="bg-white shadow rounded px-5 py-4">
            <form @submit.prevent="onSubmit">
              <!-- Heading. -->
              <h2 class="font-bold playfair-font text-3xl">Choose a tier</h2>

              <!-- Tiers. -->
              <div class="radio-group my-4">
                <div class="flex mb-1">
                  <div
                    class="flex items-center mr-8"
                    v-for="tier in service.tiers"
                    :key="`tier-${tier.id}`"
                  >
                    <input
                      type="radio"
                      name="tier"
                      :value="tier.id"
                      :id="`tier-${tier.id}`"
                      @change="onSubscribe(tier)"
                      v-model.number="$v.subscription.tier_id.$model"
                    />
                    <label
                      :for="`tier-${tier.id}`"
                      class="text-md italic work-sans-font ml-2"
                      >{{ tier.name }} (KES
                      {{ tier.price.toLocaleString() }})</label
                    >
                  </div>
                </div>
                <div class="feedback" v-if="$v.subscription.tier_id.$error">
                  <p class="text-red-500 text-sm">You must select a tier</p>
                </div>
              </div>

              <!-- Submission button. -->
              <button
                class="bg-blue-500 transition ease-out duration-700 text-white py-2 px-4 border border-gray-400 rounded shadow work-sans-font"
              >
                Button
              </button>
            </form>
          </div>
        </div>

        <div class="w-full lg:w-1/3 mb-4 px-2">
          <div id="summary" class="bg-white shadow rounded p-3 px-5">
            <h2 class="playfair-font font-bold text-3xl mb-4">Your Summary</h2>
            <div class="items flex flex-col" v-if="hasSubscription">
              <div class="item flex mb-2">
                <div class="item-name avenir-font font-semibold">Tier</div>
                <div class="item-details ml-auto italic work-sans-font">
                  {{ subscription.tier.name }}
                </div>
              </div>
              <div class="item flex mb-2">
                <div class="item-name avenir-font font-semibold">Price</div>
                <div class="item-details ml-auto italic work-sans-font">
                  KES {{ subscription.amount.toLocaleString() }}
                </div>
              </div>
              <div class="item flex mb-2">
                <div class="item-name avenir-font font-semibold">
                  Usage Limit
                </div>
                <div class="item-details ml-auto italic work-sans-font">
                  {{ subscription.usage_limit.toLocaleString() }} requests
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { required, minLength, between } from 'vuelidate/lib/validators'
import AppLayout from "../../Layouts/AppLayout"

// Set API Token on axios.
window.axios.defaults.headers.common["Api-Token"] = process.env.MIX_AMPREST_PAYMENT_API_TOKEN;

export default {
  components: { AppLayout },
  props: {
    project: { type: Object, default: () => { } },
    service: { type: Object, default: () => { } },
  },

  data() {
    return {
      message: null,
      isLoading: false,
      transaction: null,
      baseURI: `https://pay.amprest.co.ke`,
      subscription: {
        project_id: this.project.id,
        project: this.project,
        tier_id: null
      },
    }
  },

  computed: {
    hasSubscription: function () {
      return this.subscription.tier_id !== null
    }
  },

  validations: {
    subscription: { tier_id: { required } }
  },

  methods: {
    /**
   * Add a subscription to a service.
   *
   * @returns {void}
   * @author Brian K. Kiragu <brian@amprest.co.ke>
   */
    onSubscribe(tier) {
      this.subscription = {
        ...this.subscription,
        ...{
          project_id: this.project.id,
          tier_id: tier.id,
          usage_limit: tier.usage_limit,
          amount: tier.price,
          tier: tier,
        },
      };

      // Touch vuelidate.
      this.$v.subscription.$touch();

      // Scroll to the submit button.
      document.getElementById("summary").scrollIntoView({
        behavior: "smooth",
      });
    },

    /**
     * Prepare a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async prepareTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/prepare`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Check a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async checkTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/check`, data)
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
    async retrievedTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/retrieve`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Add the subscription to the project.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async addSubscription(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`/api/v1/projects/${this.project.uuid}/new-subscription`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * User submission.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubmit() {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      this.prepareTransaction({
        business_short_code: 204440,
        bill_ref_number: this.project.uuid,
        transaction_amount: this.subscription.amount,
        transaction_desc: `New Subscription for ${this.project.name}`,
      })
        .then((res) => {
          // Set the transaction.
          this.transaction = { ...res };

          // Remove the loading state.
          this.isLoading = false;
        })
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        });
    },

    /**
     * Check if the transaction was completed.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {void}
     */
    onConfirm() {
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
              .then((data) =>
                // Add the new subscription.
                this.addSubscription(this.subscription)
                  .then((res) =>
                    window.location.replace(`/home/projects/${this.project.uuid}`)
                  )
                  .catch((err) => {
                    this.message = err;
                    this.isLoading = false;
                  })
              )
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
  }
}
</script>

<style>
</style>