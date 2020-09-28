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
          <div
            class="bg-white shadow rounded mb-4 px-5 py-4"
            v-if="!transaction"
          >
            <!-- Heading. -->
            <h2 class="font-bold playfair-font text-3xl">Choose a tier</h2>

            <!-- Tiers. -->
            <div class="radio-group my-4">
              <div class="flex flex-col lg:flex-row mb-1">
                <div
                  class="flex items-center mr-8 mb-2"
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
          </div>

          <!-- Make the payment. -->
          <payment-options
            :_transaction="transaction"
            v-if="transaction"
            @completed="onComplete"
          ></payment-options>
        </div>

        <div class="w-full lg:w-1/3 mb-4 px-2">
          <div id="summary" class="bg-white shadow rounded p-3 px-5">
            <h2 class="playfair-font font-bold text-3xl mb-4">Your Summary</h2>

            <!-- Summary Details. -->
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

            <!-- Submission button. -->
            <button
              class="w-full mt-2 bg-blue-500 transition ease-out duration-700 text-white py-2 px-4 border border-gray-400 rounded shadow work-sans-font disabled:opacity-0"
              :disabled="hasSubscription && hasTransaction"
              @click.prevent="onSubmit"
            >
              Subscribe
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { required, minLength, between } from 'vuelidate/lib/validators'
import AppLayout from "../../Layouts/AppLayout"
import PaymentOptions from "../../Components/PaymentOptions"

// Set API Token on axios.
window.axios.defaults.headers.common["Api-Token"] = process.env.MIX_AMPREST_PAYMENT_API_TOKEN;

export default {
  components: { AppLayout, PaymentOptions },
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
    },

    hasTransaction: function () {
      return this.transaction !== null
    }
  },

  validations: {
    subscription: { tier_id: { required } }
  },

  methods: {
    /**
     * Prepare a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    prepareTransaction: async function (data) {
      return new Promise((resolve, reject) =>
        window.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/prepare`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Add the subscription to the project.
     *
     * @returns {ITransaction | string}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    addSubscription: async function (data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`/api/v1/projects/${this.project.uuid}/new-subscription`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Add a subscription to a service.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubscribe: function (tier) {
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
     * User submission.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubmit: function () {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      this.prepareTransaction({
        business_short_code: 204440,
        bill_ref_number: this.project.uuid,
        transaction_amount: this.subscription.amount,
        transaction_desc: `New Subscription for ${this.project.name}`,
      }).then((res) => {
        // Set the transaction.
        this.transaction = { ...res };
        // Remove the loading state.
        this.isLoading = false;
      }).catch((err) => {
        this.message = err;
        this.isLoading = false;
      });
    },

    /**
     * When the completed event is called after successful payment.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onComplete: function () {
      // Add the new subscription.
      this.addSubscription(this.subscription)
        .then((res) => window.location.replace(
          `/projects/${this.project.uuid}`
        ))
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        })
    }
  }
}
</script>

<style>
</style>